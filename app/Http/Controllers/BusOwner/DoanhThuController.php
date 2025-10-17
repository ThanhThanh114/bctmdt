<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoanhThuController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        // Get year and month from request or use current
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Revenue statistics
        $stats = $this->getRevenueStats($busCompany->ma_nha_xe, $year, $month);

        // Monthly revenue for chart
        $monthlyRevenue = $this->getMonthlyRevenue($busCompany->ma_nha_xe, $year);

        // Daily revenue for current month
        $dailyRevenue = $this->getDailyRevenue($busCompany->ma_nha_xe, $year, $month);

        // Top trips by revenue
        $topTrips = $this->getTopTrips($busCompany->ma_nha_xe, $year, $month);

        return view('AdminLTE.bus_owner.doanh_thu.index', compact(
            'busCompany',
            'stats',
            'monthlyRevenue',
            'dailyRevenue',
            'topTrips',
            'year',
            'month'
        ));
    }

    private function getRevenueStats($maNhaXe, $year, $month)
    {
        // Total revenue for selected month
        $monthlyRevenue = DatVe::join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $maNhaXe)
            ->where('dat_ve.trang_thai', 'Đã thanh toán')
            ->whereYear('dat_ve.ngay_dat', $year)
            ->whereMonth('dat_ve.ngay_dat', $month)
            ->selectRaw('COUNT(*) as total_bookings, SUM(chuyen_xe.gia_ve) as total_revenue')
            ->first();

        // Total revenue for year
        $yearlyRevenue = DatVe::join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $maNhaXe)
            ->where('dat_ve.trang_thai', 'Đã thanh toán')
            ->whereYear('dat_ve.ngay_dat', $year)
            ->sum('chuyen_xe.gia_ve');

        // Today's revenue
        $todayRevenue = DatVe::join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $maNhaXe)
            ->where('dat_ve.trang_thai', 'Đã thanh toán')
            ->whereDate('dat_ve.ngay_dat', today())
            ->selectRaw('COUNT(*) as total_bookings, SUM(chuyen_xe.gia_ve) as total_revenue')
            ->first();

        return [
            'monthly_revenue' => $monthlyRevenue->total_revenue ?? 0,
            'monthly_bookings' => $monthlyRevenue->total_bookings ?? 0,
            'yearly_revenue' => $yearlyRevenue ?? 0,
            'today_revenue' => $todayRevenue->total_revenue ?? 0,
            'today_bookings' => $todayRevenue->total_bookings ?? 0,
            'average_booking' => $monthlyRevenue->total_bookings > 0
                ? ($monthlyRevenue->total_revenue / $monthlyRevenue->total_bookings)
                : 0,
        ];
    }

    private function getMonthlyRevenue($maNhaXe, $year)
    {
        $data = DatVe::select(
            DB::raw('MONTH(ngay_dat) as month'),
            DB::raw('SUM(chuyen_xe.gia_ve) as revenue'),
            DB::raw('COUNT(*) as bookings')
        )
            ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $maNhaXe)
            ->where('dat_ve.trang_thai', 'Đã thanh toán')
            ->whereYear('ngay_dat', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Fill missing months with zero
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = [
                'month' => $i,
                'revenue' => $data->has($i) ? $data[$i]->revenue : 0,
                'bookings' => $data->has($i) ? $data[$i]->bookings : 0,
            ];
        }

        return $result;
    }

    private function getDailyRevenue($maNhaXe, $year, $month)
    {
        return DatVe::select(
            DB::raw('DAY(ngay_dat) as day'),
            DB::raw('SUM(chuyen_xe.gia_ve) as revenue'),
            DB::raw('COUNT(*) as bookings')
        )
            ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $maNhaXe)
            ->where('dat_ve.trang_thai', 'Đã thanh toán')
            ->whereYear('ngay_dat', $year)
            ->whereMonth('ngay_dat', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    private function getTopTrips($maNhaXe, $year, $month)
    {
        return ChuyenXe::select('chuyen_xe.id', 'chuyen_xe.ten_xe', 'chuyen_xe.ma_xe', 'chuyen_xe.gia_ve')
            ->join('dat_ve', 'chuyen_xe.id', '=', 'dat_ve.chuyen_xe_id')
            ->where('chuyen_xe.ma_nha_xe', $maNhaXe)
            ->where('dat_ve.trang_thai', 'Đã thanh toán')
            ->whereYear('dat_ve.ngay_dat', $year)
            ->whereMonth('dat_ve.ngay_dat', $month)
            ->groupBy('chuyen_xe.id', 'chuyen_xe.ten_xe', 'chuyen_xe.ma_xe', 'chuyen_xe.gia_ve')
            ->selectRaw('COUNT(*) as bookings_count, SUM(chuyen_xe.gia_ve) as total_revenue')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
    }

    public function export(Request $request)
    {
        // TODO: Implement export to Excel/PDF
        return back()->with('info', 'Chức năng xuất báo cáo đang được phát triển.');
    }
}
