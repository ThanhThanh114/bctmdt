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
        $query = DatVe::with(['user', 'chuyenXe']);

        // Filter by status
        if ($request->filled('status')) {
            $query->whereStatus($request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('ngay_dat', $request->date);
        }

        // Search by booking ID or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('fullname', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->orderBy('ngay_dat', 'desc')->paginate(10);

        return view('AdminLTE.staff.bookings.index', compact('bookings'));
    }

    public function show(DatVe $booking)
    {
        $booking->load(['user', 'chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen']);

        return view('AdminLTE.staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, DatVe $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $booking->status;
        $booking->update([
            'status' => $request->status,
            'staff_notes' => $request->notes
        ]);

        // If booking is confirmed, you might want to update available seats
        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            // Logic to update available seats if needed
        }

        return redirect()->back()->with('success', 'Trạng thái đặt vé đã được cập nhật.');
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
