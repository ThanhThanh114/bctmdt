<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\ChuyenXe;
use App\Models\DatVe;
use App\Helpers\ProfanityFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    /**
     * Hiển thị danh sách bình luận của một chuyến xe
     */
    public function index(Request $request)
    {
        $chuyenXeId = $request->get('chuyen_xe_id');
        
        if (!$chuyenXeId) {
            return redirect()->route('user.bookings.index')
                ->with('error', 'Không tìm thấy thông tin chuyến xe!');
        }

        $chuyenXe = ChuyenXe::with(['tramDi', 'tramDen', 'nhaXe'])->findOrFail($chuyenXeId);

        // Kiểm tra xem user có vé cho chuyến xe này không
        $hasBooking = DatVe::where('user_id', Auth::id())
            ->where('chuyen_xe_id', $chuyenXeId)
            ->whereIn('trang_thai', ['Đã xác nhận', 'Đã thanh toán'])
            ->exists();

        // Kiểm tra xem user đã bình luận chưa
        $userComment = BinhLuan::where('user_id', Auth::id())
            ->where('chuyen_xe_id', $chuyenXeId)
            ->whereNull('parent_id')
            ->first();

        // Lấy tất cả bình luận đã duyệt (chỉ bình luận cha)
        // Load tất cả replies (từ user và staff/admin)
        $binhLuan = BinhLuan::with([
                'user', 
                'replies' => function($query) {
                    // Load tất cả replies đã duyệt, sắp xếp theo thời gian
                    $query->where('trang_thai', 'da_duyet')->orderBy('ngay_bl', 'asc');
                },
                'replies.user' // Load thông tin user của mỗi reply
            ])
            ->where('chuyen_xe_id', $chuyenXeId)
            ->where('trang_thai', 'da_duyet')
            ->whereNull('parent_id')
            ->orderBy('ngay_bl', 'desc')
            ->paginate(10);

        // Tính điểm đánh giá trung bình
        $avgRating = BinhLuan::where('chuyen_xe_id', $chuyenXeId)
            ->where('trang_thai', 'da_duyet')
            ->whereNull('parent_id')
            ->whereNotNull('so_sao')
            ->avg('so_sao');

        $totalReviews = BinhLuan::where('chuyen_xe_id', $chuyenXeId)
            ->where('trang_thai', 'da_duyet')
            ->whereNull('parent_id')
            ->whereNotNull('so_sao')
            ->count();

        return view('user.binh_luan.index', compact(
            'chuyenXe', 
            'binhLuan', 
            'hasBooking', 
            'userComment',
            'avgRating',
            'totalReviews'
        ));
    }

    /**
     * Tạo bình luận mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chuyen_xe_id' => 'required|exists:chuyen_xe,id',
            'noi_dung' => 'required|string|max:1000',
            'so_sao' => 'required|integer|min:1|max:5',
        ], [
            'chuyen_xe_id.required' => 'Không tìm thấy chuyến xe',
            'noi_dung.required' => 'Vui lòng nhập nội dung bình luận',
            'noi_dung.max' => 'Nội dung không được vượt quá 1000 ký tự',
            'so_sao.required' => 'Vui lòng chọn số sao đánh giá',
            'so_sao.min' => 'Số sao phải từ 1 đến 5',
            'so_sao.max' => 'Số sao phải từ 1 đến 5',
        ]);

        // Kiểm tra user có vé cho chuyến xe này không
        $hasBooking = DatVe::where('user_id', Auth::id())
            ->where('chuyen_xe_id', $validated['chuyen_xe_id'])
            ->whereIn('trang_thai', ['Đã xác nhận', 'Đã thanh toán'])
            ->exists();

        if (!$hasBooking) {
            return back()->with('error', 'Bạn phải mua vé trước khi có thể bình luận!');
        }

        // Kiểm tra đã bình luận chưa
        $existingComment = BinhLuan::where('user_id', Auth::id())
            ->where('chuyen_xe_id', $validated['chuyen_xe_id'])
            ->whereNull('parent_id')
            ->first();

        if ($existingComment) {
            return back()->with('error', 'Bạn đã bình luận cho chuyến xe này rồi!');
        }

        // Filter profanity
        $noiDung = ProfanityFilter::filter($validated['noi_dung']);

        // Tự động duyệt nếu >= 3 sao, chờ duyệt nếu <= 2 sao
        $trangThai = $validated['so_sao'] >= 3 ? 'da_duyet' : 'cho_duyet';

        // Create comment
        BinhLuan::create([
            'parent_id' => null,
            'user_id' => Auth::id(),
            'chuyen_xe_id' => $validated['chuyen_xe_id'],
            'noi_dung' => $noiDung,
            'noi_dung_tl' => '',
            'so_sao' => $validated['so_sao'],
            'trang_thai' => $trangThai,
            'ngay_bl' => now(),
            'ngay_tl' => null,
            'nv_id' => null,
            'ngay_tao' => now(),
            'ngay_duyet' => $trangThai === 'da_duyet' ? now() : null,
            'ly_do_tu_choi' => null,
        ]);

        $message = $trangThai === 'da_duyet'
            ? 'Cảm ơn bạn đã đánh giá! Bình luận của bạn đã được đăng.'
            : 'Cảm ơn bạn đã đánh giá! Bình luận của bạn đang chờ duyệt.';

        return back()->with('success', $message);
    }

    /**
     * Cập nhật bình luận
     */
    public function update(Request $request, BinhLuan $binhLuan)
    {
        // Kiểm tra quyền sở hữu
        if ($binhLuan->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bình luận này.');
        }

        // Không cho phép chỉnh sửa nếu đã có trả lời từ admin/staff
        if ($binhLuan->replies()->count() > 0) {
            return back()->with('error', 'Không thể chỉnh sửa bình luận đã có phản hồi từ nhà xe!');
        }

        $validated = $request->validate([
            'noi_dung' => 'required|string|max:1000',
            'so_sao' => 'required|integer|min:1|max:5',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung bình luận',
            'noi_dung.max' => 'Nội dung không được vượt quá 1000 ký tự',
            'so_sao.required' => 'Vui lòng chọn số sao đánh giá',
        ]);

        // Filter profanity
        $noiDung = ProfanityFilter::filter($validated['noi_dung']);

        // Tự động chuyển trạng thái dựa vào số sao mới
        $trangThai = $validated['so_sao'] >= 3 ? 'da_duyet' : 'cho_duyet';

        $binhLuan->update([
            'noi_dung' => $noiDung,
            'so_sao' => $validated['so_sao'],
            'trang_thai' => $trangThai,
            'ngay_duyet' => $trangThai === 'da_duyet' ? now() : null,
        ]);

        $message = $trangThai === 'da_duyet'
            ? 'Đã cập nhật bình luận của bạn!'
            : 'Đã cập nhật bình luận! Bình luận đang chờ duyệt do điểm đánh giá thấp.';

        return back()->with('success', $message);
    }

    /**
     * Xóa bình luận
     */
    public function destroy(BinhLuan $binhLuan)
    {
        // Kiểm tra quyền sở hữu
        if ($binhLuan->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa bình luận này.');
        }

        // Không cho phép xóa nếu đã có trả lời từ admin/staff
        if ($binhLuan->replies()->count() > 0) {
            return back()->with('error', 'Không thể xóa bình luận đã có phản hồi từ nhà xe!');
        }

        $binhLuan->delete();

        return back()->with('success', 'Đã xóa bình luận của bạn!');
    }

    /**
     * Trả lời bình luận của chính mình (User reply to nhà xe)
     */
    public function reply(Request $request, BinhLuan $binhLuan)
    {
        // Kiểm tra quyền sở hữu - chỉ chủ bình luận mới có thể trả lời
        if ($binhLuan->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền trả lời bình luận này.');
        }

        // Kiểm tra user có vé cho chuyến xe này không
        $hasBooking = DatVe::where('user_id', Auth::id())
            ->where('chuyen_xe_id', $binhLuan->chuyen_xe_id)
            ->whereIn('trang_thai', ['Đã xác nhận', 'Đã thanh toán'])
            ->exists();

        if (!$hasBooking) {
            return back()->with('error', 'Bạn không có quyền trả lời bình luận này!');
        }

        $validated = $request->validate([
            'noi_dung' => 'required|string|max:1000',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung trả lời',
            'noi_dung.max' => 'Nội dung không được vượt quá 1000 ký tự',
        ]);

        // Filter profanity
        $noiDung = ProfanityFilter::filter($validated['noi_dung']);

        // Tự động duyệt reply của user (vì đã có bình luận gốc được duyệt rồi)
        BinhLuan::create([
            'parent_id' => $binhLuan->ma_bl,
            'user_id' => Auth::id(),
            'chuyen_xe_id' => $binhLuan->chuyen_xe_id,
            'noi_dung' => $noiDung,
            'noi_dung_tl' => '',
            'so_sao' => null, // Replies don't have rating
            'trang_thai' => 'da_duyet', // Auto-approved for user replies
            'ngay_bl' => now(),
            'ngay_tl' => now(),
            'nv_id' => null,
            'ngay_tao' => now(),
            'ngay_duyet' => now(),
        ]);

        return back()->with('success', 'Đã gửi phản hồi của bạn!');
    }
}
