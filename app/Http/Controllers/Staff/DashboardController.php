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
        $user = auth()->user();
        $maNhaXe = $user->ma_nha_xe;
        
        // Build base query for filtering by bus company
        $bookingsQuery = DatVe::query();
        $tripsQuery = ChuyenXe::query();
        
        if ($maNhaXe) {
            // Filter bookings by bus company
            $bookingsQuery->whereHas('chuyenXe', function($q) use ($maNhaXe) {
                $q->where('ma_nha_xe', $maNhaXe);
            });
            
            // Filter trips by bus company
            $tripsQuery->where('ma_nha_xe', $maNhaXe);
        }
        
        // Staff-specific statistics
        $stats = [
            'today_bookings' => (clone $bookingsQuery)->whereDate('ngay_dat', date('Y-m-d'))->count(),
            'pending_bookings' => (clone $bookingsQuery)->whereStatus('pending')->count(),
            'confirmed_bookings' => (clone $bookingsQuery)->whereStatus('confirmed')->count(),
            'cancelled_bookings' => (clone $bookingsQuery)->whereStatus('cancelled')->count(),
            'today_trips' => (clone $tripsQuery)->where('ngay_di', date('Y-m-d'))->count(),
            'total_customers' => User::where('role', 'User')->count(),
            'monthly_bookings' => (clone $bookingsQuery)->whereMonth('ngay_dat', date('m'))
                ->whereYear('ngay_dat', date('Y'))
                ->count(),
        ];

        // Recent bookings for staff to manage
        $recent_bookings = (clone $bookingsQuery)->with(['user', 'chuyenXe'])
            ->orderBy('ngay_dat', 'desc')
            ->limit(15)
            ->get();

        // Today's trips
        $today_trips = (clone $tripsQuery)->with(['nhaXe', 'tramDi', 'tramDen'])
            ->where('ngay_di', date('Y-m-d'))
            ->orderBy('gio_di')
            ->get();

        // Pending bookings that need attention
        $pending_bookings = (clone $bookingsQuery)->with(['user', 'chuyenXe'])
            ->whereStatus('pending')
            ->orderBy('ngay_dat', 'desc')
            ->limit(10)
            ->get();

        // Monthly booking trend
        $trendQuery = (clone $bookingsQuery)->select(
            DB::raw('DATE(ngay_dat) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('ngay_dat', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date');
        
        $monthly_trend = $trendQuery->pluck('count', 'date');

        return view('AdminLTE.staff.dashboard', compact(
            'stats',
            'recent_bookings',
            'today_trips',
            'pending_bookings',
            'monthly_trend'
        ));
    }
}
