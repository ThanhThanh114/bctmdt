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
        if (!Schema::hasColumn('nha_xe', 'trang_thai')) {
            Schema::table('nha_xe', function (Blueprint $table) {
                $table->enum('trang_thai', ['hoat_dong', 'bi_khoa'])->default('hoat_dong')
                    ->after('email')->comment('Trạng thái nhà xe');
            });
        }

        if (!Schema::hasColumn('nha_xe', 'ly_do_khoa')
            || !Schema::hasColumn('nha_xe', 'ngay_khoa')
            || !Schema::hasColumn('nha_xe', 'admin_khoa_id')) {
            Schema::table('nha_xe', function (Blueprint $table) {
                if (!Schema::hasColumn('nha_xe', 'ly_do_khoa')) {
                    $table->text('ly_do_khoa')->nullable()->after('trang_thai')
                        ->comment('Lý do khóa nhà xe');
                }
                if (!Schema::hasColumn('nha_xe', 'ngay_khoa')) {
                    $table->timestamp('ngay_khoa')->nullable()->after('ly_do_khoa')
                        ->comment('Ngày khóa');
                }
                if (!Schema::hasColumn('nha_xe', 'admin_khoa_id')) {
                    $table->integer('admin_khoa_id')->nullable()->after('ngay_khoa')
                        ->comment('ID admin khóa');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nha_xe', function (Blueprint $table) {
            if (Schema::hasColumn('nha_xe', 'admin_khoa_id')) {
                $table->dropColumn('admin_khoa_id');
            }
            if (Schema::hasColumn('nha_xe', 'ngay_khoa')) {
                $table->dropColumn('ngay_khoa');
            }
            if (Schema::hasColumn('nha_xe', 'ly_do_khoa')) {
                $table->dropColumn('ly_do_khoa');
            }
            if (Schema::hasColumn('nha_xe', 'trang_thai')) {
                $table->dropColumn('trang_thai');
            }
        });
    }
};
