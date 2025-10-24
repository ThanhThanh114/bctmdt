<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\ChuyenXe;
use App\Helpers\ProfanityFilter;
use Illuminate\Http\Request;

class BinhLuanController extends Controller
{
    /**
     * Hiển thị danh sách bình luận (cho nhân viên)
     */
    public function index(Request $request)
    {
        $query = BinhLuan::with([
            'user',
            'chuyenXe.tramDi',
            'chuyenXe.tramDen',
            'chuyenXe.nhaXe',
            'parent',
            'replies'
        ]);

        // Chỉ hiển thị bình luận của chuyến xe thuộc nhà xe của nhân viên
        $nhaXeId = auth()->user()->ma_nha_xe;
        $query->whereHas('chuyenXe', function ($q) use ($nhaXeId) {
            $q->where('ma_nha_xe', $nhaXeId);
        });

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
        $query->whereNull('parent_id');

        $binhLuan = $query->orderBy('ngay_bl', 'desc')->paginate(15);

        // Thống kê
        $stats = [
            'total' => BinhLuan::whereHas('chuyenXe', function ($q) use ($nhaXeId) {
                $q->where('ma_nha_xe', $nhaXeId);
            })->whereNull('parent_id')->count(),
            
            'cho_duyet' => BinhLuan::whereHas('chuyenXe', function ($q) use ($nhaXeId) {
                $q->where('ma_nha_xe', $nhaXeId);
            })->where('trang_thai', 'cho_duyet')->whereNull('parent_id')->count(),
            
            'da_duyet' => BinhLuan::whereHas('chuyenXe', function ($q) use ($nhaXeId) {
                $q->where('ma_nha_xe', $nhaXeId);
            })->where('trang_thai', 'da_duyet')->whereNull('parent_id')->count(),
            
            'tu_choi' => BinhLuan::whereHas('chuyenXe', function ($q) use ($nhaXeId) {
                $q->where('ma_nha_xe', $nhaXeId);
            })->where('trang_thai', 'tu_choi')->whereNull('parent_id')->count(),
        ];

        return view('AdminLTE.staff.binh_luan.index', compact('binhLuan', 'stats'));
    }

    /**
     * Hiển thị chi tiết bình luận
     */
    public function show(BinhLuan $binhLuan)
    {
        // Kiểm tra quyền truy cập
        if ($binhLuan->chuyenXe->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền xem bình luận này.');
        }

        // Load relationships với điều kiện lọc replies
        $binhLuan->load([
            'user', 
            'chuyenXe.tramDi', 
            'chuyenXe.tramDen', 
            'chuyenXe.nhaXe', 
            'parent.user',
            'replies' => function($query) {
                // Chỉ load replies đã duyệt và không phải của admin
                $query->where('trang_thai', 'da_duyet')
                      ->whereHas('user', function($q) {
                          $q->where('role', '!=', 'admin'); // Ẩn reply của admin
                      })
                      ->with('user')
                      ->orderBy('ngay_bl', 'asc');
            }
        ]);
        
        return view('AdminLTE.staff.binh_luan.show', compact('binhLuan'));
    }

    /**
     * Trả lời bình luận (Staff reply to customer)
     */
    public function reply(Request $request, BinhLuan $binhLuan)
    {
        // Kiểm tra quyền
        if ($binhLuan->chuyenXe->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền trả lời bình luận này.');
        }

        $validated = $request->validate([
            'noi_dung' => 'required|string|max:1000',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung trả lời',
            'noi_dung.max' => 'Nội dung không được vượt quá 1000 ký tự',
        ]);

        // Filter profanity
        $noiDung = ProfanityFilter::filter($validated['noi_dung']);

        // Create reply
        BinhLuan::create([
            'parent_id' => $binhLuan->ma_bl,
            'user_id' => auth()->id(),
            'chuyen_xe_id' => $binhLuan->chuyen_xe_id,
            'noi_dung' => $noiDung,
            'noi_dung_tl' => '',
            'so_sao' => null, // Replies don't have rating
            'trang_thai' => 'da_duyet', // Staff replies are auto-approved
            'ngay_bl' => now(),
            'ngay_tl' => now(),
            'nv_id' => auth()->user()->ma_nhan_vien ?? null,
            'ngay_tao' => now(),
            'ngay_duyet' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã gửi trả lời thành công!');
    }

    /**
     * Duyệt bình luận
     */
    public function approve(BinhLuan $binhLuan)
    {
        // Kiểm tra quyền
        if ($binhLuan->chuyenXe->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền duyệt bình luận này.');
        }

        $binhLuan->update([
            'trang_thai' => 'da_duyet',
            'ngay_duyet' => now(),
            'ly_do_tu_choi' => null,
            'nv_id' => auth()->user()->ma_nhan_vien ?? null,
        ]);

        return redirect()->back()->with('success', 'Đã duyệt bình luận thành công!');
    }

    /**
     * Từ chối bình luận
     */
    public function reject(Request $request, BinhLuan $binhLuan)
    {
        // Kiểm tra quyền
        if ($binhLuan->chuyenXe->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền từ chối bình luận này.');
        }

        $validated = $request->validate([
            'ly_do_tu_choi' => 'required|string|max:255',
        ], [
            'ly_do_tu_choi.required' => 'Vui lòng nhập lý do từ chối',
        ]);

        $binhLuan->update([
            'trang_thai' => 'tu_choi',
            'ly_do_tu_choi' => $validated['ly_do_tu_choi'],
            'nv_id' => auth()->user()->ma_nhan_vien ?? null,
        ]);

        return redirect()->back()->with('success', 'Đã từ chối bình luận!');
    }

    /**
     * Xóa bình luận
     */
    public function destroy(BinhLuan $binhLuan)
    {
        // Kiểm tra quyền
        if ($binhLuan->chuyenXe->ma_nha_xe !== auth()->user()->ma_nha_xe) {
            abort(403, 'Bạn không có quyền xóa bình luận này.');
        }

        try {
            // Xóa các bình luận trả lời trước
            $binhLuan->replies()->delete();

            // Xóa bình luận chính
            $binhLuan->delete();

            return redirect()->route('staff.binh-luan.index')
                ->with('success', 'Xóa bình luận thành công!');
        } catch (\Exception $e) {
            return redirect()->route('staff.binh-luan.index')
                ->with('error', 'Không thể xóa bình luận này!');
        }
    }
}
