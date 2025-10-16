<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\ChuyenXe;
use App\Models\DatVe;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get the authenticated user's bus company
            $user = Auth::user();
            $bus_company = $user->nhaXe;

            // If user doesn't have a bus company assigned, show error message
            if (!$bus_company) {
                return view('AdminLTE.bus_owner.dashboard', [
                    'stats' => [
                        'total_trips' => 0,
                        'today_trips' => 0,
                        'total_bookings' => 0,
                        'today_bookings' => 0,
                        'monthly_revenue' => 0,
                        'pending_bookings' => 0,
                        'weekly_revenue' => 0,
                        'total_customers' => 0,
                        'confirmed_bookings' => 0,
                        'cancelled_bookings' => 0,
                        'average_booking_value' => 0,
                        'occupancy_rate' => 0,
                        'growth_rate' => 0,
                    ],
                    'recent_bookings' => collect(),
                    'today_trips' => collect(),
                    'monthly_revenue' => collect(),
                    'trip_performance' => collect(),
                    'bus_company' => null,
                    'weekly_trend' => collect(),
                    'upcoming_trips' => collect(),
                    'top_routes' => collect(),
                    'customer_insights' => collect(),
                ])->with('warning', 'Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin để được hỗ trợ.');
            }

            // Get current month and year for calculations
            $currentMonth = date('m');
            $currentYear = date('Y');
            $today = date('Y-m-d');
            $weekAgo = date('Y-m-d', strtotime('-7 days'));

            // Bus owner specific statistics with optimized queries
            $totalTrips = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->count();
            $todayTrips = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)
                ->where('ngay_di', $today)->count();

            // Booking statistics with optimized queries
            $totalBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->count();

            $todayBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->whereDate('ngay_dat', $today)->count();

            // Revenue calculations
            $monthlyRevenue = DatVe::with('chuyenXe')
                ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
                })
                ->whereMonth('ngay_dat', $currentMonth)
                ->whereYear('ngay_dat', $currentYear)
                ->get()
                ->sum(function ($booking) {
                    return ($booking->chuyenXe && $booking->chuyenXe->gia_ve) ? $booking->chuyenXe->gia_ve : 0;
                });

            $weeklyRevenue = DatVe::with('chuyenXe')
                ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
                })
                ->whereBetween('ngay_dat', [$weekAgo, $today])
                ->get()
                ->sum(function ($booking) {
                    return ($booking->chuyenXe && $booking->chuyenXe->gia_ve) ? $booking->chuyenXe->gia_ve : 0;
                });

            // Status-based booking counts
            $pendingBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->where('trang_thai', 'Đã đặt')->count();

            $confirmedBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->where('trang_thai', 'Đã xác nhận')->count();

            $cancelledBookings = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->where('trang_thai', 'Đã hủy')->count();

            // Customer statistics
            $totalCustomers = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->distinct('user_id')->count('user_id');

            // Calculate additional metrics
            $averageBookingValue = $totalBookings > 0 ? $monthlyRevenue / $totalBookings : 0;

            // Calculate occupancy rate (simplified version)
            $totalSeats = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->sum('so_cho');
            $bookedSeats = DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
            })->where('trang_thai', '!=', 'Đã hủy')->sum('so_luong_ve');
            $occupancyRate = $totalSeats > 0 ? round(($bookedSeats / $totalSeats) * 100, 1) : 0;

            // Calculate growth rate (compare this month with last month)
            $lastMonthRevenue = DatVe::with('chuyenXe')
                ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
                })
                ->whereMonth('ngay_dat', date('m', strtotime('-1 month')))
                ->whereYear('ngay_dat', $currentYear)
                ->get()
                ->sum(function ($booking) {
                    return ($booking->chuyenXe && $booking->chuyenXe->gia_ve) ? $booking->chuyenXe->gia_ve : 0;
                });

            $growthRate = $lastMonthRevenue > 0 ?
                round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

            $stats = [
                'total_trips' => $totalTrips,
                'today_trips' => $todayTrips,
                'total_bookings' => $totalBookings,
                'today_bookings' => $todayBookings,
                'monthly_revenue' => $monthlyRevenue,
                'pending_bookings' => $pendingBookings,
                'weekly_revenue' => $weeklyRevenue,
                'total_customers' => $totalCustomers,
                'confirmed_bookings' => $confirmedBookings,
                'cancelled_bookings' => $cancelledBookings,
                'average_booking_value' => $averageBookingValue,
                'occupancy_rate' => $occupancyRate,
                'growth_rate' => $growthRate,
            ];

            // Recent bookings for this bus company (with enhanced data)
            $recent_bookings = DatVe::with(['user', 'chuyenXe'])
                ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                    $q->where('ma_nha_xe', $bus_company->ma_nha_xe);
                })
                ->select('dat_ve.*')
                ->orderBy('ngay_dat', 'desc')
                ->limit(10)
                ->get();

            // Today's trips for this bus company (with enhanced data)
            $today_trips = ChuyenXe::with(['tramDi', 'tramDen'])
                ->where('ma_nha_xe', $bus_company->ma_nha_xe)
                ->where('ngay_di', $today)
                ->select('chuyen_xe.*')
                ->orderBy('gio_di')
                ->get();

            // Monthly revenue chart data
            $monthly_revenue_data = DatVe::select(
                DB::raw('MONTH(ngay_dat) as month'),
                DB::raw('SUM(chuyen_xe.gia_ve) as revenue'),
                DB::raw('COUNT(*) as bookings_count')
            )
                ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
                ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
                ->whereYear('ngay_dat', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            // Trip performance with enhanced metrics
            $trip_performance = ChuyenXe::select('chuyen_xe.id', 'chuyen_xe.ten_xe', 'chuyen_xe.so_cho')
                ->withCount(['datVe as bookings_count' => function ($q) {
                    $q->where('trang_thai', '!=', 'Đã hủy');
                }])
                ->with(['datVe' => function ($q) {
                    $q->where('trang_thai', '!=', 'Đã hủy')
                        ->selectRaw('chuyen_xe_id, SUM(so_luong_ve) as total_seats_booked')
                        ->groupBy('chuyen_xe_id');
                }])
                ->where('ma_nha_xe', $bus_company->ma_nha_xe)
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($trip) {
                    $totalSeatsBooked = $trip->dat_ve ? $trip->dat_ve->sum('total_seats_booked') : 0;
                    $trip->occupancy_rate = $trip->so_cho > 0 ?
                        round(($totalSeatsBooked / $trip->so_cho) * 100, 1) : 0;
                    return $trip;
                });

            // Weekly revenue trend (last 7 days) with enhanced data
            $weekly_trend_raw = DatVe::select(
                DB::raw('DATE(ngay_dat) as date'),
                DB::raw('SUM(chuyen_xe.gia_ve) as revenue'),
                DB::raw('COUNT(*) as bookings'),
                DB::raw('COUNT(DISTINCT user_id) as unique_customers')
            )
                ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
                ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
                ->whereBetween('ngay_dat', [$weekAgo, $today])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Fill missing dates with zero values
            $weekly_data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-{$i} days"));
                $day_data = $weekly_trend_raw->where('date', $date)->first();
                $weekly_data[] = [
                    'date' => $date,
                    'revenue' => $day_data ? $day_data->revenue : 0,
                    'bookings' => $day_data ? $day_data->bookings : 0,
                    'unique_customers' => $day_data ? $day_data->unique_customers : 0,
                ];
            }

            // Upcoming trips (next 7 days) with enhanced data
            $upcoming_trips = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)
                ->whereBetween('ngay_di', [$today, date('Y-m-d', strtotime('+7 days'))])
                ->withCount(['datVe' => function ($q) {
                    $q->where('trang_thai', '!=', 'Đã hủy');
                }])
                ->select('chuyen_xe.*')
                ->orderBy('ngay_di')
                ->orderBy('gio_di')
                ->limit(10)
                ->get()
                ->map(function ($trip) {
                    $trip->available_seats = $trip->so_cho - $trip->dat_ve_count;
                    $trip->occupancy_rate = $trip->so_cho > 0 ?
                        round(($trip->dat_ve_count / $trip->so_cho) * 100, 1) : 0;
                    return $trip;
                });

            // Top performing routes
            $top_routes = ChuyenXe::select('chuyen_xe.ten_xe', 'chuyen_xe.so_cho')
                ->join('dat_ve', 'chuyen_xe.id', '=', 'dat_ve.chuyen_xe_id')
                ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
                ->where('dat_ve.trang_thai', '!=', 'Đã hủy')
                ->groupBy('chuyen_xe.id', 'chuyen_xe.ten_xe', 'chuyen_xe.so_cho')
                ->selectRaw('COUNT(*) as bookings_count, SUM(chuyen_xe.gia_ve) as total_revenue')
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get();

            // Customer insights
            $customer_insights = DatVe::select('user_id')
                ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
                ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
                ->where('dat_ve.trang_thai', '!=', 'Đã hủy')
                ->groupBy('user_id')
                ->selectRaw('COUNT(*) as bookings_count, SUM(chuyen_xe.gia_ve) as total_spent')
                ->orderBy('total_spent', 'desc')
                ->limit(5)
                ->get();

            return view('AdminLTE.bus_owner.dashboard', compact(
                'stats',
                'recent_bookings',
                'today_trips',
                'monthly_revenue_data',
                'trip_performance',
                'bus_company',
                'weekly_data',
                'upcoming_trips',
                'top_routes',
                'customer_insights'
            ));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Dashboard Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return view with error message but still show basic structure
            return view('AdminLTE.bus_owner.dashboard', [
                'stats' => [
                    'total_trips' => 0,
                    'today_trips' => 0,
                    'total_bookings' => 0,
                    'today_bookings' => 0,
                    'monthly_revenue' => 0,
                    'pending_bookings' => 0,
                    'weekly_revenue' => 0,
                    'total_customers' => 0,
                    'confirmed_bookings' => 0,
                    'cancelled_bookings' => 0,
                    'average_booking_value' => 0,
                    'occupancy_rate' => 0,
                    'growth_rate' => 0,
                ],
                'recent_bookings' => collect(),
                'today_trips' => collect(),
                'monthly_revenue' => collect(),
                'monthly_revenue_data' => collect(), // Added missing variable
                'trip_performance' => collect(),
                'bus_company' => null,
                'weekly_trend' => collect(),
                'weekly_data' => [],
                'upcoming_trips' => collect(),
                'top_routes' => collect(),
                'customer_insights' => collect(),
                'error' => 'Có lỗi xảy ra khi tải dữ liệu dashboard. Vui lòng thử lại sau.',
            ]);
        }
    }

    public function revenue()
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        // If user doesn't have a bus company assigned, show error message
        if (!$bus_company) {
            return redirect()->route('bus-owner.dashboard')->with('warning', 'Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin để được hỗ trợ.');
        }

        // Revenue statistics for current year
        $current_year = date('Y');
        $yearly_revenue = DatVe::select(
            DB::raw('MONTH(ngay_dat) as month'),
            DB::raw('SUM(chuyen_xe.gia_ve) as revenue'),
            DB::raw('COUNT(*) as bookings_count')
        )
            ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
            ->whereYear('ngay_dat', $current_year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fill in missing months with zero values
        $monthly_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $month_data = $yearly_revenue->where('month', $i)->first();
            $monthly_data[] = [
                'month' => $i,
                'revenue' => $month_data->revenue ?? 0,
                'bookings' => $month_data->bookings_count ?? 0
            ];
        }

        // Top performing trips by revenue
        $top_trips = ChuyenXe::select('chuyen_xe.*')
            ->join('dat_ve', 'chuyen_xe.id', '=', 'dat_ve.chuyen_xe_id')
            ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
            ->whereYear('dat_ve.ngay_dat', $current_year)
            ->groupBy('chuyen_xe.id')
            ->selectRaw('SUM(chuyen_xe.gia_ve) as total_revenue, COUNT(*) as bookings_count')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Daily revenue for current month
        $current_month = date('m');
        $daily_revenue = DatVe::select(
            DB::raw('DAY(ngay_dat) as day'),
            DB::raw('SUM(chuyen_xe.gia_ve) as revenue'),
            DB::raw('COUNT(*) as bookings_count')
        )
            ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
            ->whereMonth('ngay_dat', $current_month)
            ->whereYear('ngay_dat', $current_year)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Overall statistics
        $total_revenue = DatVe::join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
            ->whereYear('ngay_dat', $current_year)
            ->sum('chuyen_xe.gia_ve');

        $total_bookings = DatVe::join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->where('chuyen_xe.ma_nha_xe', $bus_company->ma_nha_xe)
            ->whereYear('ngay_dat', $current_year)
            ->count();

        $average_booking_value = $total_bookings > 0 ? $total_revenue / $total_bookings : 0;

        return view('AdminLTE.bus_owner.revenue', compact(
            'bus_company',
            'monthly_data',
            'top_trips',
            'daily_revenue',
            'total_revenue',
            'total_bookings',
            'average_booking_value',
            'current_year'
        ));
    }
}
