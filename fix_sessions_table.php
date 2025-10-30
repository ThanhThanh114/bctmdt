<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING SESSIONS TABLE ===\n\n";

try {
    // Check if sessions table exists
    $hasTable = DB::connection()->getPdo()->exec("SHOW TABLES LIKE 'sessions'");
    echo "Sessions table exists: " . ($hasTable !== false ? "Yes" : "No") . "\n";

    if ($hasTable === false) {
        echo "Sessions table does not exist!\n";
        echo "Creating sessions table...\n";

        // Create sessions table
        DB::connection()->getPdo()->exec("
            CREATE TABLE sessions (
                id VARCHAR(255) NOT NULL,
                user_id BIGINT UNSIGNED NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload LONGTEXT NOT NULL,
                last_activity INT NOT NULL,
                PRIMARY KEY (id)
            )
        ");
        echo "Sessions table created successfully!\n";
    }

    // Check recent sessions
    $sessions = DB::table('sessions')
        ->orderBy('last_activity', 'desc')
        ->limit(5)
        ->get();

    echo "\nRecent sessions:\n";
    if ($sessions->isEmpty()) {
        echo "  No sessions found\n";
    } else {
        foreach ($sessions as $session) {
            $time = date('Y-m-d H:i:s', $session->last_activity);
            echo "  ID: " . substr($session->id, 0, 20) . "... | Last Activity: {$time} | User ID: " . ($session->user_id ?? 'NULL') . "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== END CHECK ===\n";
