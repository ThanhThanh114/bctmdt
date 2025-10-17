<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Fixing user password hashes...\n";
echo "WARNING: This will update passwords in the database.\n";
echo "Users with plain text passwords will be assigned a default password.\n\n";

try {
    $users = User::all();
    $fixedCount = 0;
    $problematicUsers = [];

    foreach ($users as $user) {
        $needsUpdate = false;

        // Check if password needs rehashing or is plain text
        if (Hash::needsRehash($user->password)) {
            $needsUpdate = true;

            // If it's plain text (short length), we need to set a default password
            if (strlen($user->password) < 20) {
                echo "User '{$user->username}' has plain text password. Setting default password.\n";
                $newPassword = 'defaultpassword123'; // You should change this
                $user->password = Hash::make($newPassword);
                $problematicUsers[] = [
                    'username' => $user->username,
                    'new_password' => $newPassword
                ];
            } else {
                // It's a hash that needs updating to current standards
                echo "User '{$user->username}' has old hash format. Rehashing...\n";
                $user->password = Hash::make($user->password); // This will rehash the existing password
            }
        }

        if ($needsUpdate) {
            $user->save();
            $fixedCount++;
            echo "✅ Updated password for user: {$user->username}\n";
        }
    }

    echo "\nSummary:\n";
    echo "Fixed {$fixedCount} user passwords.\n";

    if (!empty($problematicUsers)) {
        echo "\nUsers with default passwords set:\n";
        foreach ($problematicUsers as $problemUser) {
            echo "- {$problemUser['username']}: {$problemUser['new_password']}\n";
        }
        echo "\nIMPORTANT: Please change these default passwords for security!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
