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

        // Filter by role if specified (similar to users)
        if ($request->filled('chuc_vu') && $request->chuc_vu !== 'all') {
            $query->where('chuc_vu', $request->chuc_vu);
        }

        // Filter by search term (similar to users)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_nv', 'like', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by bus company
        if ($request->filled('ma_nha_xe') && $request->ma_nha_xe !== 'all') {
            $query->where('ma_nha_xe', $request->ma_nha_xe);
        }

        $nhanViens = $query->paginate(10);
        $nhaXes = NhaXe::all();

        // Thống kê
        $stats = [
            'total' => NhanVien::count(),
            'tai_xe' => NhanVien::where('chuc_vu', 'tài xế')->count(),
            'phu_xe' => NhanVien::where('chuc_vu', 'phụ xe')->count(),
            'nhan_vien_van_phong' => NhanVien::where('chuc_vu', 'nhân viên văn phòng')->count(),
            'quan_ly' => NhanVien::where('chuc_vu', 'quản lý')->count(),
        ];

        return view('AdminLTE.admin.nhan_vien.index', compact('nhanViens', 'nhaXes', 'stats'));
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

    /**
     * Export employee data to CSV
     */
    public function export(Request $request)
        {
            $query = NhanVien::with('nhaXe');

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ten_nv', 'like', "%{$search}%")
                        ->orWhere('so_dien_thoai', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('chuc_vu') && $request->chuc_vu !== 'all') {
                $query->where('chuc_vu', $request->chuc_vu);
            }

            if ($request->filled('ma_nha_xe') && $request->ma_nha_xe !== 'all') {
                $query->where('ma_nha_xe', $request->ma_nha_xe);
            }

            $employees = $query->get();

            // Create CSV
            $filename = 'danh_sach_nhan_vien_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function () use ($employees) {
                $file = fopen('php://output', 'w');
                // Add BOM for Excel UTF-8
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Header
                fputcsv($file, [
                    'Tên nhân viên',
                    'Chức vụ',
                    'Số điện thoại',
                    'Email',
                    'Nhà xe',
                    'Ngày tạo'
                ]);

                // Data
                foreach ($employees as $employee) {
                    fputcsv($file, [
                        $employee->ten_nv,
                        $employee->chuc_vu,
                        $employee->so_dien_thoai,
                        $employee->email,
                        $employee->nhaXe ? $employee->nhaXe->ten_nha_xe : 'N/A',
                        $employee->created_at ? $employee->created_at->format('d/m/Y H:i') : 'N/A'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
