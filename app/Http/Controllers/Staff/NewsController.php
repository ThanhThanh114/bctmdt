<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TinTuc::with(['user', 'nhaXe']);

        // Chỉ hiển thị tin tức của nhà xe của nhân viên
        $nhaXeId = auth()->user()->ma_nha_xe;
        $query->where('ma_nha_xe', $nhaXeId);

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('tieu_de', 'like', "%{$search}%");
        }

        // Lọc theo nhà xe
        if ($request->filled('nha_xe')) {
            $query->where('ma_nha_xe', $request->nha_xe);
        }

        $tinTuc = $query->orderBy('ngay_dang', 'desc')->paginate(15);
        $nhaXe = \App\Models\NhaXe::all();
        $tacGia = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();

        // Thống kê - chỉ của nhà xe hiện tại
        $stats = [
            'total' => TinTuc::where('ma_nha_xe', $nhaXeId)->count(),
            'today' => TinTuc::where('ma_nha_xe', $nhaXeId)->whereDate('ngay_dang', today())->count(),
            'this_month' => TinTuc::where('ma_nha_xe', $nhaXeId)->whereMonth('ngay_dang', date('m'))->whereYear('ngay_dang', date('Y'))->count(),
        ];

        return view('AdminLTE.staff.news.index', compact('tinTuc', 'nhaXe', 'tacGia', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Chỉ lấy nhà xe của nhân viên hiện tại
        $nhaXe = \App\Models\NhaXe::where('ma_nha_xe', auth()->user()->ma_nha_xe)->get();
        return view('AdminLTE.staff.news.create', compact('nhaXe'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
        ]);

        // Tự động gán nhà xe của nhân viên hiện tại
        $validated['ma_nha_xe'] = auth()->user()->ma_nha_xe;
        $validated['user_id'] = auth()->id();
        $validated['ngay_dang'] = now();

        // Handle image upload or URL
        if ($request->hasFile('hinh_anh')) {
            // Upload file từ máy
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/news'), $imageName);
            $validated['hinh_anh'] = 'assets/images/news/' . $imageName;
        } elseif ($request->filled('image_url')) {
            // Sử dụng URL ảnh
            $validated['hinh_anh'] = $request->image_url;
        }

        TinTuc::create($validated);

        return redirect()->route('staff.news.index')->with('success', 'Đã thêm tin tức mới!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tinTuc = TinTuc::with(['user', 'nhaXe'])->findOrFail($id);
        return view('AdminLTE.staff.news.show', compact('tinTuc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        // Kiểm tra quyền - chỉ chỉnh sửa tin tức của nhà xe mình
        if ($tinTuc->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền chỉnh sửa tin tức này.');
        }

        // Chỉ lấy nhà xe của nhân viên hiện tại
        $nhaXe = \App\Models\NhaXe::where('ma_nha_xe', auth()->user()->ma_nha_xe)->get();
        return view('AdminLTE.staff.news.edit', compact('tinTuc', 'nhaXe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        // Kiểm tra quyền - chỉ cập nhật tin tức của nhà xe mình
        if ($tinTuc->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền cập nhật tin tức này.');
        }

        $validated = $request->validate([
            'tieu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
        ]);

        // Đảm bảo không thể thay đổi nhà xe
        $validated['ma_nha_xe'] = auth()->user()->ma_nha_xe;

        // Handle image upload or URL
        if ($request->hasFile('hinh_anh')) {
            // Delete old image if exists and is a local file
            if ($tinTuc->hinh_anh && !filter_var($tinTuc->hinh_anh, FILTER_VALIDATE_URL) && file_exists(public_path($tinTuc->hinh_anh))) {
                unlink(public_path($tinTuc->hinh_anh));
            }

            // Upload file từ máy
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/news'), $imageName);
            $validated['hinh_anh'] = 'assets/images/news/' . $imageName;
        } elseif ($request->filled('image_url')) {
            // Delete old image if exists and is a local file
            if ($tinTuc->hinh_anh && !filter_var($tinTuc->hinh_anh, FILTER_VALIDATE_URL) && file_exists(public_path($tinTuc->hinh_anh))) {
                unlink(public_path($tinTuc->hinh_anh));
            }
            // Sử dụng URL ảnh
            $validated['hinh_anh'] = $request->image_url;
        }

        $tinTuc->update($validated);

        return redirect()->route('staff.news.index')->with('success', 'Đã cập nhật tin tức!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        // Kiểm tra quyền - chỉ xóa tin tức của nhà xe mình
        if ($tinTuc->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền xóa tin tức này.');
        }

        // Delete image if exists
        if ($tinTuc->hinh_anh && file_exists(public_path($tinTuc->hinh_anh))) {
            unlink(public_path($tinTuc->hinh_anh));
        }

        $tinTuc->delete();

        return redirect()->route('staff.news.index')->with('success', 'Đã xóa tin tức!');
    }
}
