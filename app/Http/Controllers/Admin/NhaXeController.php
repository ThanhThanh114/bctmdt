<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NhaXeController extends Controller
{
    /**
     * Hiển thị danh sách nhà xe (với tìm kiếm và phân trang)
     */
    public function index(Request $request)
    {
        $query = NhaXe::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_nha_xe', 'LIKE', "%{$search}%")
                    ->orWhere('dia_chi', 'LIKE', "%{$search}%")
                    ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('ma_nha_xe', 'LIKE', "%{$search}%");
            });
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'ma_nha_xe');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $nhaXes = $query->paginate($perPage)->withQueryString();

        // Thống kê
        $statistics = [
            'total' => NhaXe::count(),
            'total_trips' => DB::table('chuyen_xe')->count(),
            'total_bookings' => DB::table('dat_ve')->where('trang_thai', 'Đã thanh toán')->count(),
        ];

        return view('AdminLTE.admin.nha_xe.index', compact('nhaXes', 'statistics'));
    }

    /**
     * Hiển thị form tạo nhà xe mới
     */
    public function create()
    {
        return view('AdminLTE.admin.nha_xe.create');
    }

    /**
     * Lưu nhà xe mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_nha_xe' => 'required|string|max:100',
            'dia_chi' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
        ], [
            'ten_nha_xe.required' => 'Tên nhà xe là bắt buộc',
            'ten_nha_xe.max' => 'Tên nhà xe không được quá 100 ký tự',
            'dia_chi.required' => 'Địa chỉ là bắt buộc',
            'dia_chi.max' => 'Địa chỉ không được quá 255 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc',
            'so_dien_thoai.max' => 'Số điện thoại không được quá 15 ký tự',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được quá 100 ký tự',
        ]);

        try {
            NhaXe::create($validated);
            return redirect()->route('admin.nha-xe.index')
                ->with('success', 'Thêm nhà xe mới thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết một nhà xe
     */
    public function show($id)
    {
        $nhaXe = NhaXe::with(['chuyenXe', 'tramXe', 'nhanVien'])->findOrFail($id);

        // Thống kê chi tiết
        $statistics = [
            'total_trips' => $nhaXe->chuyenXe()->count(),
            'active_trips' => $nhaXe->chuyenXe()->where('ngay_di', '>=', now())->count(),
            'total_stations' => $nhaXe->tramXe()->count(),
            'total_employees' => $nhaXe->nhanVien()->count(),
            'total_revenue' => DB::table('dat_ve')
                ->join('chuyen_xe', 'dat_ve.chuyen_xe_id', '=', 'chuyen_xe.id')
                ->where('chuyen_xe.ma_nha_xe', $id)
                ->where('dat_ve.trang_thai', 'Đã thanh toán')
                ->sum(DB::raw('chuyen_xe.gia_ve * (LENGTH(dat_ve.so_ghe) - LENGTH(REPLACE(dat_ve.so_ghe, ",", "")) + 1)')),
        ];

        return view('AdminLTE.admin.nha_xe.show', compact('nhaXe', 'statistics'));
    }

    /**
     * Hiển thị form chỉnh sửa nhà xe
     */
    public function edit($id)
    {
        $nhaXe = NhaXe::findOrFail($id);
        return view('AdminLTE.admin.nha_xe.edit', compact('nhaXe'));
    }

    /**
     * Cập nhật thông tin nhà xe
     */
    public function update(Request $request, $id)
    {
        $nhaXe = NhaXe::findOrFail($id);

        $validated = $request->validate([
            'ten_nha_xe' => 'required|string|max:100',
            'dia_chi' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
        ], [
            'ten_nha_xe.required' => 'Tên nhà xe là bắt buộc',
            'ten_nha_xe.max' => 'Tên nhà xe không được quá 100 ký tự',
            'dia_chi.required' => 'Địa chỉ là bắt buộc',
            'dia_chi.max' => 'Địa chỉ không được quá 255 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc',
            'so_dien_thoai.max' => 'Số điện thoại không được quá 15 ký tự',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được quá 100 ký tự',
        ]);

        try {
            $nhaXe->update($validated);
            return redirect()->route('admin.nha-xe.show', $id)
                ->with('success', 'Cập nhật thông tin nhà xe thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhà xe
     */
    public function destroy($id)
    {
        try {
            $nhaXe = NhaXe::findOrFail($id);

            // Kiểm tra xem nhà xe có dữ liệu liên quan không
            if ($nhaXe->chuyenXe()->count() > 0) {
                return redirect()->back()
                    ->with('warning', 'Không thể xóa nhà xe này vì còn chuyến xe liên quan!');
            }

            $nhaXe->delete();
            return redirect()->route('admin.nha-xe.index')
                ->with('success', 'Xóa nhà xe thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
