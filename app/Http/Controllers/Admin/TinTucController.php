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
            'image_type' => 'required|in:file,url',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:500',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề',
            'noi_dung.required' => 'Vui lòng nhập nội dung',
            'image_type.required' => 'Vui lòng chọn loại hình ảnh',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'hinh_anh.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
            'image_url.url' => 'URL hình ảnh không hợp lệ',
        ]);

        // Xử lý hình ảnh theo loại
        if ($request->image_type === 'url' && $request->filled('image_url')) {
            // Sử dụng URL trực tiếp
            $validated['hinh_anh'] = $request->image_url;
        } elseif ($request->image_type === 'file' && $request->hasFile('hinh_anh')) {
            // Upload file từ máy
            $image = $request->file('hinh_anh');
            $imageName = 'news_' . Str::random(16) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/news'), $imageName);
            $validated['hinh_anh'] = '/assets/images/news/' . $imageName;
        } else {
            $validated['hinh_anh'] = null;
        }

        $validated['user_id'] = auth()->id();
        $validated['ngay_dang'] = now();

        // Loại bỏ field không cần thiết
        unset($validated['image_type']);
        unset($validated['image_url']);

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
            'image_type' => 'required|in:file,url',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:500',
            'ma_nha_xe' => 'nullable|exists:nha_xe,ma_nha_xe',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề',
            'noi_dung.required' => 'Vui lòng nhập nội dung',
            'image_type.required' => 'Vui lòng chọn loại hình ảnh',
            'hinh_anh.image' => 'File phải là hình ảnh',
            'hinh_anh.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'hinh_anh.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
            'image_url.url' => 'URL hình ảnh không hợp lệ',
        ]);

        // Xử lý hình ảnh theo loại
        if ($request->image_type === 'url' && $request->filled('image_url')) {
            // Xóa ảnh cũ nếu là file upload (không phải URL)
            if ($tintuc->hinh_anh && !filter_var($tintuc->hinh_anh, FILTER_VALIDATE_URL)) {
                $oldImagePath = public_path($tintuc->hinh_anh);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Sử dụng URL mới
            $validated['hinh_anh'] = $request->image_url;
        } elseif ($request->image_type === 'file' && $request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu là file upload (không phải URL)
            if ($tintuc->hinh_anh && !filter_var($tintuc->hinh_anh, FILTER_VALIDATE_URL)) {
                $oldImagePath = public_path($tintuc->hinh_anh);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Upload file mới
            $image = $request->file('hinh_anh');
            $imageName = 'news_' . Str::random(16) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/images/news'), $imageName);
            $validated['hinh_anh'] = '/assets/images/news/' . $imageName;
        }

        // Loại bỏ field không cần thiết
        unset($validated['image_type']);
        unset($validated['image_url']);

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