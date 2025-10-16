<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        // Staff-specific statistics
        $stats = [
            'today_bookings' => DatVe::whereDate('ngay_dat', date('Y-m-d'))->count(),
            'pending_bookings' => DatVe::whereStatus('pending')->count(),
            'confirmed_bookings' => DatVe::whereStatus('confirmed')->count(),
            'cancelled_bookings' => DatVe::whereStatus('cancelled')->count(),
            'today_trips' => ChuyenXe::where('ngay_di', date('Y-m-d'))->count(),
            'total_customers' => User::where('role', 'User')->count(),
            'monthly_bookings' => DatVe::whereMonth('ngay_dat', date('m'))
                ->whereYear('ngay_dat', date('Y'))
                ->count(),
        ];

        // Recent bookings for staff to manage
        $recent_bookings = DatVe::with(['user', 'chuyenXe'])
            ->latest()
            ->limit(15)
            ->get();

        // Today's trips
        $today_trips = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
            ->where('ngay_di', date('Y-m-d'))
            ->orderBy('gio_di')
            ->get();

        // Pending bookings that need attention
        $pending_bookings = DatVe::with(['user', 'chuyenXe'])
            ->whereStatus('pending')
            ->latest()
            ->limit(10)
            ->get();

        // Monthly booking trend
        $monthly_trend = DatVe::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return view('AdminLTE.staff.dashboard', compact(
            'stats',
            'recent_bookings',
            'today_trips',
            'pending_bookings',
            'monthly_trend'
        ));
    }
}
