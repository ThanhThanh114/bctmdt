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
        Schema::table('nha_xe', function (Blueprint $table) {
            $table->enum('trang_thai', ['hoat_dong', 'bi_khoa'])->default('hoat_dong')->after('email')->comment('Trạng thái nhà xe');
            $table->text('ly_do_khoa')->nullable()->after('trang_thai')->comment('Lý do khóa nhà xe');
            $table->timestamp('ngay_khoa')->nullable()->after('ly_do_khoa')->comment('Ngày khóa');
            $table->integer('admin_khoa_id')->nullable()->after('ngay_khoa')->comment('ID admin khóa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nha_xe', function (Blueprint $table) {
            $table->dropColumn(['trang_thai', 'ly_do_khoa', 'ngay_khoa', 'admin_khoa_id']);
        });
    }
};
