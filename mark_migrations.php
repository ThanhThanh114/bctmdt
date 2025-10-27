<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$migrations = [
    '2025_10_19_072750_create_binh_luan_table',
    '2025_10_19_072812_create_tuyenphobien_table',
    '2025_10_19_072921_create_chuyen_xe_table',
    '2025_10_19_073254_update_tin_tuc_table_charset_to_utf8mb4',
    '2025_10_21_014742_add_created_by_to_nha_xe_table',
    '2025_10_24_add_locked_info_to_users_table',
];

foreach ($migrations as $migration) {
    DB::table('migrations')->insert([
        'migration' => $migration,
        'batch' => 15
    ]);
    echo "✅ Marked: $migration\n";
}

echo "\nDone!\n";
