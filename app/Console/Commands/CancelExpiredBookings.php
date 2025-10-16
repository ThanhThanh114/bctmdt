<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DatVe;
use Illuminate\Support\Facades\DB;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Tự động hủy các booking quá 15 phút chưa thanh toán';

    public function handle()
    {
        $expiredTime = now()->subMinutes(15);

        $expiredBookings = DatVe::where('trang_thai', 'Đã đặt')
            ->where('ngay_dat', '<', $expiredTime)
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            DatVe::where('trang_thai', 'Đã đặt')
                ->where('ngay_dat', '<', $expiredTime)
                ->update(['trang_thai' => 'Đã hủy']);

            $this->info("✓ Đã hủy $count booking quá hạn");
        } else {
            $this->info("Không có booking nào cần hủy");
        }

        return 0;
    }
}
