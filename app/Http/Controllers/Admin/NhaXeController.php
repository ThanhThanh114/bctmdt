<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhaXe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class NhaXeController extends Controller
{
    /**
     * Hiển thị danh sách nhà xe
     */
    public function index(Request $request)
    {
        $query = NhaXe::withCount(['chuyenXe', 'nhanVien', 'users']);

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_nha_xe', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái (chỉ khi cột tồn tại)
        if ($request->filled('trang_thai') && $request->trang_thai !== 'all' && Schema::hasColumn('nha_xe', 'trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $nhaXe = $query->orderBy('ma_nha_xe', 'desc')->paginate(15);

        // Thống kê
        $total = NhaXe::count();
        if (Schema::hasColumn('nha_xe', 'trang_thai')) {
            $hoat_dong = NhaXe::where('trang_thai', 'hoat_dong')->count();
            $bi_khoa = NhaXe::where('trang_thai', 'bi_khoa')->count();
        } else {
            // Nếu cột không tồn tại, fallback an toàn
            $hoat_dong = $total;
            $bi_khoa = 0;
        }

        $stats = [
            'total' => $total,
            'hoat_dong' => $hoat_dong,
            'bi_khoa' => $bi_khoa,
        ];

        return view('AdminLTE.admin.nha_xe.index', compact('nhaXe', 'stats'));
    }

    /**
     * Hiển thị chi tiết nhà xe
     */
    public function show(NhaXe $nhaxe)
    {
        $nhaxe->load([
            'chuyenXe' => function($query) {
                $query->with(['tramDi', 'tramDen'])->orderBy('ngay_di', 'desc')->take(10);
            },
            'nhanVien' => function($query) {
                $query->orderBy('ma_nv', 'desc')->take(10);
            },
            'users' => function($query) {
                $query->where('role', 'staff')->orderBy('id', 'desc')->take(10);
            }
        ]);

        // Thống kê chi tiết
        $stats = [
            'tong_chuyen_xe' => $nhaxe->chuyenXe()->count(),
            'chuyen_xe_hom_nay' => $nhaxe->chuyenXe()->whereDate('ngay_di', today())->count(),
            'tong_nhan_vien' => $nhaxe->nhanVien()->count(),
            'tong_tai_khoan' => $nhaxe->users()->count(),
        ];

        return view('AdminLTE.admin.nha_xe.show', compact('nhaxe', 'stats'));
    }

    /**
     * Khóa nhà xe
     */
    public function lock(Request $request, NhaXe $nhaxe)
    {
        $validated = $request->validate([
            'ly_do_khoa' => 'required|string|max:500',
        ], [
            'ly_do_khoa.required' => 'Vui lòng nhập lý do khóa nhà xe',
            'ly_do_khoa.max' => 'Lý do không được vượt quá 500 ký tự',
        ]);

        $updateData = [];
        if (Schema::hasColumn('nha_xe', 'trang_thai')) {
            $updateData['trang_thai'] = 'bi_khoa';
        }
        if (Schema::hasColumn('nha_xe', 'ly_do_khoa')) {
            $updateData['ly_do_khoa'] = $validated['ly_do_khoa'];
        }
        if (Schema::hasColumn('nha_xe', 'ngay_khoa')) {
            $updateData['ngay_khoa'] = now();
        }
        if (Schema::hasColumn('nha_xe', 'admin_khoa_id')) {
            $updateData['admin_khoa_id'] = auth()->id();
        }

        if (!empty($updateData)) {
            $nhaxe->update($updateData);
        }

        // Lấy danh sách users để lưu thông tin gốc
        $users = User::where('ma_nha_xe', (string)$nhaxe->ma_nha_xe)
            ->whereIn('role', ['staff', 'bus_owner'])
            ->get();

        $userIds = $users->pluck('id')->toArray();

        // Cập nhật từng user để lưu thông tin gốc
        foreach ($users as $user) {
            $user->update([
                'is_active' => 0,
                'locked_reason' => $validated['ly_do_khoa'],
                'locked_at' => now(),
                'locked_by' => auth()->id(),
                'locked_original_role' => $user->role, // Lưu role gốc
                'locked_original_ma_nha_xe' => $user->ma_nha_xe, // Lưu ma_nha_xe gốc
                'role' => 'user', // Hạ cấp xuống user
                'ma_nha_xe' => null, // Xóa liên kết với nhà xe
            ]);
        }

        $affected = count($users);

        // Xóa session tất cả người dùng bị ảnh hưởng
        try {
            if (!empty($userIds)) {
                DB::table('sessions')->whereIn('user_id', $userIds)->delete();
            }
        } catch (\Throwable $e) {
            \Log::warning('Không thể xóa session khi khóa nhà xe: ' . $e->getMessage());
        }

        \Log::info("Đã khóa nhà xe {$nhaxe->ma_nha_xe}, khóa và hạ cấp {$affected} tài khoản xuống quyền user");

        return redirect()->route('admin.nha-xe.index')->with('success', "Đã khóa nhà xe và hạ cấp {$affected} tài khoản xuống quyền User thành công! Có thể khôi phục lại khi mở khóa.");
    }

    /**
     * Mở khóa nhà xe
     */
    public function unlock(NhaXe $nhaxe)
    {
        $updateData = [];
        if (Schema::hasColumn('nha_xe', 'trang_thai')) {
            $updateData['trang_thai'] = 'hoat_dong';
        }
        if (Schema::hasColumn('nha_xe', 'ly_do_khoa')) {
            $updateData['ly_do_khoa'] = null;
        }
        if (Schema::hasColumn('nha_xe', 'ngay_khoa')) {
            $updateData['ngay_khoa'] = null;
        }
        if (Schema::hasColumn('nha_xe', 'admin_khoa_id')) {
            $updateData['admin_khoa_id'] = null;
        }

        if (!empty($updateData)) {
            $nhaxe->update($updateData);
        }

        // Lấy danh sách users bị khóa của nhà xe này
        $users = User::where('locked_original_ma_nha_xe', (string)$nhaxe->ma_nha_xe)
            ->where('is_active', 0)
            ->get();

        // Khôi phục từng user
        $affected = 0;
        foreach ($users as $user) {
            $user->update([
                'is_active' => 1,
                'locked_reason' => null,
                'locked_at' => null,
                'locked_by' => null,
                'role' => $user->locked_original_role ?? 'user', // Khôi phục role gốc
                'ma_nha_xe' => $user->locked_original_ma_nha_xe, // Khôi phục ma_nha_xe gốc
                'locked_original_role' => null, // Xóa backup
                'locked_original_ma_nha_xe' => null, // Xóa backup
            ]);
            $affected++;
        }

        \Log::info("Đã mở khóa nhà xe {$nhaxe->ma_nha_xe}, mở khóa và khôi phục quyền {$affected} tài khoản");

        return redirect()->route('admin.nha-xe.index')->with('success', "Đã mở khóa nhà xe và khôi phục quyền {$affected} tài khoản thành công!");
    }

    /**
     * Xóa nhà xe (soft delete - chỉ khóa vĩnh viễn)
     */
    public function destroy(NhaXe $nhaxe)
    {
        // Kiểm tra xem có chuyến xe đang hoạt động không
        $activeTrips = $nhaxe->chuyenXe()->where('ngay_di', '>=', today())->count();

        if ($activeTrips > 0) {
            return redirect()->back()->with('error', 'Không thể xóa nhà xe có chuyến xe đang hoạt động!');
        }

        // Khóa nhà xe thay vì xóa
        $updateData = [];
        if (Schema::hasColumn('nha_xe', 'trang_thai')) {
            $updateData['trang_thai'] = 'bi_khoa';
        }
        if (Schema::hasColumn('nha_xe', 'ly_do_khoa')) {
            $updateData['ly_do_khoa'] = 'Đã xóa bởi Admin';
        }
        if (Schema::hasColumn('nha_xe', 'ngay_khoa')) {
            $updateData['ngay_khoa'] = now();
        }
        if (Schema::hasColumn('nha_xe', 'admin_khoa_id')) {
            $updateData['admin_khoa_id'] = auth()->id();
        }

        if (!empty($updateData)) {
            $nhaxe->update($updateData);
        }

        // Vô hiệu hóa tất cả tài khoản
        User::where('ma_nha_xe', (string)$nhaxe->ma_nha_xe)->update(['is_active' => 0]);

        return redirect()->route('admin.nha-xe.index')
            ->with('success', 'Đã khóa nhà xe thành công!');
    }
}
