<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChuyenXe;
use App\Models\TramXe;
use App\Models\NhaXe;
use Carbon\Carbon;

class FutureTripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tramXes = TramXe::all();

        if ($tramXes->count() < 2) {
            echo "Cần có ít nhất 2 trạm xe!\n";
            return;
        }

        // Lấy nhà xe đầu tiên hoặc tạo mới nếu chưa có
        $nhaXe = NhaXe::first();
        if (!$nhaXe) {
            $nhaXe = NhaXe::create([
                'ten_nha_xe' => 'Nhà xe Test',
                'dia_chi' => 'Địa chỉ test',
                'so_dien_thoai' => '0123456789'
            ]);
        }

        // Tạo 10 chuyến xe trong 30 ngày tới
        for ($i = 1; $i <= 10; $i++) {
            $tramDi = $tramXes->random();

            // Lấy trạm đến khác trạm đi
            $tramDenOptions = $tramXes->filter(function ($tram) use ($tramDi) {
                return $tram->id != $tramDi->id;
            });

            // Nếu chỉ có 1 trạm, dùng lại trạm đó
            $tramDen = $tramDenOptions->count() > 0 ? $tramDenOptions->random() : $tramXes->random();

            $ngay_di = Carbon::now()->addDays(rand(1, 30))->format('Y-m-d');
            $gio_di = Carbon::now()->addDays(rand(1, 30))->setTime(rand(6, 20), [0, 15, 30, 45][rand(0, 3)])->format('Y-m-d H:i:s');

            ChuyenXe::create([
                'tram_xe_di_id' => $tramDi->id,
                'tram_xe_den_id' => $tramDen->id,
                'nha_xe_id' => $nhaXe->id,
                'ngay_di' => $ngay_di,
                'gio_di' => $gio_di,
                'loai_xe' => ['Giường nằm', 'Ghế ngồi', 'Limousine'][rand(0, 2)],
                'so_cho' => [30, 40, 45][rand(0, 2)],
                'gia_ve' => rand(100, 500) * 1000,
                'trang_thai' => 'Đang hoạt động'
            ]);
        }

        echo "Đã tạo 10 chuyến xe trong tương lai!\n";
    }
}
