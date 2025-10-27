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
            $table->text('locked_reason')->nullable()->after('is_active')->comment('Lý do khóa tài khoản');
            $table->timestamp('locked_at')->nullable()->after('locked_reason')->comment('Thời gian khóa');
            $table->integer('locked_by')->nullable()->after('locked_at')->comment('ID admin khóa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['locked_reason', 'locked_at', 'locked_by']);
        });
    }
};
