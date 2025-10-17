<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Checking user password hashes...\n";

try {
    $users = User::all();

    if ($users->isEmpty()) {
        echo "No users found in database.\n";
        return;
    }

    echo "Found {$users->count()} users:\n";
    echo "----------------------------------------\n";

    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Username: {$user->username}\n";
        echo "Password Hash: {$user->password}\n";
        echo "Hash Length: " . strlen($user->password) . "\n";

        // Check if password is properly hashed
        if (Hash::needsRehash($user->password)) {
            echo "❌ WARNING: Password needs rehashing!\n";
        } else {
            echo "✅ Password is properly hashed\n";
        }

        echo "----------------------------------------\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
