<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use App\Models\User;
use App\Models\NhaXe;
use App\Models\BinhLuan;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display general report
     */
    public function index()
    {
        // Tổng quan hệ thống
        $overview = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_bookings' => DatVe::count(),
            'total_trips' => ChuyenXe::count(),
            'total_operators' => NhaXe::count(),
            'total_revenue' => $this->calculateTotalRevenue(),
            'total_comments' => BinhLuan::count(),
            'total_contacts' => Contact::count(),
        ];

        // Báo cáo theo tháng
        $currentMonth = date('m');
        $currentYear = date('Y');

        $monthlyStats = [
            'bookings' => DatVe::whereMonth('ngay_dat', $currentMonth)
                ->whereYear('ngay_dat', $currentYear)
                ->count(),
            'revenue' => $this->calculateMonthlyRevenue($currentYear, $currentMonth),
            'new_users' => User::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'comments' => BinhLuan::whereMonth('ngay_bl', $currentMonth)
                ->whereYear('ngay_bl', $currentYear)
                ->count(),
        ];

        // Top 5 users có nhiều vé nhất
        $topUsers = User::select('users.id', 'users.username', 'users.email', 'users.fullname', DB::raw('COUNT(dat_ve.id) as booking_count'))
            ->join('dat_ve', 'users.id', '=', 'dat_ve.user_id')
            ->groupBy('users.id', 'users.username', 'users.email', 'users.fullname')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        // Top 5 chuyến xe phổ biến
        $topTrips = ChuyenXe::select('chuyen_xe.id', 'chuyen_xe.ma_nha_xe', 'chuyen_xe.ma_tram_di', 'chuyen_xe.ma_tram_den', 'chuyen_xe.ngay_di', 'chuyen_xe.gio_di', DB::raw('COUNT(dat_ve.id) as booking_count'))
            ->with(['nhaXe', 'tramDi', 'tramDen'])
            ->join('dat_ve', 'chuyen_xe.id', '=', 'dat_ve.chuyen_xe_id')
            ->groupBy('chuyen_xe.id', 'chuyen_xe.ma_nha_xe', 'chuyen_xe.ma_tram_di', 'chuyen_xe.ma_tram_den', 'chuyen_xe.ngay_di', 'chuyen_xe.gio_di')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        return view('AdminLTE.admin.report.index', compact('overview', 'monthlyStats', 'topUsers', 'topTrips'));
    }

    /**
     * Booking report
     */
    public function bookings(Request $request)
    {
        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $bookings = DatVe::with(['user', 'chuyenXe'])
            ->whereBetween('ngay_dat', [$fromDate, $toDate])
            ->get();

        $stats = [
            'total' => $bookings->count(),
            'confirmed' => $bookings->where('trang_thai', 'Đã thanh toán')->count(),
            'pending' => $bookings->where('trang_thai', 'Đã đặt')->count(),
            'cancelled' => $bookings->where('trang_thai', 'Đã hủy')->count(),
            'revenue' => $this->calculateRevenueFromBookings($bookings->where('trang_thai', '!=', 'Đã hủy')),
        ];

        // Biểu đồ theo ngày
        $dailyBookings = [];
        $period = \Carbon\CarbonPeriod::create($fromDate, $toDate);
        foreach ($period as $date) {
            $dailyBookings[$date->format('Y-m-d')] = $bookings
                ->whereBetween('ngay_dat', [$date->format('Y-m-d 00:00:00'), $date->format('Y-m-d 23:59:59')])
                ->count();
        }

        return view('AdminLTE.admin.report.bookings', compact('stats', 'dailyBookings', 'fromDate', 'toDate'));
    }

    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        // Doanh thu theo tháng trong năm
        $monthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[$m] = $this->calculateMonthlyRevenue($year, $m);
        }

        // Doanh thu theo nhà xe
        $companyRevenue = NhaXe::all()->map(function ($company) use ($year, $month) {
            return [
                'company' => $company,
                'revenue' => $this->getCompanyRevenue($company->ma_nha_xe, $year, $month),
            ];
        })->sortByDesc('revenue');

        $totalRevenue = $this->calculateYearlyRevenue($year);

        return view('AdminLTE.admin.report.revenue', compact('monthlyRevenue', 'companyRevenue', 'totalRevenue', 'year'));
    }

    /**
     * User report
     */
    public function users(Request $request)
    {
        // Thống kê users theo role
        $usersByRole = [
            'user' => User::where('role', 'user')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'bus_owner' => User::where('role', 'bus_owner')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        // Users mới theo tháng
        $newUsersByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $newUsersByMonth[$m] = User::whereMonth('created_at', $m)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        // Top users có nhiều booking nhất
        $topActiveUsers = User::select('users.id', 'users.fullname', 'users.email', 'users.phone', DB::raw('COUNT(dat_ve.id) as booking_count'))
            ->join('dat_ve', 'users.id', '=', 'dat_ve.user_id')
            ->groupBy('users.id', 'users.fullname', 'users.email', 'users.phone')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();

        return view('AdminLTE.admin.report.users', compact('usersByRole', 'newUsersByMonth', 'topActiveUsers'));
    }

    /**
     * Comments report
     */
    public function comments(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Thống kê bình luận
        $stats = [
            'total' => BinhLuan::count(),
            'this_month' => BinhLuan::whereMonth('ngay_bl', date('m'))
                ->whereYear('ngay_bl', date('Y'))
                ->count(),
            'approved' => BinhLuan::where('trang_thai', 'Đã duyệt')->count(),
            'pending' => BinhLuan::where('trang_thai', 'Chờ duyệt')->count(),
        ];

        // Bình luận theo tháng
        $commentsByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $commentsByMonth[$m] = BinhLuan::whereMonth('ngay_bl', $m)
                ->whereYear('ngay_bl', $year)
                ->count();
        }

        // Top chuyến xe có nhiều bình luận nhất
        $topCommentedTrips = ChuyenXe::select('chuyen_xe.id', 'chuyen_xe.ma_nha_xe', 'chuyen_xe.ma_tram_di', 'chuyen_xe.ma_tram_den', DB::raw('COUNT(binh_luan.ma_bl) as comment_count'))
            ->with(['nhaXe', 'tramDi', 'tramDen'])
            ->join('binh_luan', 'chuyen_xe.id', '=', 'binh_luan.chuyen_xe_id')
            ->groupBy('chuyen_xe.id', 'chuyen_xe.ma_nha_xe', 'chuyen_xe.ma_tram_di', 'chuyen_xe.ma_tram_den')
            ->orderBy('comment_count', 'desc')
            ->limit(10)
            ->get();

        // Phân bố đánh giá sao
        $ratingDistribution = [];
        for ($star = 1; $star <= 5; $star++) {
            $ratingDistribution[$star] = BinhLuan::where('so_sao', $star)->count();
        }

        return view('AdminLTE.admin.report.comments', compact('stats', 'commentsByMonth', 'topCommentedTrips', 'ratingDistribution', 'year'));
    }

    /**
     * Operators (Bus Companies) report
     */
    public function operators(Request $request)
    {
        // Thống kê nhà xe
        $operators = NhaXe::all()->map(function ($operator) {
            $trips = ChuyenXe::where('ma_nha_xe', $operator->ma_nha_xe)->get();

            return [
                'operator' => $operator,
                'total_trips' => $trips->count(),
                'total_bookings' => DatVe::whereIn('chuyen_xe_id', $trips->pluck('id'))->count(),
                'revenue' => $this->getCompanyRevenue($operator->ma_nha_xe, date('Y')),
                'avg_rating' => BinhLuan::whereIn('chuyen_xe_id', $trips->pluck('id'))
                    ->where('trang_thai', 'Đã duyệt')
                    ->avg('so_sao') ?? 0,
                'total_comments' => BinhLuan::whereIn('chuyen_xe_id', $trips->pluck('id'))->count(),
            ];
        })->sortByDesc('revenue');

        $totalRevenue = $operators->sum('revenue');

        return view('AdminLTE.admin.report.operators', compact('operators', 'totalRevenue'));
    }

    /**
     * Routes report
     */
    public function routes(Request $request)
    {
        // Top tuyến đường phổ biến nhất
        $topRoutes = DB::table('chuyen_xe')
            ->select(
                'chuyen_xe.ma_tram_di',
                'chuyen_xe.ma_tram_den',
                DB::raw('COUNT(DISTINCT chuyen_xe.id) as trip_count'),
                DB::raw('COUNT(dat_ve.id) as booking_count')
            )
            ->leftJoin('dat_ve', 'chuyen_xe.id', '=', 'dat_ve.chuyen_xe_id')
            ->groupBy('chuyen_xe.ma_tram_di', 'chuyen_xe.ma_tram_den')
            ->orderBy('booking_count', 'desc')
            ->limit(15)
            ->get();

        // Load tên trạm
        $routes = $topRoutes->map(function ($route) {
            $tramDi = \App\Models\TramXe::find($route->ma_tram_di);
            $tramDen = \App\Models\TramXe::find($route->ma_tram_den);

            return [
                'from' => $tramDi->ten_tram ?? 'N/A',
                'to' => $tramDen->ten_tram ?? 'N/A',
                'trip_count' => $route->trip_count,
                'booking_count' => $route->booking_count,
            ];
        });

        return view('AdminLTE.admin.report.routes', compact('routes'));
    }

    /**
     * Contacts report
     */
    public function contacts(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Thống kê liên hệ
        $stats = [
            'total' => Contact::count(),
            'this_month' => Contact::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
        ];

        // Liên hệ theo tháng
        $contactsByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $contactsByMonth[$m] = Contact::whereMonth('created_at', $m)
                ->whereYear('created_at', $year)
                ->count();
        }

        // Liên hệ theo chi nhánh (nếu có)
        $contactsByBranch = Contact::select('branch', DB::raw('COUNT(*) as count'))
            ->whereNotNull('branch')
            ->groupBy('branch')
            ->get();

        return view('AdminLTE.admin.report.contacts', compact('stats', 'contactsByMonth', 'contactsByBranch', 'year'));
    }

    /**
     * Export report
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'general');
        // TODO: Implement Excel/PDF export
        return redirect()->back()->with('info', 'Chức năng xuất báo cáo ' . $type . ' đang được phát triển!');
    }

    // Helper methods
    private function calculateTotalRevenue()
    {
        $bookings = DatVe::with('chuyenXe')
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();
        return $this->calculateRevenueFromBookings($bookings);
    }

    private function calculateMonthlyRevenue($year, $month)
    {
        $bookings = DatVe::with('chuyenXe')
            ->whereYear('ngay_dat', $year)
            ->whereMonth('ngay_dat', $month)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();
        return $this->calculateRevenueFromBookings($bookings);
    }

    private function calculateYearlyRevenue($year)
    {
        $bookings = DatVe::with('chuyenXe')
            ->whereYear('ngay_dat', $year)
            ->where('trang_thai', '!=', 'Đã hủy')
            ->get();
        return $this->calculateRevenueFromBookings($bookings);
    }

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

    private function getCompanyRevenue($companyId, $year, $month = null)
    {
        $query = DatVe::with('chuyenXe')
            ->whereHas('chuyenXe', function ($q) use ($companyId) {
                $q->where('ma_nha_xe', $companyId);
            })
            ->whereYear('ngay_dat', $year)
            ->where('trang_thai', '!=', 'Đã hủy');

        if ($month) {
            $query->whereMonth('ngay_dat', $month);
        }

        $bookings = $query->get();
        return $this->calculateRevenueFromBookings($bookings);
    }
}
