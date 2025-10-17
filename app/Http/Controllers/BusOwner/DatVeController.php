<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatVeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $query = DatVe::with(['user', 'chuyenXe'])
            ->whereHas('chuyenXe', function ($q) use ($busCompany) {
                $q->where('ma_nha_xe', $busCompany->ma_nha_xe);
            });

        // Filter by status
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter by date
        if ($request->has('ngay_dat') && $request->ngay_dat != '') {
            $query->whereDate('ngay_dat', $request->ngay_dat);
        }

        $bookings = $query->orderBy('ngay_dat', 'desc')->paginate(20);

        $stats = [
            'total' => DatVe::whereHas('chuyenXe', function ($q) use ($busCompany) {
                $q->where('ma_nha_xe', $busCompany->ma_nha_xe);
            })->count(),
            'pending' => DatVe::whereHas('chuyenXe', function ($q) use ($busCompany) {
                $q->where('ma_nha_xe', $busCompany->ma_nha_xe);
            })->where('trang_thai', 'Đã đặt')->count(),
            'confirmed' => DatVe::whereHas('chuyenXe', function ($q) use ($busCompany) {
                $q->where('ma_nha_xe', $busCompany->ma_nha_xe);
            })->where('trang_thai', 'Đã thanh toán')->count(),
            'cancelled' => DatVe::whereHas('chuyenXe', function ($q) use ($busCompany) {
                $q->where('ma_nha_xe', $busCompany->ma_nha_xe);
            })->where('trang_thai', 'Đã hủy')->count(),
        ];

        return view('AdminLTE.bus_owner.dat_ve.index', compact('bookings', 'busCompany', 'stats'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $booking = DatVe::with(['user', 'chuyenXe'])->findOrFail($id);

        // Check if booking belongs to this bus company
        if ($booking->chuyenXe->ma_nha_xe != $busCompany->ma_nha_xe) {
            abort(403, 'Không có quyền truy cập vé này.');
        }

        return view('AdminLTE.bus_owner.dat_ve.show', compact('booking', 'busCompany'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $booking = DatVe::with(['chuyenXe'])->findOrFail($id);

        // Check if booking belongs to this bus company
        if ($booking->chuyenXe->ma_nha_xe != $busCompany->ma_nha_xe) {
            abort(403, 'Không có quyền truy cập vé này.');
        }

        $validated = $request->validate([
            'trang_thai' => 'required|in:Đã đặt,Đã thanh toán,Đã hủy',
        ]);

        $oldStatus = $booking->trang_thai;
        $booking->update(['trang_thai' => $validated['trang_thai']]);

        // Update available seats if status changed
        if ($oldStatus !== $validated['trang_thai']) {
            if ($validated['trang_thai'] === 'Đã hủy' && $oldStatus !== 'Đã hủy') {
                // Release seats when booking is cancelled
                $booking->chuyenXe->decrement('so_ve', $booking->so_luong_ve ?? 1);
            } elseif ($oldStatus === 'Đã hủy' && $validated['trang_thai'] !== 'Đã hủy') {
                // Take seats back when cancelled booking is restored
                $booking->chuyenXe->increment('so_ve', $booking->so_luong_ve ?? 1);
            }
        }

        return back()->with('success', 'Cập nhật trạng thái đặt vé thành công.');
    }

    public function confirm($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $booking = DatVe::with(['chuyenXe'])->findOrFail($id);

        // Check if booking belongs to this bus company
        if ($booking->chuyenXe->ma_nha_xe != $busCompany->ma_nha_xe) {
            abort(403, 'Không có quyền truy cập vé này.');
        }

        $booking->update(['trang_thai' => 'Đã thanh toán']);

        return back()->with('success', 'Xác nhận đặt vé thành công.');
    }

    public function cancel($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $booking = DatVe::with(['chuyenXe'])->findOrFail($id);

        // Check if booking belongs to this bus company
        if ($booking->chuyenXe->ma_nha_xe != $busCompany->ma_nha_xe) {
            abort(403, 'Không có quyền truy cập vé này.');
        }

        $oldStatus = $booking->trang_thai;
        $booking->update(['trang_thai' => 'Đã hủy']);

        // Release seats if booking was not already cancelled
        if ($oldStatus !== 'Đã hủy') {
            $booking->chuyenXe->decrement('so_ve', $booking->so_luong_ve ?? 1);
        }

        return back()->with('success', 'Hủy đặt vé thành công.');
    }
}
