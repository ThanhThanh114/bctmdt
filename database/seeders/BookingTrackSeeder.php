<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Inserts a test booking with code BK20251029163438866 if it does not exist.
     * WARNING: Intended for local/dev environments only.
     *
     * @return void
     */
    public function run()
    {
        $code = 'BK20251029163438866';

        $exists = DB::table('dat_ve')->whereRaw("REPLACE(ma_ve, ' ', '') = ?", [$code])->exists();
        if ($exists) {
            echo "Booking $code already exists.\n";
            return;
        }

        // Find any existing trip and user to associate the booking with
        $trip = DB::table('chuyen_xe')->first();
        $user = DB::table('users')->first();

        if (! $trip || ! $user) {
            echo "No trip or user found to link the test booking. Seeder skipped.\n";
            return;
        }

        DB::table('dat_ve')->insert([
            'user_id' => $user->id,
            'chuyen_xe_id' => $trip->id,
            'ma_ve' => $code,
            'so_ghe' => 'A01',
            'ngay_dat' => now(),
            'trang_thai' => 'Đã thanh toán',
            'ten_khach_hang' => $user->fullname ?? null,
            'sdt_khach_hang' => $user->phone ?? null,
            'email_khach_hang' => $user->email ?? null,
            'total_price' => 0,
        ]);

        echo "Inserted test booking $code\n";
    }
}
