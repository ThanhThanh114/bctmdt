<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BinhLuan::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'parent', 'replies']);

        // Chỉ hiển thị bình luận của chuyến xe thuộc nhà xe của nhân viên
        $nhaXeId = auth()->user()->ma_nha_xe;
        if ($nhaXeId) {
            $query->whereHas('chuyenXe', function ($q) use ($nhaXeId) {
                $q->where('ma_nha_xe', $nhaXeId);
            });
        }

        // Chỉ hiển thị bình luận của người dùng, không hiển thị bình luận của admin/staff
        $query->whereHas('user', function ($q) {
            $q->where('role', 'User');
        });

        // Tìm kiếm
        if ($request->filled('user')) {
            $search = $request->user;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo ngày
        if ($request->filled('ngay_bl')) {
            $query->whereDate('ngay_bl', $request->ngay_bl);
        }

        // Chỉ lấy bình luận cha
        $query->whereNull('parent_id');

        $binhLuan = $query->orderBy('ngay_bl', 'desc')->paginate(15);

        // Thống kê - chỉ của nhà xe hiện tại và chỉ bình luận của người dùng
        $statsQuery = BinhLuan::whereHas('user', function ($q) {
            $q->where('role', 'User');
        })->whereNull('parent_id');

        if ($nhaXeId) {
            $statsQuery->whereHas('chuyenXe', function ($q) use ($nhaXeId) {
                $q->where('ma_nha_xe', $nhaXeId);
            });
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'cho_duyet' => (clone $statsQuery)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => (clone $statsQuery)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => (clone $statsQuery)->where('trang_thai', 'tu_choi')->count(),
        ];

        return view('AdminLTE.staff.comments.index', compact('binhLuan', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment = BinhLuan::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'parent', 'replies.user'])
            ->findOrFail($id);

        // Load all replies for this comment thread (only replies, not the original comment)
        $allReplies = BinhLuan::where('parent_id', $comment->ma_bl)
            ->with('user')
            ->orderBy('ngay_bl', 'asc')
            ->get();

        return view('AdminLTE.staff.comments.show', compact('comment', 'allReplies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $comment = BinhLuan::findOrFail($id);
        return view('AdminLTE.staff.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = BinhLuan::findOrFail($id);


        $validated = $request->validate([
            'trang_thai' => 'required|in:cho_duyet,da_duyet,tu_choi',
            'ly_do_tu_choi' => 'nullable|string'
        ]);

        $comment->update($validated);

        return redirect()->route('staff.comments.index')->with('success', 'Đã cập nhật bình luận!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = BinhLuan::findOrFail($id);

        $comment->delete();

        return redirect()->route('staff.comments.index')->with('success', 'Đã xóa bình luận!');
    }

    /**
     * Reply to a comment
     */
    public function reply(Request $request, $id)
    {
        $parentComment = BinhLuan::findOrFail($id);


        $validated = $request->validate([
            'noi_dung_tl' => 'required|string'
        ]);

        BinhLuan::create([
            'parent_id' => $parentComment->ma_bl,
            'user_id' => auth()->id(),
            'chuyen_xe_id' => $parentComment->chuyen_xe_id,
            'noi_dung' => $validated['noi_dung_tl'], // Use noi_dung for staff replies
            'noi_dung_tl' => '', // Keep noi_dung_tl empty for staff replies
            'trang_thai' => 'da_duyet',
            'ngay_tl' => now(),
            'ngay_bl' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã gửi trả lời!');
    }
}
