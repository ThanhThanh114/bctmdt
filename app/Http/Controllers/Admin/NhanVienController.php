<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\NhaXe;
use Illuminate\Http\Request;

class NhanVienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NhanVien::with('nhaXe');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_nv', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Lọc theo chức vụ
        if ($request->filled('chuc_vu') && $request->chuc_vu !== 'all') {
            $query->where('chuc_vu', $request->chuc_vu);
        }

        // Lọc theo nhà xe
        if ($request->filled('ma_nha_xe') && $request->ma_nha_xe !== 'all') {
            $query->where('ma_nha_xe', $request->ma_nha_xe);
        }

        $nhanViens = $query->paginate(15);
        $nhaXes = NhaXe::all();

        return view('AdminLTE.admin.nhan_vien.index', compact('nhanViens', 'nhaXes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nhaXes = NhaXe::all();
        return view('AdminLTE.admin.nhan_vien.create', compact('nhaXes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_nv' => 'required|string|max:100',
            'chuc_vu' => 'required|in:tài xế,phụ xe,nhân viên văn phòng,quản lý',
            'so_dien_thoai' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'ma_nha_xe' => 'required|exists:nha_xe,ma_nha_xe',
        ], [
            'ten_nv.required' => 'Vui lòng nhập tên nhân viên',
            'chuc_vu.required' => 'Vui lòng chọn chức vụ',
            'ma_nha_xe.required' => 'Vui lòng chọn nhà xe',
            'ma_nha_xe.exists' => 'Nhà xe không tồn tại',
        ]);

        NhanVien::create($validated);

        return redirect()->route('admin.nhanvien.index')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(NhanVien $nhanvien)
    {
        $nhanvien->load('nhaXe');
        return view('AdminLTE.admin.nhan_vien.show', compact('nhanvien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NhanVien $nhanvien)
    {
        $nhaXes = NhaXe::all();
        return view('AdminLTE.admin.nhan_vien.edit', compact('nhanvien', 'nhaXes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NhanVien $nhanvien)
    {
        $validated = $request->validate([
            'ten_nv' => 'required|string|max:100',
            'chuc_vu' => 'required|in:tài xế,phụ xe,nhân viên văn phòng,quản lý',
            'so_dien_thoai' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'ma_nha_xe' => 'required|exists:nha_xe,ma_nha_xe',
        ], [
            'ten_nv.required' => 'Vui lòng nhập tên nhân viên',
            'chuc_vu.required' => 'Vui lòng chọn chức vụ',
            'ma_nha_xe.required' => 'Vui lòng chọn nhà xe',
            'ma_nha_xe.exists' => 'Nhà xe không tồn tại',
        ]);

        $nhanvien->update($validated);

        return redirect()->route('admin.nhanvien.index')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NhanVien $nhanvien)
    {
        try {
            $nhanvien->delete();
            return redirect()->route('admin.nhanvien.index')
                ->with('success', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.nhanvien.index')
                ->with('error', 'Không thể xóa nhân viên này!');
        }
    }
}
