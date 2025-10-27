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
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->date('ngay_sinh')->nullable()->after('email');
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác'])->nullable()->after('ngay_sinh');
            $table->string('cccd', 20)->nullable()->unique()->after('gioi_tinh');
            $table->text('dia_chi')->nullable()->after('cccd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nhan_vien', function (Blueprint $table) {
            $table->dropColumn(['ngay_sinh', 'gioi_tinh', 'cccd', 'dia_chi']);
        });
    }
};
