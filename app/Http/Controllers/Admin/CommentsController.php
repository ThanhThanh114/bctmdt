<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\ChuyenXe;
use App\Models\User;
use App\Helpers\ProfanityFilter;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BinhLuan::with([
            'user',
            'chuyenXe.tramDi',
            'chuyenXe.tramDen',
            'parent',
            'replies'
        ]);

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('noi_dung', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('fullname', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai') && $request->trang_thai !== 'all') {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo số sao
        if ($request->filled('so_sao') && $request->so_sao !== 'all') {
            $query->where('so_sao', $request->so_sao);
        }

        // Chỉ lấy bình luận cha (không phải trả lời)
        if (!$request->filled('show_replies')) {
            $query->whereNull('parent_id');
        }

        $comments = $query->orderBy('ngay_bl', 'desc')->paginate(15);

        // Thống kê
        $stats = [
            'total' => BinhLuan::count(),
            'cho_duyet' => BinhLuan::where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => BinhLuan::where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => BinhLuan::where('trang_thai', 'tu_choi')->count(),
        ];

        return view('AdminLTE.admin.comments.index', compact('comments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy danh sách người dùng để chọn
        $users = User::orderBy('fullname')->get();

        // Lấy danh sách chuyến xe (tất cả, không giới hạn ngày)
        // Admin có thể thêm bình luận cho cả chuyến xe đã qua
        $chuyenXe = ChuyenXe::with(['tramDi', 'tramDen'])
            ->orderBy('ngay_di', 'desc') // Mới nhất lên đầu
            ->get();

        return view('AdminLTE.admin.comments.create', compact('users', 'chuyenXe'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chuyen_xe_id' => 'required|exists:chuyen_xe,id',
            'noi_dung' => 'required|string|max:1000',
            'so_sao' => 'required|integer|min:1|max:5',
        ], [
            'user_id.required' => 'Vui lòng chọn người dùng',
            'user_id.exists' => 'Người dùng không tồn tại',
            'chuyen_xe_id.required' => 'Vui lòng chọn chuyến xe',
            'chuyen_xe_id.exists' => 'Chuyến xe không tồn tại',
            'noi_dung.required' => 'Vui lòng nhập nội dung bình luận',
            'noi_dung.max' => 'Nội dung không được vượt quá 1000 ký tự',
            'so_sao.required' => 'Vui lòng chọn số sao đánh giá',
            'so_sao.min' => 'Số sao phải từ 1 đến 5',
            'so_sao.max' => 'Số sao phải từ 1 đến 5',
        ]);

        // Filter profanity
        $noiDung = ProfanityFilter::filter($validated['noi_dung']);

        // Auto set trạng thái dựa vào số sao:
        // <= 2 sao: Chờ duyệt (cần review)
        // >= 3 sao: Đã duyệt (tự động approve)
        $trangThai = $validated['so_sao'] >= 3 ? 'da_duyet' : 'cho_duyet';

        // Create comment
        $binhLuan = BinhLuan::create([
            'parent_id' => null,
            'user_id' => $validated['user_id'],
            'chuyen_xe_id' => $validated['chuyen_xe_id'],
            'noi_dung' => $noiDung,
            'noi_dung_tl' => '',
            'so_sao' => $validated['so_sao'],
            'trang_thai' => $trangThai,
            'ngay_bl' => now(),
            'ngay_tl' => now(),
            'nv_id' => null,
            'ngay_tao' => now(),
            'ngay_duyet' => $trangThai === 'da_duyet' ? now() : null,
            'ly_do_tu_choi' => null,
        ]);

        $message = $trangThai === 'da_duyet'
            ? 'Thêm bình luận thành công! (Tự động duyệt vì đánh giá ≥ 3 sao)'
            : 'Thêm bình luận thành công! (Chờ duyệt vì đánh giá ≤ 2 sao)';

        return redirect()->route('admin.comments.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(BinhLuan $comment)
    {
        $comment->load(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'parent.user', 'replies.user']);
        return view('AdminLTE.admin.comments.show', compact('comment'));
    }

    /**
     * Store reply to comment (Admin reply to customer)
     */
    public function reply(Request $request, BinhLuan $comment)
    {
        $validated = $request->validate([
            'noi_dung' => 'required|string|max:1000',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung trả lời',
            'noi_dung.max' => 'Nội dung không được vượt quá 1000 ký tự',
        ]);

        // Filter profanity
        $noiDung = ProfanityFilter::filter($validated['noi_dung']);

        // Create reply
        $reply = BinhLuan::create([
            'parent_id' => $comment->ma_bl,
            'user_id' => auth()->id(),
            'chuyen_xe_id' => $comment->chuyen_xe_id,
            'noi_dung' => $noiDung,
            'noi_dung_tl' => '',
            'so_sao' => null, // Replies don't have rating, use NULL instead of 0
            'trang_thai' => 'da_duyet', // Admin replies are auto-approved
            'ngay_bl' => now(),
            'ngay_tl' => now(),
            'nv_id' => null, // Set to null since admin users are not in nhan_vien table
            'ngay_tao' => now(),
            'ngay_duyet' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã gửi trả lời thành công!');
    }

    /**
     * Approve comment
     */
    public function approve(BinhLuan $binhluan)
    {
        $binhluan->update([
            'trang_thai' => 'da_duyet',
            'ngay_duyet' => now(),
            'ly_do_tu_choi' => null,
            'nv_id' => null, // Set to null for admin actions since they're not from employees
        ]);

        return redirect()->back()
            ->with('success', 'Đã duyệt bình luận thành công!');
    }

    /**
     * Reject comment
     */
    public function reject(Request $request, BinhLuan $binhluan)
    {
        $validated = $request->validate([
            'ly_do_tu_choi' => 'required|string|max:255',
        ], [
            'ly_do_tu_choi.required' => 'Vui lòng nhập lý do từ chối',
        ]);

        $binhluan->update([
            'trang_thai' => 'tu_choi',
            'ly_do_tu_choi' => $validated['ly_do_tu_choi'],
        ]);

        return redirect()->back()
            ->with('success', 'Đã từ chối bình luận!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BinhLuan $binhluan)
    {
        try {
            // Xóa các bình luận trả lời trước
            $binhluan->replies()->delete();

            // Xóa bình luận chính
            $binhluan->delete();

            return redirect()->back()
                ->with('success', 'Xóa bình luận thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa bình luận này!');
        }
    }

    /**
     * Toggle lock/unlock comment (Khóa/Mở khóa bình luận)
     */
    public function toggleLock(BinhLuan $binhluan)
    {
        // Nếu đang là tu_choi (khóa), chuyển về cho_duyet
        // Nếu không, chuyển thành tu_choi (khóa)
        $newStatus = $binhluan->trang_thai === 'tu_choi' ? 'cho_duyet' : 'tu_choi';
        $message = $newStatus === 'tu_choi' ? 'Đã khóa bình luận!' : 'Đã mở khóa bình luận!';

        $binhluan->update([
            'trang_thai' => $newStatus,
            'ly_do_tu_choi' => $newStatus === 'tu_choi' ? 'Bình luận bị khóa bởi Admin' : null,
        ]);

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk approve comments
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:binh_luan,ma_bl',
        ]);

        BinhLuan::whereIn('ma_bl', $validated['comment_ids'])
            ->update([
                'trang_thai' => 'da_duyet',
                'ngay_duyet' => now(),
                'ly_do_tu_choi' => null,
            ]);

        return redirect()->back()->with('success', 'Đã duyệt ' . count($validated['comment_ids']) . ' bình luận!');
    }

    /**
     * Bulk delete comments
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:binh_luan,ma_bl',
        ]);

        BinhLuan::whereIn('ma_bl', $validated['comment_ids'])->delete();

        return redirect()->back()->with('success', 'Đã xóa ' . count($validated['comment_ids']) . ' bình luận!');
    }

    /**
     * Statistics page
     */
    public function statistics()
    {
        $stats = [
            'total_comments' => BinhLuan::count(),
            'pending_comments' => BinhLuan::where('trang_thai', 'cho_duyet')->count(),
            'approved_comments' => BinhLuan::where('trang_thai', 'da_duyet')->count(),
            'rejected_comments' => BinhLuan::where('trang_thai', 'tu_choi')->count(),
            'avg_rating' => BinhLuan::where('trang_thai', 'da_duyet')->avg('so_sao'),
            'rating_distribution' => [
                5 => BinhLuan::where('so_sao', 5)->where('trang_thai', 'da_duyet')->count(),
                4 => BinhLuan::where('so_sao', 4)->where('trang_thai', 'da_duyet')->count(),
                3 => BinhLuan::where('so_sao', 3)->where('trang_thai', 'da_duyet')->count(),
                2 => BinhLuan::where('so_sao', 2)->where('trang_thai', 'da_duyet')->count(),
                1 => BinhLuan::where('so_sao', 1)->where('trang_thai', 'da_duyet')->count(),
            ],
        ];

        return view('AdminLTE.admin.comments.statistics', compact('stats'));
    }
}
