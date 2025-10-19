<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tin_tuc', function (Blueprint $table) {
            // Index cho ngày đăng (sắp xếp DESC thường xuyên)
            $table->index('ngay_dang', 'idx_tin_tuc_ngay_dang');

            // Index cho mã tin (primary key lookup)
            if (!Schema::hasColumn('tin_tuc', 'ma_tin')) {
                $table->index('ma_tin', 'idx_tin_tuc_ma_tin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tin_tuc', function (Blueprint $table) {
            $table->dropIndex('idx_tin_tuc_ngay_dang');
            $table->dropIndex('idx_tin_tuc_ma_tin');
        });
    }
};
