<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    /**
     * Display a listing of trips for staff (read-only)
     */
    public function index(Request $request)
    {
        $query = ChuyenXe::with(['tramDi', 'tramDen', 'nhaXe']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by route name
        if ($request->filled('search')) {
            $query->where('route_name', 'like', '%' . $request->search . '%');
        }

        $trips = $query->orderBy('ngay_di', 'desc')
                      ->orderBy('gio_di', 'desc')
                      ->paginate(10);

        return view('AdminLTE.staff.trips.index', compact('trips'));
    }

    /**
     * Display the specified trip for staff (read-only)
     */
    public function show(ChuyenXe $trip)
    {
        $trip->load(['tramDi', 'tramDen', 'nhaXe']);

        return view('AdminLTE.staff.trips.show', compact('trip'));
    }
}
