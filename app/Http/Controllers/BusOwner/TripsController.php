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
        $bus_company = NhaXe::where('user_id', Auth::id())->first();

        if (!$bus_company) {
            return redirect()->route('bus-owner.profile.edit')->with('error', 'Bạn cần tạo thông tin nhà xe trước khi quản lý chuyến xe.');
        }

        $query = ChuyenXe::where('nha_xe_id', $bus_company->id)->with(['tramDi', 'tramDen']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by route name
        if ($request->filled('search')) {
            $query->where('route_name', 'like', '%' . $request->search . '%');
        }

        $trips = $query->orderBy('ngay_di', 'desc')->orderBy('gio_di', 'desc')->paginate(10);

        return view('AdminLTE.bus_owner.trips.index', compact('trips', 'bus_company'));
    }

    public function create()
    {
        $bus_company = NhaXe::where('user_id', Auth::id())->first();

        if (!$bus_company) {
            return redirect()->route('bus-owner.profile.edit')->with('error', 'Bạn cần tạo thông tin nhà xe trước khi thêm chuyến xe.');
        }

        return view('AdminLTE.bus_owner.trips.create', compact('bus_company'));
    }

    public function store(Request $request)
    {
        $bus_company = NhaXe::where('user_id', Auth::id())->first();

        $request->validate([
            'route_name' => 'required|string|max:255',
            'ngay_di' => 'required|date',
            'gio_di' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0|lte:total_seats',
            'status' => 'required|in:active,inactive'
        ]);

        ChuyenXe::create([
            'nha_xe_id' => $bus_company->id,
            'route_name' => $request->route_name,
            'ngay_di' => $request->ngay_di,
            'gio_di' => $request->gio_di,
            'price' => $request->price,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->available_seats,
            'status' => $request->status
        ]);

        return redirect()->route('bus-owner.trips.index')->with('success', 'Chuyến xe đã được thêm thành công.');
    }

    public function show(ChuyenXe $trip)
    {
        // Ensure the trip belongs to the current bus owner
        $bus_company = NhaXe::where('user_id', Auth::id())->first();
        if ($trip->nha_xe_id !== $bus_company->id) {
            abort(403, 'Bạn không có quyền xem chuyến xe này.');
        }

        $trip->load(['tramDi', 'tramDen', 'datVes.user']);
        $recent_bookings = $trip->datVes()->with('user')->latest()->limit(10)->get();

        return view('AdminLTE.bus_owner.trips.show', compact('trip', 'recent_bookings'));
    }

    public function edit(ChuyenXe $trip)
    {
        // Ensure the trip belongs to the current bus owner
        $bus_company = NhaXe::where('user_id', Auth::id())->first();
        if ($trip->nha_xe_id !== $bus_company->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa chuyến xe này.');
        }

        return view('AdminLTE.bus_owner.trips.edit', compact('trip'));
    }

    public function update(Request $request, ChuyenXe $trip)
    {
        // Ensure the trip belongs to the current bus owner
        $bus_company = NhaXe::where('user_id', Auth::id())->first();
        if ($trip->nha_xe_id !== $bus_company->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa chuyến xe này.');
        }

        $request->validate([
            'route_name' => 'required|string|max:255',
            'ngay_di' => 'required|date',
            'gio_di' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0|lte:total_seats',
            'status' => 'required|in:active,inactive'
        ]);

        $trip->update($request->only([
            'route_name',
            'ngay_di',
            'gio_di',
            'price',
            'total_seats',
            'available_seats',
            'status'
        ]));

        return redirect()->route('bus-owner.trips.show', $trip)->with('success', 'Chuyến xe đã được cập nhật.');
    }

    public function destroy(ChuyenXe $trip)
    {
        // Ensure the trip belongs to the current bus owner
        $bus_company = NhaXe::where('user_id', Auth::id())->first();
        if ($trip->nha_xe_id !== $bus_company->id) {
            abort(403, 'Bạn không có quyền xóa chuyến xe này.');
        }

        // Check if trip has bookings
        if ($trip->datVes()->count() > 0) {
            return back()->with('error', 'Không thể xóa chuyến xe này vì còn đặt vé.');
        }

        $trip->delete();

        return redirect()->route('bus-owner.trips.index')->with('success', 'Chuyến xe đã được xóa.');
    }
}
