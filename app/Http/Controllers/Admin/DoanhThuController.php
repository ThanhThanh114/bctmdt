<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoanhThuController extends Controller
{
    /**
     * Display revenue dashboard
     */
    public function index(Request $request)
    {
        // Lấy tham số lọc
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $reportType = $request->input('report_type', 'month');

        // Tổng quan doanh thu
        $stats = [
            'today_revenue' => $this->calculateDailyRevenue(Carbon::today()),
            'yesterday_revenue' => $this->calculateDailyRevenue(Carbon::yesterday()),
            'month_revenue' => $this->calculateMonthlyRevenue(date('Y'), date('m')),
            'last_month_revenue' => $this->calculateMonthlyRevenue(
                date('Y', strtotime('-1 month')),
                date('m', strtotime('-1 month'))
            ),
            'year_revenue' => $this->calculateYearlyRevenue(date('Y')),
            'total_bookings' => DatVe::where('trang_thai', '!=', 'Đã hủy')->count(),
            'total_cancelled' => DatVe::where('trang_thai', 'Đã hủy')->count(),
        ];

        // Dữ liệu cho biểu đồ theo LOẠI BÁO CÁO được chọn
        $dailyRevenue = [];
        $dailyTickets = [];
        $monthlyRevenue = [];
        $monthlyTickets = [];
        $yearlyRevenue = [];
        $yearlyTickets = [];

        if ($reportType === 'day') {
            // Theo Ngày - Hiển thị các ngày trong tháng được chọn
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = Carbon::create($year, $month, $d);
                $dateKey = $date->format('d/m');
                $dailyRevenue[$dateKey] = $this->calculateDailyRevenue($date);
                $dailyTickets[$dateKey] = $this->calculateDailyTickets($date);
            }
            // Tạo dữ liệu rỗng cho các chart khác
            for ($m = 1; $m <= 12; $m++) {
                $monthlyRevenue[$m] = 0;
                $monthlyTickets[$m] = 0;
            }
            for ($y = date('Y') - 4; $y <= date('Y'); $y++) {
                $yearlyRevenue[$y] = 0;
                $yearlyTickets[$y] = 0;
            }
        } elseif ($reportType === 'month') {
            // Theo Tháng - Hiển thị 12 tháng của năm được chọn
            for ($m = 1; $m <= 12; $m++) {
                $monthlyRevenue[$m] = $this->calculateMonthlyRevenue($year, $m);
                $monthlyTickets[$m] = $this->calculateMonthlyTickets($year, $m);
            }
            // Tạo dữ liệu rỗng cho các chart khác
            for ($i = 1; $i <= 31; $i++) {
                $dailyRevenue["$i"] = 0;
                $dailyTickets["$i"] = 0;
            }
            for ($y = date('Y') - 4; $y <= date('Y'); $y++) {
                $yearlyRevenue[$y] = 0;
                $yearlyTickets[$y] = 0;
            }
        } else {
            // Theo Năm - Hiển thị 5 năm gần nhất
            for ($y = date('Y') - 4; $y <= date('Y'); $y++) {
                $yearlyRevenue[$y] = $this->calculateYearlyRevenue($y);
                $yearlyTickets[$y] = $this->calculateYearlyTickets($y);
            }
            // Tạo dữ liệu rỗng cho các chart khác
            for ($i = 1; $i <= 31; $i++) {
                $dailyRevenue["$i"] = 0;
                $dailyTickets["$i"] = 0;
            }
            for ($m = 1; $m <= 12; $m++) {
                $monthlyRevenue[$m] = 0;
                $monthlyTickets[$m] = 0;
            }
        }

        // Top 10 chuyến xe có doanh thu cao nhất (theo năm và tháng hiện tại)
        $topTrips = $this->getTopTrips(date('Y'), date('m'), 10);

        // Doanh thu theo nhà xe (theo năm và tháng hiện tại)
        $revenueByCompany = $this->getRevenueByCompany(date('Y'), date('m'));

        return view('AdminLTE.admin.doanh_thu.index', compact(
            'stats',
            'dailyRevenue',
            'dailyTickets',
            'monthlyRevenue',
            'monthlyTickets',
            'yearlyRevenue',
            'yearlyTickets',
            'topTrips',
            'revenueByCompany',
            'year',
            'month',
            'reportType'
        ));
    }

    /**
     * Revenue by trip
     */
    public function byTrip(Request $request)
    {
        $query = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen']);

        // Tính doanh thu cho mỗi chuyến xe
        $trips = $query->get()->map(function ($trip) use ($request) {
            $bookingsQuery = $trip->datVe()->where('trang_thai', '!=', 'Đã hủy');

            // Lọc theo ngày nếu có
            if ($request->filled('from_date')) {
                $bookingsQuery->whereDate('ngay_dat', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $bookingsQuery->whereDate('ngay_dat', '<=', $request->to_date);
            }

            $bookings = $bookingsQuery->get();
            $revenue = $this->calculateRevenueFromBookings($bookings);
            $ticketsSold = $bookings->count();

            return [
                'trip' => $trip,
                'revenue' => $revenue,
                'tickets_sold' => $ticketsSold,
            ];
        })->sortByDesc('revenue');

        return view('AdminLTE.admin.doanh_thu.by_trip', compact('trips'));
    }

    /**
     * Revenue by company
     */
    public function byCompany(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $companies = NhaXe::all()->map(function ($company) use ($year, $month) {
            $revenue = $this->getCompanyRevenue($company->ma_nha_xe, $year, $month);
            $ticketsSold = $this->getCompanyTicketsSold($company->ma_nha_xe, $year, $month);

            return [
                'company' => $company,
                'revenue' => $revenue,
                'tickets_sold' => $ticketsSold,
            ];
        })->sortByDesc('revenue');

        return view('AdminLTE.admin.doanh_thu.by_company', compact('companies', 'year', 'month'));
    }

    /**
     * Export revenue report
     */
    public function export(Request $request)
    {
        // TODO: Implement Excel/PDF export
        return redirect()->back()->with('info', 'Chức năng xuất báo cáo đang được phát triển!');
    }

    /**
     * Calculate daily revenue
     */
    private function calculateDailyRevenue(Carbon $date)
    {
        $bookings = DatVe::with('chuyenXe')
            ->whereDate('ngay_dat', $date)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();

        return $this->calculateRevenueFromBookings($bookings);
    }

    /**
     * Calculate monthly revenue
     */
    private function calculateMonthlyRevenue($year, $month)
    {
        $bookings = DatVe::with('chuyenXe')
            ->whereYear('ngay_dat', $year)
            ->whereMonth('ngay_dat', $month)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();

        return $this->calculateRevenueFromBookings($bookings);
    }

    /**
     * Calculate yearly revenue
     */
    private function calculateYearlyRevenue($year)
    {
        $bookings = DatVe::with('chuyenXe')
            ->whereYear('ngay_dat', $year)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();

        return $this->calculateRevenueFromBookings($bookings);
    }

    /**
     * Calculate revenue from bookings collection
     */
    private function calculateRevenueFromBookings($bookings)
    {
        $total = 0;
        foreach ($bookings as $booking) {
            if ($booking->chuyenXe) {
                $seats = explode(',', $booking->so_ghe);
                $seatCount = count(array_filter(array_map('trim', $seats)));
                $price = floatval(preg_replace('/[^0-9\.]/', '', $booking->chuyenXe->gia_ve));
                $total += $price * $seatCount;
            }
        }
        return $total;
    }

    /**
     * Calculate daily tickets sold
     */
    private function calculateDailyTickets(Carbon $date)
    {
        return DatVe::whereDate('ngay_dat', $date)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->count();
    }

    /**
     * Calculate monthly tickets sold
     */
    private function calculateMonthlyTickets($year, $month)
    {
        return DatVe::whereYear('ngay_dat', $year)
            ->whereMonth('ngay_dat', $month)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->count();
    }

    /**
     * Calculate yearly tickets sold
     */
    private function calculateYearlyTickets($year)
    {
        return DatVe::whereYear('ngay_dat', $year)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->count();
    }

    /**
     * Get top trips by revenue
     */
    private function getTopTrips($year, $month, $limit = 10)
    {
        $trips = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
            ->whereYear('ngay_di', $year)
            ->whereMonth('ngay_di', $month)
            ->get();

        return $trips->map(function ($trip) {
            $bookings = $trip->datVe()->where('trang_thai', '!=', 'Đã hủy')->get();
            $revenue = $this->calculateRevenueFromBookings($bookings);

            return [
                'trip' => $trip,
                'revenue' => $revenue,
                'tickets_sold' => $bookings->count(),
            ];
        })->sortByDesc('revenue')->take($limit);
    }

    /**
     * Get revenue by company
     */
    private function getRevenueByCompany($year, $month)
    {
        return NhaXe::all()->map(function ($company) use ($year, $month) {
            return [
                'company' => $company,
                'revenue' => $this->getCompanyRevenue($company->ma_nha_xe, $year, $month),
            ];
        })->sortByDesc('revenue');
    }

    /**
     * Get company revenue
     */
    private function getCompanyRevenue($companyId, $year, $month)
    {
        $bookings = DatVe::with('chuyenXe')
            ->whereHas('chuyenXe', function ($q) use ($companyId) {
                $q->where('ma_nha_xe', $companyId);
            })
            ->whereYear('ngay_dat', $year)
            ->whereMonth('ngay_dat', $month)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();

        return $this->calculateRevenueFromBookings($bookings);
    }

    /**
     * Get company tickets sold
     */
    private function getCompanyTicketsSold($companyId, $year, $month)
    {
        return DatVe::whereHas('chuyenXe', function ($q) use ($companyId) {
            $q->where('ma_nha_xe', $companyId);
        })
            ->whereYear('ngay_dat', $year)
            ->whereMonth('ngay_dat', $month)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->count();
    }
}
