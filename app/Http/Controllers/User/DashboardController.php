<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // User-specific statistics
        $stats = [
            'total_bookings' => DatVe::where('user_id', $user->id)->count(),
            'confirmed_bookings' => DatVe::where('user_id', $user->id)
                ->whereStatus('confirmed')
                ->count(),
            'pending_bookings' => DatVe::where('user_id', $user->id)
                ->whereStatus('pending')
                ->count(),
            'cancelled_bookings' => DatVe::where('user_id', $user->id)
                ->whereStatus('cancelled')
                ->count(),
            'total_spent' => DatVe::with('chuyenXe')
                ->where('user_id', $user->id)
                ->whereStatus('confirmed')
                ->get()
                ->sum(function ($booking) {
                    return $booking->chuyenXe->gia_ve ?? 0;
                }),
            'upcoming_trips' => DatVe::where('user_id', $user->id)
                ->whereStatus('confirmed')
                ->whereHas('chuyenXe', function ($q) {
                    $q->where(function ($query) {
                        $query->where('ngay_di', '>', today())
                            ->orWhere(function ($q2) {
                                $q2->where('ngay_di', today())
                                    ->where('gio_di', '>', now()->format('H:i:s'));
                            });
                    });
                })
                ->count(),
        ];

        // User's recent bookings
        $recent_bookings = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen'])
            ->where('user_id', $user->id)
            ->orderBy('ngay_dat', 'desc')
            ->limit(10)
            ->get();

        // Upcoming trips
        $upcoming_trips = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen'])
            ->where('user_id', $user->id)
            ->whereStatus('confirmed')
            ->whereHas('chuyenXe', function ($q) {
                $q->where(function ($query) {
                    $query->where('ngay_di', '>', today())
                        ->orWhere(function ($q2) {
                            $q2->where('ngay_di', today())
                                ->where('gio_di', '>', now()->format('H:i:s'));
                        });
                });
            })
            ->orderByDesc('ngay_dat')
            ->limit(5)
            ->get();

        // Popular routes for quick booking
        $popular_routes = ChuyenXe::selectRaw("CONCAT(tram_di.ten_tram, ' - ', tram_den.ten_tram) as route_name, COUNT(*) as trip_count")
            ->join('tram_xe as tram_di', 'chuyen_xe.ma_tram_di', '=', 'tram_di.ma_tram_xe')
            ->join('tram_xe as tram_den', 'chuyen_xe.ma_tram_den', '=', 'tram_den.ma_tram_xe')
            ->groupBy('tram_di.ten_tram', 'tram_den.ten_tram')
            ->orderBy('trip_count', 'desc')
            ->limit(6)
            ->get();

        // Monthly spending for the current year
        $monthly_bookings = DatVe::with('chuyenXe')
            ->where('user_id', $user->id)
            ->whereStatus('confirmed')
            ->whereYear('ngay_dat', date('Y'))
            ->get()
            ->groupBy(function ($booking) {
                return $booking->ngay_dat->format('n'); // Month number
            });

        $monthly_spending = [];
        foreach ($monthly_bookings as $month => $bookings) {
            $monthly_spending[$month] = $bookings->sum(function ($booking) {
                return $booking->chuyenXe->gia_ve ?? 0;
            });
        }

        return view('AdminLTE.user.dashboard', compact(
            'stats',
            'recent_bookings',
            'upcoming_trips',
            'popular_routes',
            'monthly_spending',
            'user'
        ));
    }
}
