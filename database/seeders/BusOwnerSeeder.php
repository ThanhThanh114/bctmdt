<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\NhaXe;
use App\Models\TramXe;
use App\Models\ChuyenXe;
use App\Models\DatVe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BusOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo user bus_owner
        $busOwner = User::create([
            'fullname' => 'Nhà xe Phương Trang',
            'username' => 'phuongtrang_owner',
            'email' => 'busowner@phuongtrang.com',
            'password' => Hash::make('password'),
            'phone' => '19006067',
            'address' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
            'role' => 'bus_owner',
            'ma_nha_xe' => 'PT001'
        ]);

        // Tạo nhà xe
        $nhaXe = NhaXe::create([
            'ma_nha_xe' => 'PT001',
            'ten_nha_xe' => 'Nhà xe Phương Trang',
            'dia_chi' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
            'so_dien_thoai' => '1900 6067',
            'email' => 'info@phuongtrang.com.vn'
        ]);

        // Tạo các trạm xe
        $tramDi = TramXe::create([
            'ten_tram' => 'Bến xe Miền Đông',
            'dia_chi_tram' => '292 Đinh Bộ Lĩnh, Bình Thạnh, TP.HCM'
        ]);

        $tramDen1 = TramXe::create([
            'ten_tram' => 'Bến xe Đà Lạt',
            'dia_chi_tram' => '01 Đường 3/4, Đà Lạt, Lâm Đồng'
        ]);

        $tramDen2 = TramXe::create([
            'ten_tram' => 'Bến xe Nha Trang',
            'dia_chi_tram' => 'Đường 23/10, Nha Trang, Khánh Hòa'
        ]);

        $tramDen3 = TramXe::create([
            'ten_tram' => 'Bến xe Cần Thơ',
            'dia_chi_tram' => 'Đường Nguyễn Văn Linh, Cần Thơ'
        ]);

        // Tạo các chuyến xe
        $chuyenXes = [
            [
                'ma_xe' => 'PT001',
                'ten_xe' => 'TP.HCM - Đà Lạt',
                'ma_nha_xe' => 'PT001',
                'ten_tai_xe' => 'Nguyễn Văn An',
                'sdt_tai_xe' => '0901234567',
                'ma_tram_di' => $tramDi->ma_tram_xe,
                'ma_tram_den' => $tramDen1->ma_tram_xe,
                'ngay_di' => Carbon::now()->addDays(rand(1, 7))->format('Y-m-d'),
                'gio_di' => Carbon::parse('06:00:00')->format('H:i:s'),
                'loai_xe' => 'Giường nằm',
                'so_cho' => 40,
                'so_ve' => rand(25, 35),
                'gia_ve' => 250000,
                'loai_chuyen' => 'Một chiều'
            ],
            [
                'ma_xe' => 'PT002',
                'ten_xe' => 'TP.HCM - Nha Trang',
                'ma_nha_xe' => 'PT001',
                'ten_tai_xe' => 'Trần Thị Bình',
                'sdt_tai_xe' => '0901234568',
                'ma_tram_di' => $tramDi->ma_tram_xe,
                'ma_tram_den' => $tramDen2->ma_tram_xe,
                'ngay_di' => Carbon::now()->addDays(rand(1, 7))->format('Y-m-d'),
                'gio_di' => Carbon::parse('08:30:00')->format('H:i:s'),
                'loai_xe' => 'Ghế ngồi',
                'so_cho' => 45,
                'so_ve' => rand(30, 40),
                'gia_ve' => 200000,
                'loai_chuyen' => 'Một chiều'
            ],
            [
                'ma_xe' => 'PT003',
                'ten_xe' => 'TP.HCM - Cần Thơ',
                'ma_nha_xe' => 'PT001',
                'ten_tai_xe' => 'Lê Văn Cường',
                'sdt_tai_xe' => '0901234569',
                'ma_tram_di' => $tramDi->ma_tram_xe,
                'ma_tram_den' => $tramDen3->ma_tram_xe,
                'ngay_di' => Carbon::now()->addDays(rand(1, 7))->format('Y-m-d'),
                'gio_di' => Carbon::parse('10:00:00')->format('H:i:s'),
                'loai_xe' => 'Limousine',
                'so_cho' => 20,
                'so_ve' => rand(15, 18),
                'gia_ve' => 150000,
                'loai_chuyen' => 'Một chiều'
            ],
            [
                'ma_xe' => 'PT004',
                'ten_xe' => 'TP.HCM - Đà Lạt',
                'ma_nha_xe' => 'PT001',
                'ten_tai_xe' => 'Phạm Thị Dung',
                'sdt_tai_xe' => '0901234570',
                'ma_tram_di' => $tramDi->ma_tram_xe,
                'ma_tram_den' => $tramDen1->ma_tram_xe,
                'ngay_di' => Carbon::now()->format('Y-m-d'),
                'gio_di' => Carbon::parse('14:00:00')->format('H:i:s'),
                'loai_xe' => 'Giường nằm VIP',
                'so_cho' => 32,
                'so_ve' => rand(20, 28),
                'gia_ve' => 300000,
                'loai_chuyen' => 'Một chiều'
            ],
            [
                'ma_xe' => 'PT005',
                'ten_xe' => 'TP.HCM - Nha Trang',
                'ma_nha_xe' => 'PT001',
                'ten_tai_xe' => 'Hoàng Văn Em',
                'sdt_tai_xe' => '0901234571',
                'ma_tram_di' => $tramDi->ma_tram_xe,
                'ma_tram_den' => $tramDen2->ma_tram_xe,
                'ngay_di' => Carbon::now()->format('Y-m-d'),
                'gio_di' => Carbon::parse('16:30:00')->format('H:i:s'),
                'loai_xe' => 'Ghế ngồi',
                'so_cho' => 45,
                'so_ve' => rand(35, 42),
                'gia_ve' => 220000,
                'loai_chuyen' => 'Một chiều'
            ]
        ];

        foreach ($chuyenXes as $chuyenXeData) {
            $chuyenXe = ChuyenXe::create($chuyenXeData);

            // Tạo đặt vé cho chuyến xe này
            $soVeDaDat = $chuyenXe->so_ve;
            for ($i = 0; $i < $soVeDaDat; $i++) {
                $user = User::factory()->create([
                    'role' => 'user',
                    'username' => 'user' . ($i + 1) . '_' . $chuyenXe->id,
                    'phone' => '09' . rand(10000000, 99999999),
                    'address' => 'Địa chỉ khách hàng ' . ($i + 1),
                    'date_of_birth' => '1990-01-01',
                    'gender' => rand(0, 1) ? 'Nam' : 'Nữ'
                ]);

                DatVe::create([
                    'user_id' => $user->id,
                    'chuyen_xe_id' => $chuyenXe->id,
                    'ma_ve' => 'VE' . str_pad($chuyenXe->id, 3, '0', STR_PAD_LEFT) . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'so_ghe' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'ngay_dat' => Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 24)),
                    'trang_thai' => rand(1, 10) <= 8 ? 'Đã xác nhận' : (rand(1, 10) <= 9 ? 'Đã đặt' : 'Đã hủy'),
                    'so_luong_ve' => 1
                ]);
            }
        }

        // Tạo thêm một số user khách hàng để có dữ liệu phong phú
        for ($i = 0; $i < 20; $i++) {
            User::factory()->create([
                'role' => 'user',
                'username' => 'customer' . ($i + 1),
                'phone' => '09' . rand(10000000, 99999999),
                'address' => 'Địa chỉ khách hàng ' . ($i + 1),
                'date_of_birth' => '1990-01-01',
                'gender' => rand(0, 1) ? 'Nam' : 'Nữ'
            ]);
        }

        $this->command->info('Đã tạo dữ liệu mẫu cho Bus Owner thành công!');
        $this->command->info('Nhà xe: ' . $nhaXe->ten_nha_xe);
        $this->command->info('Email đăng nhập: ' . $busOwner->email);
        $this->command->info('Mật khẩu: password');
    }
}