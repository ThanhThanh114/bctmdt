<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Hash;

echo "Testing authentication after password fixes...\n";

try {
    // Test a user that was properly hashed before
    $existingUser = User::where('username', 'admin')->first();
    if ($existingUser) {
        echo "Testing existing user 'admin'...\n";
        $authService = new AuthenticationService();

        // Test with wrong password first
        $result = $authService->authenticate('admin', 'wrongpassword');
        if (!$result['success']) {
            echo "✅ Correctly rejected wrong password\n";
        }

        // Test with a common password first
        $result = $authService->authenticate('admin', 'admin');
        if ($result['success']) {
            echo "✅ Authentication successful for 'admin' with password 'admin'\n";
        } else {
            // Try with the default password that was set
            $result = $authService->authenticate('admin', 'defaultpassword123');
            if ($result['success']) {
                echo "✅ Authentication successful for 'admin' with default password\n";
            } else {
                echo "❌ Authentication failed for 'admin': " . $result['message'] . "\n";
            }
        }
    }

    // Test a user that had plain text password fixed
    $fixedUser = User::where('username', 'annguyen')->first();
    if ($fixedUser) {
        echo "Testing fixed user 'annguyen'...\n";
        $authService = new AuthenticationService();

        // Test with the default password that was set
        $result = $authService->authenticate('annguyen', 'defaultpassword123');
        if ($result['success']) {
            echo "✅ Authentication successful for 'annguyen'\n";
        } else {
            echo "❌ Authentication failed for 'annguyen': " . $result['message'] . "\n";
        }
    }

    // Verify all passwords are now properly hashed
    $users = User::all();
    $allProperlyHashed = true;

    foreach ($users as $user) {
        if (Hash::needsRehash($user->password)) {
            echo "❌ User '{$user->username}' still needs rehashing\n";
            $allProperlyHashed = false;
        }
    }

    if ($allProperlyHashed) {
        echo "✅ All user passwords are properly hashed\n";
    }

} catch (Exception $e) {
    echo "Error during testing: " . $e->getMessage() . "\n";
}
