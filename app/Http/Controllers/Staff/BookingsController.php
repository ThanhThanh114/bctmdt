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
        $user = auth()->user();
        $maNhaXe = $user->ma_nha_xe;
        
        $query = DatVe::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe']);

        // Nếu nhân viên có mã nhà xe, chỉ hiển thị đặt vé của nhà xe đó
        if ($maNhaXe) {
            $query->whereHas('chuyenXe', function($q) use ($maNhaXe) {
                $q->where('ma_nha_xe', $maNhaXe);
            });
        }

        if ($request->filled('ma_ve')) {
            $query->where('ma_ve', 'like', '%' . $request->ma_ve . '%');
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('ngay_dat')) {
            $query->whereDate('ngay_dat', $request->ngay_dat);
        }

        if ($request->filled('user')) {
            $search = $request->user;
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $datVe = $query->orderBy('ngay_dat', 'desc')->paginate(15);

        // Thống kê - chỉ tính vé của nhà xe mình
        $statsQuery = DatVe::query();
        if ($maNhaXe) {
            $statsQuery->whereHas('chuyenXe', function($q) use ($maNhaXe) {
                $q->where('ma_nha_xe', $maNhaXe);
            });
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'da_dat' => (clone $statsQuery)->where('trang_thai', 'Đã đặt')->count(),
            'da_thanh_toan' => (clone $statsQuery)->where('trang_thai', 'Đã thanh toán')->count(),
            'da_huy' => (clone $statsQuery)->where('trang_thai', 'Đã hủy')->count(),
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
        $user = auth()->user();
        $maNhaXe = $user->ma_nha_xe;
        
        // Kiểm tra booking có thuộc nhà xe của staff không
        if ($maNhaXe && $booking->chuyenXe && $booking->chuyenXe->ma_nha_xe !== $maNhaXe) {
            return redirect()->route('staff.bookings.index')
                ->with('error', 'Bạn không có quyền cập nhật đặt vé này.');
        }
        
        $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

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
        $booking->trang_thai = $newStatus;
        $booking->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trạng thái đã được cập nhật'
            ]);
        }

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật');
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
            ->where('trang_thai', 'Đã đặt')
            ->orderBy('ngay_dat', 'asc')
            ->paginate(10);
        
        return view('AdminLTE.staff.bookings.pending', compact('pending_bookings'));
    }
}