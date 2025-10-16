<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
            'role' => 'required|in:User,Staff,Bus_owner'
        ]);

        $user->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role
        ]);

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
}