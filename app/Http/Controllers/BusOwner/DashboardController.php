<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\ChuyenXe;
use App\Models\DatVe;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        // Get the bus company owned by this user
        $bus_company = NhaXe::where('user_id', Auth::id())->first();

        if (!$bus_company) {
            return redirect()->route('bus-owner.profile')->with('error', 'Bạn cần tạo thông tin nhà xe trước khi truy cập dashboard.');
        }

        // Bus owner specific statistics
        $stats = [
            'total_trips' => ChuyenXe::where('nha_xe_id', $bus_company->id)->count(),
            'today_trips' => ChuyenXe::where('nha_xe_id', $bus_company->id)
                ->where('ngay_di', date('Y-m-d'))
                ->count(),
            'total_bookings' => DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('nha_xe_id', $bus_company->id);
            })->count(),
            'today_bookings' => DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('nha_xe_id', $bus_company->id);
            })->whereDate('ngay_dat', date('Y-m-d'))->count(),
            'monthly_revenue' => DatVe::with('chuyenXe')
                ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                    $q->where('nha_xe_id', $bus_company->id);
                })
                ->whereMonth('ngay_dat', date('m'))
                ->whereYear('ngay_dat', date('Y'))
                ->get()
                ->sum(function ($booking) {
                    return $booking->chuyenXe->gia_ve ?? 0;
                }),
            'pending_bookings' => DatVe::whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('nha_xe_id', $bus_company->id);
            })->whereStatus('pending')->count(),
        ];

        // Recent bookings for this bus company
        $recent_bookings = DatVe::with(['user', 'chuyenXe'])
            ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('nha_xe_id', $bus_company->id);
            })
            ->latest()
            ->limit(10)
            ->get();

        // Today's trips for this bus company
        $today_trips = ChuyenXe::with(['tramDi', 'tramDen'])
            ->where('nha_xe_id', $bus_company->id)
            ->where('ngay_di', date('Y-m-d'))
            ->orderBy('gio_di')
            ->get();

        // Monthly revenue chart
        $monthly_revenue = DatVe::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as revenue')
        )
            ->whereHas('chuyenXe', function ($q) use ($bus_company) {
                $q->where('nha_xe_id', $bus_company->id);
            })
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        // Trip performance
        $trip_performance = ChuyenXe::select('id', 'route_name')
            ->withCount([
                'datVes as bookings_count' => function ($q) {
                    $q->whereStatus('!=', 'cancelled');
                }
            ])
            ->where('nha_xe_id', $bus_company->id)
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        return view('AdminLTE.bus_owner.dashboard', compact(
            'stats',
            'recent_bookings',
            'today_trips',
            'monthly_revenue',
            'trip_performance',
            'bus_company'
        ));
    }
}
