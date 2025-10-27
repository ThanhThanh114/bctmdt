<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NhanVienController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        // Get the authenticated user's bus company
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào. Vui lòng liên hệ admin.');
        }

        // Query employees for this bus company
        $query = NhanVien::where('ma_nha_xe', $nhaXe->ma_nha_xe);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_nv', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%")
                    ->orWhere('chuc_vu', 'like', "%{$search}%");
            });
        }

        // Filter by position
        if ($request->filled('chuc_vu')) {
            $query->where('chuc_vu', $request->chuc_vu);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'ma_nv');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $nhanViens = $query->paginate($perPage)->appends($request->except('page'));

        // Statistics
        $statistics = [
            'total' => NhanVien::where('ma_nha_xe', $nhaXe->ma_nha_xe)->count(),
            'tai_xe' => NhanVien::where('ma_nha_xe', $nhaXe->ma_nha_xe)->where('chuc_vu', 'Tài xế')->count(),
            'pho_xe' => NhanVien::where('ma_nha_xe', $nhaXe->ma_nha_xe)->where('chuc_vu', 'Phụ xe')->count(),
            'quan_ly' => NhanVien::where('ma_nha_xe', $nhaXe->ma_nha_xe)->where('chuc_vu', 'Quản lý')->count(),
        ];

        // Get all unique positions for filter
        $positions = NhanVien::where('ma_nha_xe', $nhaXe->ma_nha_xe)
            ->select('chuc_vu')
            ->distinct()
            ->pluck('chuc_vu');

        return view('AdminLTE.bus_owner.nhan_vien.index', compact('nhanViens', 'nhaXe', 'statistics', 'positions'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        return view('AdminLTE.bus_owner.nhan_vien.create', compact('nhaXe'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('error', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $validated = $request->validate([
            'ten_nv' => 'required|string|max:255',
            'chuc_vu' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20|unique:nhan_vien,so_dien_thoai',
            'email' => 'required|email|max:255|unique:nhan_vien,email|unique:users,email',
            'ngay_sinh' => 'nullable|date|before:today',
            'gioi_tinh' => 'nullable|in:Nam,Nữ,Khác',
            'cccd' => 'nullable|string|max:20|unique:nhan_vien,cccd',
            'dia_chi' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'ten_nv.required' => 'Vui lòng nhập tên nhân viên',
            'chuc_vu.required' => 'Vui lòng chọn chức vụ',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'so_dien_thoai.unique' => 'Số điện thoại này đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng',
            'cccd.unique' => 'Số CCCD này đã được sử dụng',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Create employee record
        $nhanVien = NhanVien::create([
            'ten_nv' => $validated['ten_nv'],
            'chuc_vu' => $validated['chuc_vu'],
            'so_dien_thoai' => $validated['so_dien_thoai'],
            'email' => $validated['email'],
            'ngay_sinh' => $validated['ngay_sinh'] ?? null,
            'gioi_tinh' => $validated['gioi_tinh'] ?? null,
            'cccd' => $validated['cccd'] ?? null,
            'dia_chi' => $validated['dia_chi'] ?? null,
            'ma_nha_xe' => $nhaXe->ma_nha_xe,
        ]);

        // Nếu chức vụ là "Quản lý" và có mật khẩu, tạo tài khoản User
        if ($validated['chuc_vu'] === 'Quản lý' && !empty($validated['password'])) {
            \App\Models\User::create([
                'username' => $validated['email'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'fullname' => $validated['ten_nv'],
                'phone' => $validated['so_dien_thoai'],
                'role' => 'staff',
                'ma_nha_xe' => $nhaXe->ma_nha_xe,
            ]);
        }

        return redirect()->route('bus-owner.nhan-vien.index')
            ->with('success', 'Thêm nhân viên thành công!' . 
                ($validated['chuc_vu'] === 'Quản lý' && !empty($validated['password']) ? 
                ' Tài khoản đăng nhập đã được tạo.' : ''));
    }

    /**
     * Display the specified employee
     */
    public function show($id)
    {
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('error', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $nhanVien = NhanVien::where('ma_nv', $id)
            ->where('ma_nha_xe', $nhaXe->ma_nha_xe)
            ->firstOrFail();

        // Additional statistics for this employee
        $statistics = [
            'trips_count' => 0, // You can add logic to count trips if you have that relationship
            'years_of_service' => 0, // Calculate if you have hire_date field
        ];

        return view('AdminLTE.bus_owner.nhan_vien.show', compact('nhanVien', 'nhaXe', 'statistics'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit($id)
    {
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('error', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $nhanVien = NhanVien::where('ma_nv', $id)
            ->where('ma_nha_xe', $nhaXe->ma_nha_xe)
            ->firstOrFail();

        return view('AdminLTE.bus_owner.nhan_vien.edit', compact('nhanVien', 'nhaXe'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('error', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $nhanVien = NhanVien::where('ma_nv', $id)
            ->where('ma_nha_xe', $nhaXe->ma_nha_xe)
            ->firstOrFail();

        $validated = $request->validate([
            'ten_nv' => 'required|string|max:255',
            'chuc_vu' => 'required|string|max:100',
            'so_dien_thoai' => [
                'required',
                'string',
                'max:20',
                Rule::unique('nhan_vien', 'so_dien_thoai')->ignore($nhanVien->ma_nv, 'ma_nv')
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('nhan_vien', 'email')->ignore($nhanVien->ma_nv, 'ma_nv')
            ],
        ], [
            'ten_nv.required' => 'Vui lòng nhập tên nhân viên',
            'chuc_vu.required' => 'Vui lòng chọn chức vụ',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'so_dien_thoai.unique' => 'Số điện thoại này đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng',
        ]);

        $nhanVien->update($validated);

        return redirect()->route('bus-owner.nhan-vien.show', $nhanVien->ma_nv)
            ->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }

    /**
     * Remove the specified employee
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $nhaXe = $user->nhaXe;

        if (!$nhaXe) {
            return redirect()->route('bus-owner.dashboard')
                ->with('error', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $nhanVien = NhanVien::where('ma_nv', $id)
            ->where('ma_nha_xe', $nhaXe->ma_nha_xe)
            ->firstOrFail();

        // Check if employee is assigned to any trips (if you have that relationship)
        // Uncomment and modify if needed:
        // if ($nhanVien->chuyenXe()->count() > 0) {
        //     return redirect()->route('bus-owner.nhan-vien.index')
        //         ->with('error', 'Không thể xóa nhân viên đang được phân công cho chuyến xe!');
        // }

        $nhanVien->delete();

        return redirect()->route('bus-owner.nhan-vien.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }
}
