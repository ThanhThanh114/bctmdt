<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UpgradeRequest;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role if specified
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Exclude admin users from regular listing
        $query->where('role', '!=', 'Admin');

        $users = $query->paginate(10);

        return view('AdminLTE.admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Don't allow viewing admin users unless it's the current admin
        if (strtolower($user->role) === 'admin' && $user->id !== auth()->id()) {
            abort(403, 'Không thể xem thông tin admin khác.');
        }

        // Get all bookings for statistics
        $allBookings = $user->datVe()->with('chuyenXe')->get();

        // Calculate statistics
        $stats = [
            'total' => $allBookings->count(),
            'confirmed' => $allBookings->where('trang_thai', 'Đã thanh toán')->count(),
            'pending' => $allBookings->where('trang_thai', 'Đã đặt')->count(),
            'cancelled' => $allBookings->where('trang_thai', 'Đã hủy')->count(),
            'total_spent' => $allBookings->where('trang_thai', 'Đã thanh toán')
                ->sum(function ($booking) {
                    return $booking->chuyenXe->gia_ve ?? 0;
                })
        ];

        $recent_bookings = $user->datVe()
            ->with(['chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe'])
            ->orderBy('ngay_dat', 'desc')
            ->limit(5)
            ->get();

        return view('AdminLTE.admin.users.show', compact('user', 'recent_bookings', 'stats'));
    }

    public function edit(User $user)
    {
        // Don't allow editing admin users unless it's the current admin
        if (strtolower($user->role) === 'admin' && $user->id !== auth()->id()) {
            abort(403, 'Không thể chỉnh sửa thông tin admin khác.');
        }

        return view('AdminLTE.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Don't allow editing admin users unless it's the current admin
        if (strtolower($user->role) === 'admin' && $user->id !== auth()->id()) {
            abort(403, 'Không thể chỉnh sửa thông tin admin khác.');
        }

        $request->validate([
            'fullname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'role' => 'required|in:User,Staff,Bus_owner',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe'
        ]);

        $updateData = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        // Only set ma_nha_xe if role is Bus_owner
        if ($request->role === 'Bus_owner') {
            $updateData['ma_nha_xe'] = $request->ma_nha_xe;
        } else {
            $updateData['ma_nha_xe'] = null;
        }

        $user->update($updateData);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    public function destroy(User $user)
    {
        // Don't allow deleting admin users
        if (strtolower($user->role) === 'admin') {
            abort(403, 'Không thể xóa tài khoản admin.');
        }

        // Check if user has bookings
        if ($user->datVe()->count() > 0) {
            return back()->with('error', 'Không thể xóa người dùng này vì còn lịch sử đặt vé.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công.');
    }

    // Quản lý yêu cầu nâng cấp
    public function upgradeRequests(Request $request)
    {
        $query = UpgradeRequest::with(['user', 'payment']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by user info
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $upgradeRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('AdminLTE.admin.users.upgrade_requests', compact('upgradeRequests'));
    }

    // Xem chi tiết yêu cầu nâng cấp
    public function showUpgradeRequest(UpgradeRequest $upgradeRequest)
    {
        $upgradeRequest->load(['user', 'payment', 'approver']);
        $busCompanies = NhaXe::orderBy('ten_nha_xe')->get();

        return view('AdminLTE.admin.users.upgrade_request_detail', compact('upgradeRequest', 'busCompanies'));
    }

    // Phê duyệt yêu cầu nâng cấp
    public function approveUpgrade(Request $request, UpgradeRequest $upgradeRequest)
    {
        // Kiểm tra trạng thái
        if ($upgradeRequest->status !== 'paid') {
            return back()->with('error', 'Chỉ có thể duyệt yêu cầu đã thanh toán.');
        }

        $request->validate([
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        // Lấy mã nhà xe từ business_info nếu không có trong request
        $maNhaXe = $request->ma_nha_xe;
        if (!$maNhaXe && isset($upgradeRequest->business_info['ma_nha_xe'])) {
            $maNhaXe = $upgradeRequest->business_info['ma_nha_xe'];
        }

        // Cập nhật role user
        $user = $upgradeRequest->user;
        $user->update([
            'role' => 'Bus_owner',
            'ma_nha_xe' => $maNhaXe,
        ]);

        // Cập nhật trạng thái yêu cầu
        $upgradeRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->route('admin.users.upgrade-requests')
            ->with('success', 'Đã phê duyệt yêu cầu nâng cấp. Tài khoản đã được nâng lên Nhà xe.');
    }

    // Từ chối yêu cầu nâng cấp
    public function rejectUpgrade(Request $request, UpgradeRequest $upgradeRequest)
    {
        $request->validate([
            'admin_note' => 'required|string|min:10|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Xóa nhà xe đã tạo tự động (nếu có và chưa được sử dụng)
            if (isset($upgradeRequest->business_info['ma_nha_xe'])) {
                $maNhaXe = $upgradeRequest->business_info['ma_nha_xe'];
                $nhaXe = NhaXe::find($maNhaXe);
                
                if ($nhaXe) {
                    // Chỉ xóa nếu nhà xe chưa có dữ liệu liên quan
                    $hasRelatedData = $nhaXe->chuyenXe()->exists() 
                        || $nhaXe->nhanVien()->exists() 
                        || $nhaXe->users()->exists();
                    
                    if (!$hasRelatedData) {
                        $nhaXe->delete();
                    }
                }
            }

            $upgradeRequest->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'admin_note' => $request->admin_note,
            ]);

            // Hoàn tiền (nếu đã thanh toán)
            if ($upgradeRequest->payment && $upgradeRequest->payment->status === 'completed') {
                $upgradeRequest->payment->update(['status' => 'refunded']);
            }

            DB::commit();

            return redirect()->route('admin.users.upgrade-requests')
                ->with('success', 'Đã từ chối yêu cầu nâng cấp.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi khi từ chối yêu cầu: ' . $e->getMessage());
        }
    }

    // Gán nhà xe cho Bus_owner
    public function assignBusCompany(Request $request, User $user)
    {
        if (!$user->isBusOwner()) {
            return back()->with('error', 'Chỉ có thể gán nhà xe cho tài khoản Nhà xe.');
        }

        $request->validate([
            'ma_nha_xe' => 'required|exists:nha_xe,ma_nha_xe',
        ]);

        $user->update(['ma_nha_xe' => $request->ma_nha_xe]);

        return back()->with('success', 'Đã gán nhà xe thành công.');
    }
}