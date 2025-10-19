<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\VeKhuyenMai;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VeKhuyenMai::with(['khuyenMai', 'datVe.user', 'datVe.chuyenXe']);

        // Lọc theo mã khuyến mãi
        if ($request->filled('ma_km')) {
            $query->where('ma_km', $request->ma_km);
        }

        $veKhuyenMai = $query->orderBy('id', 'desc')->paginate(15);
        $khuyenMai = \App\Models\KhuyenMai::all();
        $khuyenMaiList = \App\Models\KhuyenMai::active()->get();

        // Thống kê
        $stats = [
            'total' => VeKhuyenMai::count(),
            'today' => VeKhuyenMai::whereHas('datVe', function ($q) {
                $q->whereDate('ngay_dat', today());
            })->count(),
            'this_month' => VeKhuyenMai::whereHas('datVe', function ($q) {
                $q->whereMonth('ngay_dat', date('m'))->whereYear('ngay_dat', date('Y'));
            })->count(),
        ];

        return view('AdminLTE.staff.promotions.index', compact('veKhuyenMai', 'khuyenMai', 'khuyenMaiList', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'ma_km' => 'required|exists:khuyen_mai,ma_km',
        ]);

        $veKhuyenMai = VeKhuyenMai::findOrFail($id);
        $veKhuyenMai->update([
            'ma_km' => $request->ma_km,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật mã khuyến mãi thành công'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
