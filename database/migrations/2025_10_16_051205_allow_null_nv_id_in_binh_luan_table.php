<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('binh_luan', function (Blueprint $table) {
            // Allow nv_id to be null for replies from admin users who may not be in nhan_vien table
            $table->integer('nv_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('binh_luan', function (Blueprint $table) {
            // Revert nv_id to not null
            $table->integer('nv_id')->nullable(false)->change();
        });
    }
};
