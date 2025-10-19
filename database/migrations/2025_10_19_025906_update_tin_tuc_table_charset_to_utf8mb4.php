<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chuyển charset và collation của bảng tin_tuc sang utf8mb4
        DB::statement('ALTER TABLE tin_tuc CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        // Đảm bảo các cột text cũng dùng utf8mb4
        DB::statement('ALTER TABLE tin_tuc MODIFY tieu_de VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE tin_tuc MODIFY noi_dung TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE tin_tuc MODIFY hinh_anh VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert về utf8
        DB::statement('ALTER TABLE tin_tuc CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE tin_tuc MODIFY tieu_de VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE tin_tuc MODIFY noi_dung TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE tin_tuc MODIFY hinh_anh VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL');
    }
};
