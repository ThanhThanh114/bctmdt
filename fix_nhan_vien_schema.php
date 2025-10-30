<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING nhan_vien TABLE STRUCTURE ===\n\n";

try {
    // Check current column structure
    $columns = DB::select("DESCRIBE nhan_vien");

    echo "Current columns in nhan_vien table:\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field}: {$column->Type}" . ($column->Null === 'YES' ? ' (nullable)' : '') . "\n";
    }

    // Check chuc_vu column specifically
    echo "\nChecking chuc_vu column:\n";
    $chucVuColumns = DB::select("SHOW COLUMNS FROM nhan_vien LIKE 'chuc_vu'");
    if (!empty($chucVuColumns)) {
        $column = $chucVuColumns[0];
        echo "  Current type: {$column->Type}\n";
        echo "  Current length limit: ~" . (strlen(str_repeat('a', (int)filter_var($column->Type, FILTER_SANITIZE_NUMBER_INT) ?: 50))) . " characters\n";
    }

    echo "\nAttempting to fix chuc_vu column length...\n";

    // Fix the column length to VARCHAR(255)
    DB::statement("ALTER TABLE nhan_vien MODIFY COLUMN chuc_vu VARCHAR(255) NOT NULL");

    echo "✅ Successfully updated chuc_vu column to VARCHAR(255)\n";

    // Verify the fix
    $updatedColumns = DB::select("SHOW COLUMNS FROM nhan_vien LIKE 'chuc_vu'");
    if (!empty($updatedColumns)) {
        $column = $updatedColumns[0];
        echo "Updated type: {$column->Type}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== END CHECK ===\n";
