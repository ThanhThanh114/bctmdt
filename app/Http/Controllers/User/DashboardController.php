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
            ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
            ->orderByRaw("CASE WHEN ngay_di > CURDATE() THEN ngay_di ELSE CONCAT(ngay_di, ' ', gio_di) END")
            ->select('dat_ve.*')
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

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required|string|max:100',
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Nam,Nữ,Khác'
        ]);

        $user->update([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Thông tin tài khoản đã được cập nhật thành công!');
    }

    public function upgradeToDriver(Request $request)
    {
        $user = Auth::user();

        // Check if already upgraded
        if ($user->role !== 'user') {
            return back()->withErrors(['upgrade' => 'Bạn đã nâng cấp tài khoản rồi.']);
        }

        // Validate request
        $request->validate([
            'vehicle_type' => 'required|string',
            'license_number' => 'required|string|max:50',
            'license_expiry' => 'required|date|after:today',
            'experience_years' => 'required|integer|min:1',
            'company_name' => 'nullable|string|max:100',
            'terms' => 'required|accepted'
        ]);

        // Update role to bus_owner (assuming driver means bus_owner)
        $user->update([
            'role' => 'bus_owner',
            'vehicle_type' => $request->vehicle_type,
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'experience_years' => $request->experience_years,
            'company_name' => $request->company_name
        ]);

        // Send confirmation email (optional)
        try {
            // Assuming mail setup
            // Mail::to($user->email)->send(new UpgradeConfirmation($user));
        } catch (\Exception $e) {
            // Log error but don't fail
        }

        return redirect()->route('user.dashboard')->with('success', 'Tài khoản đã được nâng cấp thành công! Bạn có thể quản lý nhà xe ngay bây giờ.');
    }
}
