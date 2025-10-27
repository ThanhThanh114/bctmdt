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
        Schema::table('users', function (Blueprint $table) {
            $table->string('locked_original_role', 20)->nullable()->after('ma_nha_xe')->comment('Role gốc trước khi bị khóa');
            $table->string('locked_original_ma_nha_xe', 10)->nullable()->after('locked_original_role')->comment('Mã nhà xe gốc trước khi bị khóa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['locked_original_role', 'locked_original_ma_nha_xe']);
        });
    }
};
