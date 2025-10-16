<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use App\Models\NhaXe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TinTucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TinTuc::with(['user', 'nhaXe']);

        // Tìm kiếm theo tiêu đề
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                    ->orWhere('noi_dung', 'like', "%{$search}%");
            });
        }

        // Lọc theo nhà xe
        if ($request->filled('ma_nha_xe') && $request->ma_nha_xe !== 'all') {
            $query->where('ma_nha_xe', $request->ma_nha_xe);
        }

        // Lọc theo người đăng
        if ($request->filled('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        $tinTuc = $query->orderBy('ngay_dang', 'desc')->paginate(15);
        $nhaXe = NhaXe::all();
        $tacGia = User::where('role', 'admin')->orWhere('role', 'staff')->get();

        // Thống kê
        $stats = [
            'total' => TinTuc::count(),
            'today' => TinTuc::whereDate('ngay_dang', today())->count(),
            'this_month' => TinTuc::whereMonth('ngay_dang', date('m'))
                ->whereYear('ngay_dang', date('Y'))
                ->count(),
        ];

        return view('AdminLTE.admin.tin_tuc.index', compact('tinTuc', 'nhaXe', 'tacGia', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nhaXe = NhaXe::all();
        return view('AdminLTE.admin.tin_tuc.create', compact('nhaXe'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề',
            'noi_dung.required' => 'Vui lòng nhập nội dung',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'hinh_anh.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ]);

        // Xử lý upload hình ảnh
        if ($request->hasFile('hinh_anh')) {
            $image = $request->file('hinh_anh');
            $imageName = 'tin_' . Str::random(16) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/image'), $imageName);
            $validated['hinh_anh'] = $imageName;
        }

        $validated['user_id'] = auth()->id();
        $validated['ngay_dang'] = now();

        TinTuc::create($validated);

        return redirect()->route('admin.tintuc.index')
            ->with('success', 'Thêm tin tức thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TinTuc $tintuc)
    {
        $tintuc->load(['user', 'nhaXe']);
        $tinTuc = $tintuc;
        return view('AdminLTE.admin.tin_tuc.show', compact('tinTuc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TinTuc $tintuc)
    {
        $nhaXe = NhaXe::all();
        $tinTuc = $tintuc;
        return view('AdminLTE.admin.tin_tuc.edit', compact('tinTuc', 'nhaXe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TinTuc $tintuc)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề',
            'noi_dung.required' => 'Vui lòng nhập nội dung',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'hinh_anh.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ]);

        // Xử lý upload hình ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // Xóa hình ảnh cũ nếu có
            if ($tintuc->hinh_anh && file_exists(public_path('assets/image/' . $tintuc->hinh_anh))) {
                unlink(public_path('assets/image/' . $tintuc->hinh_anh));
            }

            $image = $request->file('hinh_anh');
            $imageName = 'tin_' . Str::random(16) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/image'), $imageName);
            $validated['hinh_anh'] = $imageName;
        }

        $tintuc->update($validated);

        return redirect()->route('admin.tintuc.index')
            ->with('success', 'Cập nhật tin tức thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TinTuc $tintuc)
    {
        try {
            // Xóa hình ảnh nếu có
            if ($tintuc->hinh_anh && file_exists(public_path('assets/image/' . $tintuc->hinh_anh))) {
                unlink(public_path('assets/image/' . $tintuc->hinh_anh));
            }

            $tintuc->delete();

            return redirect()->route('admin.tintuc.index')
                ->with('success', 'Xóa tin tức thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.tintuc.index')
                ->with('error', 'Không thể xóa tin tức này!');
        }
    }

    /**
     * Bulk delete news
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'news_ids' => 'required|array',
            'news_ids.*' => 'exists:tin_tuc,ma_tin',
        ]);

        $news = TinTuc::whereIn('ma_tin', $validated['news_ids'])->get();

        foreach ($news as $item) {
            // Xóa hình ảnh nếu có
            if ($item->hinh_anh && file_exists(public_path('assets/image/' . $item->hinh_anh))) {
                unlink(public_path('assets/image/' . $item->hinh_anh));
            }
            $item->delete();
        }

        return redirect()->back()->with('success', 'Đã xóa ' . count($validated['news_ids']) . ' tin tức!');
    }

    /**
     * Pin/Unpin news (feature for highlighting important news)
     */
    public function togglePin(TinTuc $tintuc)
    {
        // TODO: Add 'is_pinned' column to tin_tuc table
        return redirect()->back()->with('info', 'Chức năng đang được phát triển!');
    }

    /**
     * Publish/Unpublish news
     */
    public function togglePublish(TinTuc $tintuc)
    {
        // TODO: Add 'is_published' column to tin_tuc table
        return redirect()->back()->with('info', 'Chức năng đang được phát triển!');
    }
}