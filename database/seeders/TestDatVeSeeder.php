<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DatVe;
use App\Models\User;
use App\Models\ChuyenXe;
use Carbon\Carbon;

class TestDatVeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy users và chuyến xe có sẵn
        $users = User::all();
        $chuyenXes = ChuyenXe::all();

        if ($users->isEmpty() || $chuyenXes->isEmpty()) {
            echo "Cần có dữ liệu users và chuyến xe trước!\n";
            return;
        }

        // Tạo 20 booking mẫu
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $chuyenXe = $chuyenXes->random();

            // Random số ghế
            $seatCount = rand(1, 3);
            $seats = [];
            for ($j = 0; $j < $seatCount; $j++) {
                $seats[] = 'A' . str_pad(rand(1, 20), 2, '0', STR_PAD_LEFT);
            }

            // Random trạng thái
            $statuses = ['Đã đặt', 'Đã thanh toán', 'Đã hủy'];
            $status = $statuses[array_rand($statuses)];

            DatVe::create([
                'user_id' => $user->id,
                'chuyen_xe_id' => $chuyenXe->id,
                'ma_ve' => 'BK' . date('Y') . date('m') . date('d') . str_pad($i, 8, '0', STR_PAD_LEFT),
                'so_ghe' => implode(',', $seats),
                'trang_thai' => $status,
                'ngay_dat' => Carbon::now()->subDays(rand(0, 30))
            ]);
        }

        echo "Đã tạo 20 booking mẫu!\n";
    }
}
