<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KhuyenMaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KhuyenMai::query();

        // Tìm kiếm theo tên hoặc mã code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_km', 'like', "%{$search}%")
                    ->orWhere('ma_code', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $today = Carbon::today();
            switch ($request->status) {
                case 'active':
                    $query->where('ngay_bat_dau', '<=', $today)
                        ->where('ngay_ket_thuc', '>=', $today);
                    break;
                case 'upcoming':
                    $query->where('ngay_bat_dau', '>', $today);
                    break;
                case 'expired':
                    $query->where('ngay_ket_thuc', '<', $today);
                    break;
            }
        }

        $khuyenMai = $query->orderBy('ngay_bat_dau', 'desc')->paginate(15);

        // Thống kê
        $today = Carbon::today();
        $stats = [
            'total' => KhuyenMai::count(),
            'active' => KhuyenMai::where('ngay_bat_dau', '<=', $today)
                ->where('ngay_ket_thuc', '>=', $today)
                ->count(),
            'upcoming' => KhuyenMai::where('ngay_bat_dau', '>', $today)->count(),
            'expired' => KhuyenMai::where('ngay_ket_thuc', '<', $today)->count(),
        ];

        return view('AdminLTE.admin.khuyen_mai.index', compact('khuyenMai', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('AdminLTE.admin.khuyen_mai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_km' => 'required|string|max:100',
            'ma_code' => 'required|string|max:20|unique:khuyen_mai,ma_code',
            'giam_gia' => 'required|numeric|min:0|max:100',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
        ], [
            'ten_km.required' => 'Vui lòng nhập tên khuyến mãi',
            'ma_code.required' => 'Vui lòng nhập mã khuyến mãi',
            'ma_code.unique' => 'Mã khuyến mãi đã tồn tại',
            'giam_gia.required' => 'Vui lòng nhập phần trăm giảm giá',
            'giam_gia.min' => 'Giảm giá phải lớn hơn hoặc bằng 0',
            'giam_gia.max' => 'Giảm giá không được vượt quá 100%',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu',
            'ngay_ket_thuc.required' => 'Vui lòng chọn ngày kết thúc',
            'ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);

        KhuyenMai::create($validated);

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Thêm khuyến mãi thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(KhuyenMai $khuyenmai)
    {
        // Lấy danh sách vé đã sử dụng khuyến mãi này với eager loading đầy đủ
        $veKhuyenMais = $khuyenmai->veKhuyenMai()
            ->with([
                'datVe.user',
                'datVe.chuyenXe.tramDi',
                'datVe.chuyenXe.tramDen'
            ])
            ->orderBy('id', 'desc')
            ->get();

        // Nhóm theo ma_ve để tính số ghế và tổng tiền
        $recentBookings = $veKhuyenMais->groupBy(function ($item) {
            return $item->datVe ? $item->datVe->ma_ve : null;
        })->filter(function ($group, $key) {
            return $key !== null;
        })->take(10)->map(function ($group) use ($khuyenmai) {
            $firstBooking = $group->first()->datVe;

            // Đếm số ghế dựa trên số dat_ve_id UNIQUE (mỗi ghế = 1 dat_ve)
            // Lấy tất cả dat_ve có cùng ma_ve
            $allDatVe = \App\Models\DatVe::where('ma_ve', $firstBooking->ma_ve)->get();
            $soLuongGhe = $allDatVe->count();
            $soGheList = $allDatVe->pluck('so_ghe')->implode(', ');

            $giaVe = $firstBooking->chuyenXe->gia_ve ?? 0;
            $tongTien = $soLuongGhe * $giaVe;

            // Logic chuẩn: giam_gia = % được giảm
            // VD: giam_gia = 90% → được giảm 90% → khách chỉ trả 10%
            $giamGia = $tongTien * ($khuyenmai->giam_gia / 100);
            $soTienPhaiTra = $tongTien - $giamGia;

            return (object)[
                'id' => $firstBooking->id,
                'ma_ve' => $firstBooking->ma_ve,
                'user' => $firstBooking->user,
                'chuyenXe' => $firstBooking->chuyenXe,
                'so_luong_ghe' => $soLuongGhe,
                'so_ghe_list' => $soGheList,
                'tong_tien' => $tongTien,
                'giam_gia' => $giamGia,
                'so_tien_phai_tra' => $soTienPhaiTra,
                'ngay_dat' => $firstBooking->ngay_dat,
                'created_at' => $firstBooking->ngay_dat
            ];
        })->values();

        // Thống kê sử dụng
        $totalBookings = $veKhuyenMais->groupBy(function ($item) {
            return $item->datVe ? $item->datVe->ma_ve : null;
        })->filter(function ($group, $key) {
            return $key !== null;
        })->count();

        $usageStats = [
            'total_uses' => $veKhuyenMais->count(),
            'total_bookings' => $totalBookings,
            'total_discount' => $recentBookings->sum('giam_gia'),
        ];

        return view('AdminLTE.admin.khuyen_mai.show', compact('khuyenmai', 'usageStats', 'recentBookings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KhuyenMai $khuyenmai)
    {
        return view('AdminLTE.admin.khuyen_mai.edit', compact('khuyenmai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KhuyenMai $khuyenmai)
    {
        $validated = $request->validate([
            'ten_km' => 'required|string|max:100',
            'ma_code' => 'required|string|max:20|unique:khuyen_mai,ma_code,' . $khuyenmai->ma_km . ',ma_km',
            'giam_gia' => 'required|numeric|min:0|max:100',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
        ], [
            'ten_km.required' => 'Vui lòng nhập tên khuyến mãi',
            'ma_code.required' => 'Vui lòng nhập mã khuyến mãi',
            'ma_code.unique' => 'Mã khuyến mãi đã tồn tại',
            'giam_gia.required' => 'Vui lòng nhập phần trăm giảm giá',
            'giam_gia.min' => 'Giảm giá phải lớn hơn hoặc bằng 0',
            'giam_gia.max' => 'Giảm giá không được vượt quá 100%',
            'ngay_bat_dau.required' => 'Vui lòng chọn ngày bắt đầu',
            'ngay_ket_thuc.required' => 'Vui lòng chọn ngày kết thúc',
            'ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);

        $khuyenmai->update($validated);

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KhuyenMai $khuyenmai)
    {
        try {
            // Kiểm tra xem có vé nào đang sử dụng khuyến mãi này không
            $usageCount = $khuyenmai->veKhuyenMai()->count();

            if ($usageCount > 0) {
                return redirect()->route('admin.khuyenmai.index')
                    ->with('error', 'Không thể xóa khuyến mãi đã được sử dụng!');
            }

            $khuyenmai->delete();

            return redirect()->route('admin.khuyenmai.index')
                ->with('success', 'Xóa khuyến mãi thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.khuyenmai.index')
                ->with('error', 'Không thể xóa khuyến mãi này!');
        }
    }

    /**
     * Toggle promotion status (activate/deactivate)
     */
    public function toggleStatus(KhuyenMai $khuyenmai)
    {
        $today = Carbon::today();

        if ($khuyenmai->ngay_ket_thuc < $today) {
            return redirect()->back()->with('error', 'Không thể kích hoạt khuyến mãi đã hết hạn!');
        }

        // Thay đổi ngày bắt đầu để kích hoạt/vô hiệu hóa
        if ($khuyenmai->ngay_bat_dau > $today) {
            // Nếu chưa bắt đầu, đặt ngày bắt đầu là hôm nay
            $khuyenmai->update(['ngay_bat_dau' => $today]);
            $message = 'Đã kích hoạt khuyến mãi!';
        } else {
            // Nếu đang hoạt động, đặt ngày kết thúc là hôm qua
            $khuyenmai->update(['ngay_ket_thuc' => Carbon::yesterday()]);
            $message = 'Đã vô hiệu hóa khuyến mãi!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Check promotion code validity
     */
    public function checkCode(Request $request)
    {
        $validated = $request->validate([
            'ma_code' => 'required|string',
        ]);

        $today = Carbon::today();
        $promotion = KhuyenMai::where('ma_code', $validated['ma_code'])
            ->where('ngay_bat_dau', '<=', $today)
            ->where('ngay_ket_thuc', '>=', $today)
            ->first();

        if ($promotion) {
            return response()->json([
                'valid' => true,
                'promotion' => $promotion,
                'message' => 'Mã khuyến mãi hợp lệ!',
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn!',
        ], 404);
    }
}