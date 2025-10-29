<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\ChuyenXe;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripsController extends Controller
{

    public function index(Request $request)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        if (!$bus_company) {
            return redirect()->route('bus-owner.dashboard')->with('warning', 'Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin để được hỗ trợ.');
        }

        $query = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->with(['tramDi', 'tramDen']);

        // Filter by trip type (loai_chuyen)
        if ($request->filled('status')) {
            $query->where('loai_chuyen', $request->status);
        }

        // Search by trip name
        if ($request->filled('search')) {
            $query->where('ten_xe', 'like', '%' . $request->search . '%');
        }

        $trips = $query->orderBy('ngay_di', 'desc')->orderBy('gio_di', 'desc')->paginate(10);

        return view('AdminLTE.bus_owner.trips.index', compact('trips', 'bus_company'));
    }

    public function create()
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        if (!$bus_company) {
            return redirect()->route('bus-owner.dashboard')->with('warning', 'Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin để được hỗ trợ.');
        }

        return view('AdminLTE.bus_owner.trips.create', compact('bus_company'));
    }

    public function store(Request $request)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        if (!$bus_company) {
            return redirect()->route('bus-owner.dashboard')->with('warning', 'Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin để được hỗ trợ.');
        }

        $validated = $request->validate([
            'ma_xe' => 'nullable|string|max:50',
            'ten_xe' => 'required|string|max:255',
            'ma_tram_di' => 'required|exists:tram_xe,ma_tram_xe',
            'ma_tram_den' => 'required|exists:tram_xe,ma_tram_xe',
            'tram_trung_gian' => 'nullable|array',
            'tram_trung_gian.*' => 'exists:tram_xe,ma_tram_xe',
            'ngay_di' => 'required|date|after_or_equal:today',
            'gio_di' => 'required|date_format:H:i',
            'ngay_den' => 'nullable|date|after_or_equal:ngay_di',
            'loai_xe' => 'nullable|string|max:100',
            'gia_ve' => 'required|numeric|min:0',
            'so_cho' => 'required|integer|min:1',
            'so_ve' => 'nullable|integer|min:0|lte:so_cho',
            'loai_chuyen' => 'required|in:Một chiều,Khứ hồi',
            'ten_tai_xe' => 'nullable|string|max:255',
            'sdt_tai_xe' => 'nullable|string|max:20',
            'gio_den' => 'nullable|date_format:H:i',
        ], [
            'ten_xe.required' => 'Vui lòng nhập tên chuyến xe',
            'ma_tram_di.required' => 'Vui lòng chọn trạm đi',
            'ma_tram_den.required' => 'Vui lòng chọn trạm đến',
            'ngay_di.required' => 'Vui lòng chọn ngày đi',
            'ngay_di.after_or_equal' => 'Ngày đi phải từ hôm nay trở đi',
            'gio_di.required' => 'Vui lòng nhập giờ đi',
            'gia_ve.required' => 'Vui lòng nhập giá vé',
            'gia_ve.min' => 'Giá vé phải lớn hơn hoặc bằng 0',
            'so_cho.required' => 'Vui lòng nhập tổng số chỗ',
            'so_cho.min' => 'Số chỗ phải lớn hơn 0',
            'so_ve.lte' => 'Số vé đã bán không được vượt quá tổng số chỗ',
            'loai_chuyen.required' => 'Vui lòng chọn loại chuyến',
        ]);

        // Generate ma_xe if not provided
        if (empty($validated['ma_xe'])) {
            $lastTrip = ChuyenXe::where('ma_nha_xe', $bus_company->ma_nha_xe)
                ->orderBy('id', 'desc')
                ->first();
            $nextNumber = $lastTrip ? ($lastTrip->id + 1) : 1;
            $validated['ma_xe'] = 'XE' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        // Convert array trạm trung gian thành chuỗi
        $tramTrungGian = null;
        if (isset($validated['tram_trung_gian']) && is_array($validated['tram_trung_gian'])) {
            $tramTrungGian = implode(',', array_filter($validated['tram_trung_gian']));
        }

        ChuyenXe::create([
            'ma_xe' => $validated['ma_xe'],
            'ma_nha_xe' => $bus_company->ma_nha_xe,
            'ten_xe' => $validated['ten_xe'],
            'ma_tram_di' => $validated['ma_tram_di'] ?? null,
            'ma_tram_den' => $validated['ma_tram_den'] ?? null,
            'tram_trung_gian' => $tramTrungGian,
            'ngay_di' => $validated['ngay_di'],
            'gio_di' => $validated['gio_di'],
            'ngay_den' => $validated['ngay_den'] ?? null,
            'loai_xe' => $validated['loai_xe'] ?? null,
            'gia_ve' => $validated['gia_ve'],
            'so_cho' => $validated['so_cho'],
            'so_ve' => $validated['so_ve'] ?? 0,
            'loai_chuyen' => $validated['loai_chuyen'],
            'ten_tai_xe' => $validated['ten_tai_xe'] ?? null,
            'sdt_tai_xe' => $validated['sdt_tai_xe'] ?? null,
            'gio_den' => $validated['gio_den'] ?? null,
        ]);

        return redirect()->route('bus-owner.trips.index')->with('success', 'Chuyến xe đã được thêm thành công.');
    }

    public function show(ChuyenXe $trip)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        // Check if the trip belongs to the user's bus company
        if (!$bus_company || $trip->ma_nha_xe !== $bus_company->ma_nha_xe) {
            return redirect()->route('bus-owner.dashboard')->with('error', 'Bạn không có quyền xem chuyến xe này.');
        }

        $trip->load(['tramDi', 'tramDen', 'datVe.user']);
        $recent_bookings = $trip->datVe()->with('user')->orderBy('ngay_dat', 'desc')->limit(10)->get();

        return view('AdminLTE.bus_owner.trips.show', compact('trip', 'recent_bookings'));
    }

    public function edit(ChuyenXe $trip)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        // Check if the trip belongs to the user's bus company
        if (!$bus_company || $trip->ma_nha_xe !== $bus_company->ma_nha_xe) {
            return redirect()->route('bus-owner.dashboard')->with('error', 'Bạn không có quyền chỉnh sửa chuyến xe này.');
        }

        return view('AdminLTE.bus_owner.trips.edit', compact('trip'));
    }

    public function update(Request $request, ChuyenXe $trip)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        // Check if the trip belongs to the user's bus company
        if (!$bus_company || $trip->ma_nha_xe !== $bus_company->ma_nha_xe) {
            return redirect()->route('bus-owner.dashboard')->with('error', 'Bạn không có quyền cập nhật chuyến xe này.');
        }

        $validated = $request->validate([
            'ma_xe' => 'nullable|string|max:50',
            'ten_xe' => 'required|string|max:255',
            'ma_tram_di' => 'required|exists:tram_xe,ma_tram_xe',
            'ma_tram_den' => 'required|exists:tram_xe,ma_tram_xe',
            'tram_trung_gian' => 'nullable|array',
            'tram_trung_gian.*' => 'exists:tram_xe,ma_tram_xe',
            'ngay_di' => 'required|date',
            'gio_di' => 'required|date_format:H:i',
            'ngay_den' => 'nullable|date|after_or_equal:ngay_di',
            'loai_xe' => 'nullable|string|max:100',
            'gia_ve' => 'required|numeric|min:0',
            'so_cho' => 'required|integer|min:1',
            'so_ve' => 'nullable|integer|min:0|lte:so_cho',
            'loai_chuyen' => 'required|in:Một chiều,Khứ hồi',
            'ten_tai_xe' => 'nullable|string|max:255',
            'sdt_tai_xe' => 'nullable|string|max:20',
            'gio_den' => 'nullable|date_format:H:i',
        ], [
            'ma_tram_di.required' => 'Vui lòng chọn trạm đi',
            'ma_tram_den.required' => 'Vui lòng chọn trạm đến',
        ]);

        // Convert array trạm trung gian thành chuỗi
        if (isset($validated['tram_trung_gian']) && is_array($validated['tram_trung_gian'])) {
            $validated['tram_trung_gian'] = implode(',', array_filter($validated['tram_trung_gian']));
        }

        $trip->update($validated);

        return redirect()->route('bus-owner.trips.show', $trip)->with('success', 'Chuyến xe đã được cập nhật.');
    }

    public function destroy(ChuyenXe $trip)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $bus_company = $user->nhaXe;

        // Check if the trip belongs to the user's bus company
        if (!$bus_company || $trip->ma_nha_xe !== $bus_company->ma_nha_xe) {
            return redirect()->route('bus-owner.dashboard')->with('error', 'Bạn không có quyền xóa chuyến xe này.');
        }

        // Check if trip has bookings
        if ($trip->datVe()->count() > 0) {
            return back()->with('error', 'Không thể xóa chuyến xe này vì còn đặt vé.');
        }

        $trip->delete();

        return redirect()->route('bus-owner.trips.index')->with('success', 'Chuyến xe đã được xóa.');
    }
}
