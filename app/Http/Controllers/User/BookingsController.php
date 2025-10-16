<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{

    public function index(Request $request)
    {
        $query = DatVe::where('user_id', Auth::id())->with(['chuyenXe.nhaXe']);

        // Filter by status
        if ($request->filled('status')) {
            $query->whereStatus($request->status);
        }

        // Search by booking ID or route name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('chuyenXe', function ($tripQuery) use ($search) {
                        $tripQuery->where('route_name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->orderBy('ngay_dat', 'desc')->paginate(10);

        return view('AdminLTE.user.bookings.index', compact('bookings'));
    }

    public function show(DatVe $booking)
    {
        // Ensure the booking belongs to the current user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đặt vé này.');
        }

        $booking->load(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen']);

        return view('AdminLTE.user.bookings.show', compact('booking'));
    }

    public function create(Request $request)
    {
        $trip_id = $request->get('trip_id');
        $trip = \App\Models\ChuyenXe::findOrFail($trip_id);

        // Check if trip has available seats
        if ($trip->available_seats <= 0) {
            return back()->with('error', 'Chuyến xe này đã hết ghế trống.');
        }

        return view('AdminLTE.user.bookings.create', compact('trip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:chuyen_xes,id',
            'seat_number' => 'required|string|max:10',
            'passenger_name' => 'required|string|max:100',
            'passenger_phone' => 'required|string|max:15',
            'notes' => 'nullable|string|max:500'
        ]);

        $trip = \App\Models\ChuyenXe::findOrFail($request->trip_id);

        // Check if seat is available
        $existing_booking = DatVe::where('chuyen_xe_id', $trip->id)
            ->where('seat_number', $request->seat_number)
            ->whereStatus('!=', 'cancelled')
            ->first();

        if ($existing_booking) {
            return back()->withErrors(['seat_number' => 'Ghế này đã được đặt.'])->withInput();
        }

        DatVe::create([
            'user_id' => Auth::id(),
            'chuyen_xe_id' => $trip->id,
            'seat_number' => $request->seat_number,
            'passenger_name' => $request->passenger_name,
            'passenger_phone' => $request->passenger_phone,
            'total_price' => $trip->price,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('user.bookings.index')->with('success', 'Đặt vé thành công! Vui lòng chờ xác nhận.');
    }

    public function cancel(DatVe $booking)
    {
        // Ensure the booking belongs to the current user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền hủy đặt vé này.');
        }

        // Check if booking can be cancelled (not too close to departure time)
        $departure_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $booking->chuyenXe->ngay_di . ' ' . $booking->chuyenXe->gio_di);
        $hours_until_departure = now()->diffInHours($departure_datetime, false);

        if ($hours_until_departure < 2) {
            return back()->with('error', 'Không thể hủy vé trong vòng 2 giờ trước khi khởi hành.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Đặt vé đã được hủy thành công.');
    }
}
