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
        $user = auth()->user();
        
        // Lấy mã nhà xe của nhân viên
        $maNhaXe = $user->ma_nha_xe;
        
        $query = VeKhuyenMai::with(['khuyenMai', 'datVe.user', 'datVe.chuyenXe.nhaXe']);

        // Nếu nhân viên có mã nhà xe, chỉ hiển thị vé của nhà xe đó
        if ($maNhaXe) {
            $query->whereHas('datVe.chuyenXe', function($q) use ($maNhaXe) {
                $q->where('ma_nha_xe', $maNhaXe);
            });
        }

        // Lọc theo mã khuyến mãi
        if ($request->filled('ma_km')) {
            $query->where('ma_km', $request->ma_km);
        }

        $veKhuyenMai = $query->orderBy('id', 'desc')->paginate(15);
        
        // Chỉ lấy khuyến mãi của nhà xe mình hoặc khuyến mãi chung (ma_nha_xe = null)
        $khuyenMaiQuery = \App\Models\KhuyenMai::query();
        if ($maNhaXe) {
            $khuyenMaiQuery->where(function($q) use ($maNhaXe) {
                $q->where('ma_nha_xe', $maNhaXe)
                  ->orWhereNull('ma_nha_xe');
            });
        }
        $khuyenMai = $khuyenMaiQuery->get();
        $khuyenMaiList = $khuyenMaiQuery->where('ngay_bat_dau', '<=', now())
                                        ->where('ngay_ket_thuc', '>=', now())
                                        ->get();

        // Thống kê - chỉ tính vé của nhà xe mình
        $statsQuery = VeKhuyenMai::query();
        if ($maNhaXe) {
            $statsQuery->whereHas('datVe.chuyenXe', function($q) use ($maNhaXe) {
                $q->where('ma_nha_xe', $maNhaXe);
            });
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'today' => (clone $statsQuery)->whereHas('datVe', function ($q) {
                $q->whereDate('ngay_dat', today());
            })->count(),
            'this_month' => (clone $statsQuery)->whereHas('datVe', function ($q) {
                $q->whereMonth('ngay_dat', date('m'))->whereYear('ngay_dat', date('Y'));
            })->count(),
        ];

        return view('AdminLTE.staff.promotions.index', compact('veKhuyenMai', 'khuyenMai', 'khuyenMaiList', 'stats', 'maNhaXe'));
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
        $user = auth()->user();
        $maNhaXe = $user->ma_nha_xe;
        
        $request->validate([
            'ma_km' => 'required|exists:khuyen_mai,ma_km',
        ]);

        $veKhuyenMai = VeKhuyenMai::findOrFail($id);
        
        // Kiểm tra vé khuyến mãi có thuộc nhà xe của staff không
        if ($maNhaXe && $veKhuyenMai->datVe && $veKhuyenMai->datVe->chuyenXe) {
            if ($veKhuyenMai->datVe->chuyenXe->ma_nha_xe !== $maNhaXe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật vé khuyến mãi này'
                ], 403);
            }
        }
        
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
