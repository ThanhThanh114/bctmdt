<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TaiKhoanNhaXeController extends Controller
{
    /**
     * Hiển thị danh sách tài khoản nhà xe
     */
    public function index(Request $request)
    {
        $query = User::with('nhaXe')
            ->whereIn('role', ['staff', 'bus_owner']);

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo nhà xe
        if ($request->filled('ma_nha_xe') && $request->ma_nha_xe !== 'all') {
            $query->where('ma_nha_xe', $request->ma_nha_xe);
        }

        // Lọc theo role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Lọc theo trạng thái
        if ($request->filled('is_active')) {
            if ($request->is_active === '1') {
                $query->where('is_active', 1);
            } elseif ($request->is_active === '0') {
                $query->where('is_active', 0);
            }
        }

        $taiKhoan = $query->orderBy('id', 'desc')->paginate(15);

        // Danh sách nhà xe cho dropdown
        $nhaXe = NhaXe::orderBy('ten_nha_xe')->get();

        // Thống kê
        $stats = [
            'total' => User::whereIn('role', ['staff', 'bus_owner'])->count(),
            'active' => User::whereIn('role', ['staff', 'bus_owner'])->where('is_active', 1)->count(),
            'locked' => User::whereIn('role', ['staff', 'bus_owner'])->where('is_active', 0)->count(),
            'staff' => User::where('role', 'staff')->count(),
            'bus_owner' => User::where('role', 'bus_owner')->count(),
        ];

        return view('AdminLTE.admin.tai_khoan_nha_xe.index', compact('taiKhoan', 'nhaXe', 'stats'));
    }

    /**
     * Hiển thị chi tiết tài khoản
     */
    public function show(User $taikhoan)
    {
        $taikhoan->load('nhaXe');
        return view('AdminLTE.admin.tai_khoan_nha_xe.show', compact('taikhoan'));
    }

    /**
     * Khóa tài khoản
     */
    public function lock(Request $request, User $taikhoan)
    {
        $validated = $request->validate([
            'ly_do_khoa' => 'required|string|max:500',
        ], [
            'ly_do_khoa.required' => 'Vui lòng nhập lý do khóa tài khoản',
            'ly_do_khoa.max' => 'Lý do không được vượt quá 500 ký tự',
        ]);

        // Lưu role cũ và ma_nha_xe cũ để khôi phục sau
        $oldRole = $taikhoan->role;
        $oldMaNhaXe = $taikhoan->ma_nha_xe;
        
        $updateData = [
            'is_active' => 0,
            'locked_reason' => $validated['ly_do_khoa'],
            'locked_at' => now(),
            'locked_by' => auth()->id(),
            'locked_original_role' => $oldRole, // Lưu role gốc
            'locked_original_ma_nha_xe' => $oldMaNhaXe, // Lưu ma_nha_xe gốc
        ];

        // Nếu là bus_owner hoặc staff, hạ cấp xuống user
        if (in_array($taikhoan->role, ['bus_owner', 'staff'])) {
            $updateData['role'] = 'user';
            $updateData['ma_nha_xe'] = null; // Xóa liên kết với nhà xe
        }

        $taikhoan->update($updateData);

        // Xoá session của người dùng (nếu dùng database session) để đăng xuất ngay lập tức
        try {
            DB::table('sessions')->where('user_id', $taikhoan->id)->delete();
        } catch (\Throwable $e) {
            // Không bắt buộc thành công — chỉ ghi log nếu có lỗi
            \Log::warning('Không thể xóa session khi khóa tài khoản: ' . $e->getMessage());
        }

        $message = "Admin " . auth()->id() . " đã khóa tài khoản {$taikhoan->id} - {$taikhoan->username}";
        if ($oldRole !== 'user') {
            $message .= " và hạ cấp từ {$oldRole} xuống user";
        }
        \Log::info($message);

        $successMessage = 'Đã khóa tài khoản thành công!';
        if ($oldRole !== 'user') {
            $successMessage .= ' Tài khoản đã được hạ cấp xuống quyền User và có thể khôi phục lại khi mở khóa.';
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Mở khóa tài khoản
     */
    public function unlock(User $taikhoan)
    {
        // Khôi phục role và ma_nha_xe gốc nếu có
        $updateData = [
            'is_active' => 1,
            'locked_reason' => null,
            'locked_at' => null,
            'locked_by' => null,
        ];

        // Tự động khôi phục role và ma_nha_xe gốc
        if ($taikhoan->locked_original_role) {
            $updateData['role'] = $taikhoan->locked_original_role;
        }
        if ($taikhoan->locked_original_ma_nha_xe) {
            $updateData['ma_nha_xe'] = $taikhoan->locked_original_ma_nha_xe;
        }

        // Xóa thông tin backup sau khi khôi phục
        $updateData['locked_original_role'] = null;
        $updateData['locked_original_ma_nha_xe'] = null;

        $taikhoan->update($updateData);

        $message = "Admin " . auth()->id() . " đã mở khóa tài khoản {$taikhoan->id} - {$taikhoan->username}";
        if ($taikhoan->role !== 'user') {
            $message .= " và khôi phục quyền {$taikhoan->role}";
        }
        \Log::info($message);

        $successMessage = 'Đã mở khóa tài khoản thành công!';
        if ($taikhoan->role !== 'user') {
            $successMessage .= " Quyền {$taikhoan->role} đã được khôi phục.";
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Reset mật khẩu
     */
    public function resetPassword(User $taikhoan)
    {
        $newPassword = 'Password@123'; // Mật khẩu mặc định
        
        $taikhoan->update([
            'password' => Hash::make($newPassword),
        ]);

        \Log::info("Admin " . auth()->id() . " đã reset mật khẩu tài khoản {$taikhoan->id} - {$taikhoan->username}");

        return redirect()->back()->with('success', "Đã reset mật khẩu thành công! Mật khẩu mới: {$newPassword}");
    }

    /**
     * Xóa tài khoản
     */
    public function destroy(User $taikhoan)
    {
        // Không cho xóa admin
        if ($taikhoan->role === 'admin') {
            return redirect()->back()->with('error', 'Không thể xóa tài khoản Admin!');
        }

        $username = $taikhoan->username;
        $taikhoan->delete();

        \Log::warning("Admin " . auth()->id() . " đã xóa tài khoản: {$username}");

        return redirect()->route('admin.tai-khoan-nha-xe.index')
            ->with('success', 'Đã xóa tài khoản thành công!');
    }

    /**
     * Khóa hàng loạt theo nhà xe
     */
    public function lockByNhaXe(Request $request)
    {
        $validated = $request->validate([
            'ma_nha_xe' => 'required|exists:nha_xe,ma_nha_xe',
            'ly_do_khoa' => 'required|string|max:500',
        ]);

        // Lấy danh sách users trước để lưu thông tin gốc
        $users = User::where('ma_nha_xe', $validated['ma_nha_xe'])
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
            \Log::warning('Không thể xóa session khi khóa hàng loạt: ' . $e->getMessage());
        }

        \Log::info("Admin " . auth()->id() . " đã khóa và hạ cấp {$affected} tài khoản của nhà xe {$validated['ma_nha_xe']} xuống quyền user");

        return redirect()->back()->with('success', "Đã khóa {$affected} tài khoản và hạ cấp xuống quyền User thành công! Có thể khôi phục lại khi mở khóa.");
    }

    /**
     * Mở khóa hàng loạt theo nhà xe
     */
    public function unlockByNhaXe(Request $request)
    {
        $validated = $request->validate([
            'ma_nha_xe' => 'required|exists:nha_xe,ma_nha_xe',
        ]);

        $affected = User::where('ma_nha_xe', $validated['ma_nha_xe'])
            ->whereIn('role', ['staff', 'bus_owner'])
            ->update([
                'is_active' => 1,
                'locked_reason' => null,
                'locked_at' => null,
                'locked_by' => null,
            ]);

        \Log::info("Admin " . auth()->id() . " đã mở khóa {$affected} tài khoản của nhà xe {$validated['ma_nha_xe']}");

        return redirect()->back()->with('success', "Đã mở khóa {$affected} tài khoản thành công!");
    }
}
