<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use App\Models\NhaXe;
use App\Models\TinTuc;
use App\Models\Contact;
use App\Models\NhanVien;
use App\Models\BinhLuan;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Count seats from string like "A01, A02,A03"
     */
    private function countSeats($seatString)
    {
        if (empty($seatString)) {
            return 0;
        }
        // Split by comma and count non-empty items
        $seatArray = array_filter(array_map('trim', explode(',', (string) $seatString)));
        return count($seatArray);
    }

    /**
     * Get comments count safely - check if table exists first
     */
    private function getCommentsCount()
    {
        try {
            // Check if binh_luan table exists
            if (Schema::hasTable('binh_luan')) {
                return BinhLuan::count();
            }
            return 0;
        } catch (\Exception $e) {
            // If any error occurs, return 0
            return 0;
        }
    }

    public function index()
    {
        // Statistics for dashboard
        $stats = [
            'total_users' => User::where('role', '!=', 'Admin')->count(),
            'total_bookings' => DatVe::count(),
            'total_trips' => ChuyenXe::count(),
            'total_bus_companies' => NhaXe::count(),
            'total_news' => TinTuc::count(),
            'total_contacts' => Contact::count(),
            'total_employees' => NhanVien::count(), // Tổng nhân viên
            'total_comments' => $this->getCommentsCount(), // Tổng bình luận
            'total_promotions' => KhuyenMai::count(), // Tổng khuyến mãi
            'active_promotions' => KhuyenMai::where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->count(), // Khuyến mãi đang áp dụng (trong khoảng thời gian hiệu lực)
            // Calculate revenue based on booked seats * trip price when relation exists
            'monthly_revenue' => DatVe::with('chuyenXe')
                ->whereMonth('ngay_dat', date('m'))
                ->whereYear('ngay_dat', date('Y'))
                ->get()
                ->sum(function ($booking) {
                    // Ensure price and seats are numeric before multiplying to avoid PHP 8 TypeError
                    $rawPrice = optional($booking->chuyenXe)->gia_ve ?? 0;
                    // remove any non-numeric except dot
                    $cleanPrice = preg_replace('/[^0-9\.]/', '', (string) $rawPrice);
                    $price = $cleanPrice === '' ? 0.0 : (float) $cleanPrice;

                    // so_ghe is string like "A01, A02,A03" - count seats by splitting
                    $seats = $this->countSeats($booking->so_ghe);

                    return $price * $seats;
                }),
            'today_bookings' => DatVe::whereDate('ngay_dat', date('Y-m-d'))->count(),
        ];

        // Recent bookings
        // Recent bookings: eager load related user, trip, trip->tramDi and tramDen for route
        $recent_bookings = DatVe::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe'])
            ->orderBy('ngay_dat', 'desc')
            ->limit(10)
            ->get();

        // Monthly booking chart data
        // bookings count per month for current year (indexed by month number)
        $monthly_bookings = DatVe::select(
            DB::raw('MONTH(ngay_dat) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('ngay_dat', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Bus company statistics
        // Top bus companies by number of trips
        $bus_company_stats = NhaXe::withCount('chuyenXe')
            ->orderBy('chuyen_xe_count', 'desc')
            ->limit(5)
            ->get();

        // ---------- Additional analytics for richer dashboard ----------
        // Load recent bookings (30 days) for multiple aggregations
        $bookings_30 = DatVe::with('chuyenXe.tramDi', 'chuyenXe.tramDen', 'user')
            ->whereDate('ngay_dat', '>=', Carbon::now()->subDays(29)->toDateString())
            ->get();

        // Daily revenue for last 7 days
        $daily_revenue_7 = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->toDateString();
            $sum = $bookings_30->where('ngay_dat', '>=', $d . ' 00:00:00')
                ->where('ngay_dat', '<=', $d . ' 23:59:59')
                ->sum(function ($b) {
                    $rawPrice = optional($b->chuyenXe)->gia_ve ?? 0;
                    $cleanPrice = preg_replace('/[^0-9\.\-]/', '', (string) $rawPrice);
                    $price = $cleanPrice === '' ? 0.0 : (float) $cleanPrice;
                    $seats = $this->countSeats($b->so_ghe);
                    return $price * $seats;
                });
            $daily_revenue_7[$d] = $sum;
        }

        // Daily revenue for last 30 days
        $daily_revenue_30 = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->toDateString();
            $sum = $bookings_30->where('ngay_dat', '>=', $d . ' 00:00:00')
                ->where('ngay_dat', '<=', $d . ' 23:59:59')
                ->sum(function ($b) {
                    $rawPrice = optional($b->chuyenXe)->gia_ve ?? 0;
                    $cleanPrice = preg_replace('/[^0-9\.\-]/', '', (string) $rawPrice);
                    $price = $cleanPrice === '' ? 0.0 : (float) $cleanPrice;
                    $seats = $this->countSeats($b->so_ghe);
                    return $price * $seats;
                });
            $daily_revenue_30[$d] = $sum;
        }

        // Monthly revenue for last 12 months
        $monthly_revenue_12 = [];
        for ($m = 11; $m >= 0; $m--) {
            $month = Carbon::now()->subMonths($m);
            $monthKey = $month->format('Y-m');
            $sum = DatVe::with('chuyenXe')
                ->whereYear('ngay_dat', $month->year)
                ->whereMonth('ngay_dat', $month->month)
                ->get()
                ->sum(function ($b) {
                    $rawPrice = optional($b->chuyenXe)->gia_ve ?? 0;
                    $cleanPrice = preg_replace('/[^0-9\.\-]/', '', (string) $rawPrice);
                    $price = $cleanPrice === '' ? 0.0 : (float) $cleanPrice;
                    $seats = $this->countSeats($b->so_ghe);
                    return $price * $seats;
                });
            $monthly_revenue_12[$monthKey] = $sum;
        }

        // Revenue grouped by year
        $yearly_revenue = DatVe::with('chuyenXe')
            ->get()
            ->groupBy(function ($b) {
                return Carbon::parse($b->ngay_dat)->format('Y');
            })
            ->map(function ($group) {
                return $group->sum(function ($b) {
                    $rawPrice = optional($b->chuyenXe)->gia_ve ?? 0;
                    $cleanPrice = preg_replace('/[^0-9\.\-]/', '', (string) $rawPrice);
                    $price = $cleanPrice === '' ? 0.0 : (float) $cleanPrice;
                    $seats = $this->countSeats($b->so_ghe);
                    return $price * $seats;
                });
            })->toArray();

        // Top routes by number of bookings (from last 30 days)
        $top_routes = $bookings_30->map(function ($b) {
            $cx = $b->chuyenXe;
            $from = optional($cx->tramDi)->ten_tram ?? optional($cx->tramDi)->dia_chi ?? '';
            $to = optional($cx->tramDen)->ten_tram ?? optional($cx->tramDen)->dia_chi ?? '';
            return trim(($from ?: 'N/A') . ' → ' . ($to ?: 'N/A'));
        })->filter()->countBy()->sortDesc()->take(5);

        // Recent users and top customers
        $recent_users = User::orderBy('created_at', 'desc')->limit(10)->get();
        $top_customers = DatVe::select('user_id', DB::raw('COUNT(*) as tickets'))
            ->groupBy('user_id')
            ->orderBy('tickets', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($r) {
                $user = User::find($r->user_id);
                return [
                    'user' => $user,
                    'tickets' => $r->tickets
                ];
            });

        // Recent trips
        $recent_trips = ChuyenXe::with('nhaXe', 'tramDi', 'tramDen')
            ->orderBy('ngay_di', 'desc')
            ->limit(10)
            ->get();

        return view('AdminLTE.admin.dashboard', compact(
            'stats',
            'recent_bookings',
            'monthly_bookings',
            'daily_revenue_7',
            'daily_revenue_30',
            'monthly_revenue_12',
            'yearly_revenue',
            'top_routes',
            'recent_users',
            'top_customers',
            'recent_trips',
            'bus_company_stats'
        ));
    }
}
