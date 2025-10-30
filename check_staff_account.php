<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== CHECKING STAFF ACCOUNT 'new2' ===\n\n";

$staff = User::where('username', 'new2')->first();

if ($staff) {
    echo "Staff account found:\n";
    echo "  ID: {$staff->id}\n";
    echo "  Username: {$staff->username}\n";
    echo "  Role: {$staff->role}\n";
    echo "  ma_nha_xe: " . ($staff->ma_nha_xe ?? 'NULL') . "\n";
    echo "  Fullname: {$staff->fullname}\n";
} else {
    echo "Staff account 'new2' not found!\n";
}

echo "\n=== ALL STAFF ACCOUNTS ===\n\n";
$staffs = User::where('role', 'staff')->get();
foreach ($staffs as $s) {
    echo "  - {$s->username} ({$s->fullname}) - ma_nha_xe: " . ($s->ma_nha_xe ?? 'NULL') . "\n";
}

echo "\n=== END CHECK ===\n";
