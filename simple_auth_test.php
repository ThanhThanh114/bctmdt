<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Simple authentication test...\n";

try {
    $adminUser = User::where('username', 'admin')->first();

    if (!$adminUser) {
        echo "❌ Admin user not found\n";
        return;
    }

    echo "Admin user found: {$adminUser->username}\n";
    echo "Password hash: {$adminUser->password}\n";

    // Test if the password hash is valid
    $isValidHash = Hash::check('admin', $adminUser->password);
    echo "Hash::check('admin', password): " . ($isValidHash ? '✅ true' : '❌ false') . "\n";

    // Test with default password
    $isValidDefault = Hash::check('defaultpassword123', $adminUser->password);
    echo "Hash::check('defaultpassword123', password): " . ($isValidDefault ? '✅ true' : '❌ false') . "\n";

    // Test if password needs rehashing
    $needsRehash = Hash::needsRehash($adminUser->password);
    echo "Hash::needsRehash(password): " . ($needsRehash ? '❌ true' : '✅ false') . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
