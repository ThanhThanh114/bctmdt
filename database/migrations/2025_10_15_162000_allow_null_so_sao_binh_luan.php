<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove CHECK constraint and allow NULL for so_sao (for replies)
        DB::statement('ALTER TABLE binh_luan MODIFY COLUMN so_sao TINYINT(1) NULL');

        // Allow NULL for nv_id (for admin actions that are not from employees)
        DB::statement('ALTER TABLE binh_luan MODIFY COLUMN nv_id int(11) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE binh_luan MODIFY COLUMN so_sao TINYINT(1) NOT NULL');
        DB::statement('ALTER TABLE binh_luan MODIFY COLUMN nv_id int(11) NOT NULL');
    }
};
