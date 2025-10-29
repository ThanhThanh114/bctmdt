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
        // Add columns only if they don't already exist to make this migration idempotent/safe
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(1)->after('role')
                    ->comment('Trạng thái tài khoản: 1=hoạt động, 0=bị khóa');
            });
        }

        if (!Schema::hasColumn('users', 'locked_reason')
            || !Schema::hasColumn('users', 'locked_at')
            || !Schema::hasColumn('users', 'locked_by')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'locked_reason')) {
                    $table->text('locked_reason')->nullable()->after('is_active')
                        ->comment('Lý do khóa tài khoản');
                }
                if (!Schema::hasColumn('users', 'locked_at')) {
                    $table->timestamp('locked_at')->nullable()->after('locked_reason')
                        ->comment('Thời gian khóa');
                }
                if (!Schema::hasColumn('users', 'locked_by')) {
                    $table->integer('locked_by')->nullable()->after('locked_at')
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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'locked_by')) {
                $table->dropColumn('locked_by');
            }
            if (Schema::hasColumn('users', 'locked_at')) {
                $table->dropColumn('locked_at');
            }
            if (Schema::hasColumn('users', 'locked_reason')) {
                $table->dropColumn('locked_reason');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
