<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // Số bài viết mỗi trang
        $records_per_page = 6;

        // Xác định trang hiện tại từ URL, mặc định là trang 1
        $current_page = $request->input('page', 1);
        if ($current_page < 1) {
            $current_page = 1;
        }

        // Lấy tổng số bài viết
        $total_records = DB::table('tin_tuc')->count();

        // Tính tổng số trang
        $total_pages = ceil($total_records / $records_per_page);

        // Đảm bảo trang hiện tại không vượt quá tổng số trang
        if ($current_page > $total_pages && $total_pages > 0) {
            $current_page = $total_pages;
        }

        // Tính OFFSET để lấy dữ liệu cho trang hiện tại
        $offset = ($current_page - 1) * $records_per_page;

        // Fetch news for the "Tin tức nổi bật" section (top 3 latest news)
        $highlight_news = DB::table('tin_tuc')
            ->orderBy('ngay_dang', 'desc')
            ->limit(3)
            ->get();

        // Fetch news for the "Tất cả tin tức" section with pagination
        $all_news = DB::table('tin_tuc')
            ->orderBy('ngay_dang', 'desc')
            ->offset($offset)
            ->limit($records_per_page)
            ->get();

        return view('news.news', compact('highlight_news', 'all_news', 'current_page', 'total_pages'));
    }

    public function show($id)
{
    // Lấy thông tin chi tiết tin tức theo ID
    $news = DB::table('tin_tuc')->where('ma_tin', $id)->first();

    if (!$news) {
        abort(404); // Nếu không tìm thấy tin tức, trả về lỗi 404
    }

    return view('news.show', compact('news'));
}

    public function __construct()
    {
        // Định nghĩa formatTimestamp function cho tất cả views trong news
        view()->composer(['news.*'], function ($view) {
            $view->with('formatTimestamp', function ($timestamp) {
                return date("H:i d/m/Y", strtotime($timestamp));
            });
        });
    }

}
