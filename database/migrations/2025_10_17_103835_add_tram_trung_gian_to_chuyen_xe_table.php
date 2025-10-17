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
        Schema::table('chuyen_xe', function (Blueprint $table) {
            $table->text('tram_trung_gian')->nullable()->after('ma_tram_den')->comment('Danh sách mã trạm trung gian, cách nhau bởi dấu phẩy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chuyen_xe', function (Blueprint $table) {
            $table->dropColumn('tram_trung_gian');
        });
    }
};
