<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;
class BookingsController extends Controller
{

    public function index(Request $request)
    {
        $query = DatVe::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen']);

        // Filter by ma_ve
        if ($request->filled('ma_ve')) {
            $query->where('ma_ve', 'like', '%' . $request->ma_ve . '%');
        }

        // Filter by trang_thai
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter by ngay_dat
        if ($request->filled('ngay_dat')) {
            $query->whereDate('ngay_dat', $request->ngay_dat);
        }

        // Search by user name
        if ($request->filled('user')) {
            $search = $request->user;
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $datVe = $query->orderBy('ngay_dat', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => DatVe::count(),
            'da_dat' => DatVe::where('trang_thai', 'Đã đặt')->count(),
            'da_thanh_toan' => DatVe::where('trang_thai', 'Đã thanh toán')->count(),
            'da_huy' => DatVe::where('trang_thai', 'Đã hủy')->count(),
        ];

        return view('AdminLTE.staff.bookings.index', compact('datVe', 'stats'));
    }

    public function show(DatVe $booking)
    {
        $booking->load(['user', 'chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen']);
        return view('AdminLTE.staff.bookings.show', compact('booking'));
    }
    public function updateStatus(Request $request, DatVe $booking)
    {
        $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        // Map English status to Vietnamese status
        $statusMap = [
            'pending' => 'Đã đặt',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'Đã đặt' => 'Đã đặt',
            'Đã thanh toán' => 'Đã thanh toán',
            'Đã xác nhận' => 'Đã xác nhận',
            'Đã hủy' => 'Đã hủy'
        ];

        $newStatus = $statusMap[$request->status] ?? $request->status;

        // Update using direct assignment and save
        $booking->trang_thai = $newStatus;
        $booking->save();

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đặt vé đã được cập nhật thành công'
            ]);
        }

        // Otherwise redirect back with success message
        return redirect()->back()->with('success', 'Trạng thái đặt vé đã được cập nhật thành công');
    }

    public function todayBookings()
    {
        $today_bookings = DatVe::with(['user', 'chuyenXe'])
            ->whereDate('ngay_dat', date('Y-m-d'))
            ->orderBy('ngay_dat', 'desc')
            ->get();
        return view('AdminLTE.staff.bookings.today', compact('today_bookings'));
    }
    public function pendingBookings()
    {
        $pending_bookings = DatVe::with(['user', 'chuyenXe'])
            ->whereStatus('pending')
            ->orderBy('ngay_dat', 'asc')
            ->paginate(10);
        return view('AdminLTE.staff.bookings.pending', compact('pending_bookings'));
    }
}