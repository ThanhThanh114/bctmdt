<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Idempotent: only add columns if they do not exist.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('dat_ve')) {
            return;
        }

        Schema::table('dat_ve', function (Blueprint $table) {
            if (! Schema::hasColumn('dat_ve', 'ten_khach_hang')) {
                $table->string('ten_khach_hang')->nullable()->after('trang_thai');
            }
            if (! Schema::hasColumn('dat_ve', 'sdt_khach_hang')) {
                $table->string('sdt_khach_hang')->nullable()->after('ten_khach_hang');
            }
            if (! Schema::hasColumn('dat_ve', 'email_khach_hang')) {
                $table->string('email_khach_hang')->nullable()->after('sdt_khach_hang');
            }
            if (! Schema::hasColumn('dat_ve', 'total_price')) {
                // Use integer for total_price (amount in VND) to match existing patterns in project
                $table->unsignedBigInteger('total_price')->nullable()->after('email_khach_hang');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('dat_ve')) {
            return;
        }

        Schema::table('dat_ve', function (Blueprint $table) {
            if (Schema::hasColumn('dat_ve', 'total_price')) {
                $table->dropColumn('total_price');
            }
            if (Schema::hasColumn('dat_ve', 'email_khach_hang')) {
                $table->dropColumn('email_khach_hang');
            }
            if (Schema::hasColumn('dat_ve', 'sdt_khach_hang')) {
                $table->dropColumn('sdt_khach_hang');
            }
            if (Schema::hasColumn('dat_ve', 'ten_khach_hang')) {
                $table->dropColumn('ten_khach_hang');
            }
        });
    }
};
