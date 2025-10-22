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
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->integer('ma_bl')->primary();
            $table->integer('parent_id')->nullable();
            $table->integer('user_id');
            $table->integer('chuyen_xe_id');
            $table->text('noi_dung');
            $table->text('noi_dung_tl');
            $table->tinyInteger('so_sao')->nullable();
            $table->timestamp('ngay_bl')->useCurrent();
            $table->timestamp('ngay_tl')->useCurrent();
            $table->integer('nv_id')->nullable();
            $table->datetime('ngay_tao')->useCurrent();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet')->comment('Trạng thái duyệt bình luận');
            $table->datetime('ngay_duyet')->nullable()->comment('Ngày duyệt bình luận');
            $table->text('ly_do_tu_choi')->nullable()->comment('Lý do từ chối duyệt');

            // Add indexes
            $table->index('user_id');
            $table->index('chuyen_xe_id');
            $table->index('nv_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binh_luan');
    }
};