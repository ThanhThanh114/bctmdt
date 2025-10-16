<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripController extends Controller
{
    public function index(Request $request)
{
    // Lấy thông tin từ query parameters
    $params = $request->only(['start', 'end', 'date', 'ticket', 'trip', 'bus_type', 'sort', 'page', 'price_range']);
    //Log::info('Tham số tìm kiếm:', $params);

    $params['bus_type'] = $params['bus_type'] ?? 'all'; // Đặt giá trị mặc định cho bus_type
    $params['sort'] = $params['sort'] ?? 'date_asc'; // Đặt giá trị mặc định cho sort
    $params['price_range'] = $params['price_range'] ?? 'all'; // Đặt giá trị mặc định cho price_range
    $params['page'] = max(1, intval($params['page'] ?? 1));
    $perPage = 6; // Hiển thị tối đa 6 chuyến xe trên mỗi trang
    $offset = ($params['page'] - 1) * $perPage;

    // Lấy danh sách tỉnh thành
    $cities = DB::table('tram_xe')->orderBy('ten_tram')->get();

    // Xây dựng điều kiện tìm kiếm
    $query = DB::table('chuyen_xe as cx')
        ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
        ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
        ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
        ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe',
            DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'))
        ->leftJoin(DB::raw('(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai IN ("Đã thanh toán") GROUP BY chuyen_xe_id) dv'), 'cx.id', '=', 'dv.chuyen_xe_id');

    // Lọc theo điểm đi và điểm đến nếu có
    if (!empty($params['start'])) {
        $query->where('tx1.ten_tram', 'like', '%' . $params['start'] . '%');
    }

    if (!empty($params['end'])) {
        $query->where('tx2.ten_tram', 'like', '%' . $params['end'] . '%');
    }

    // Lọc theo ngày nếu có
    if (!empty($params['date'])) {
        $query->whereDate('cx.ngay_di', '=', $params['date']);
    }

    // Kiểm tra bus_type
    if ($params['bus_type'] !== 'all') {
        $query->where('cx.loai_xe', '=', $params['bus_type']);
    }

    // Lọc theo khoảng giá
    if ($params['price_range'] !== 'all') {
        $priceRanges = explode('-', $params['price_range']);
        if (count($priceRanges) === 2) {
            $minPrice = intval($priceRanges[0]);
            $maxPrice = intval($priceRanges[1]);
            $query->whereBetween('cx.gia_ve', [$minPrice, $maxPrice]);
        } elseif (count($priceRanges) === 1) {
            $minPrice = intval($priceRanges[0]);
            $query->where('cx.gia_ve', '>=', $minPrice);
        }
    }

    // Đếm tổng số kết quả (chỉ đếm những chuyến có chỗ trống)
    $allTrips = $query->get();
    $filteredTrips = $allTrips->filter(function ($trip) {
        return $trip->available_seats > 0;
    });
    $totalCount = $filteredTrips->count();
    $totalPages = ceil($totalCount / $perPage);

    // Xử lý sắp xếp
    $orderBy = 'cx.ngay_di'; // Mặc định sắp xếp theo ngày gần nhất
    $orderDirection = 'asc';

    switch ($params['sort']) {
        case 'date_asc':
            $orderBy = 'cx.ngay_di';
            $orderDirection = 'asc';
            break;
        case 'date_desc':
            $orderBy = 'cx.ngay_di';
            $orderDirection = 'desc';
            break;
        case 'time_asc':
            $orderBy = 'cx.gio_di';
            $orderDirection = 'asc';
            break;
        case 'time_desc':
            $orderBy = 'cx.gio_di';
            $orderDirection = 'desc';
            break;
        case 'price_asc':
            $orderBy = 'cx.gia_ve';
            $orderDirection = 'asc';
            break;
        case 'price_desc':
            $orderBy = 'cx.gia_ve';
            $orderDirection = 'desc';
            break;
        default:
            $orderBy = 'cx.ngay_di';
            $orderDirection = 'asc';
            break;
    }

    // Lấy dữ liệu chuyến xe với phân trang (đã lọc chỗ trống)
    $trips = $query->orderBy($orderBy, $orderDirection)
        ->get()
        ->filter(function ($trip) {
            return $trip->available_seats > 0;
        })
        ->slice($offset, $perPage);

    return view('trips.trips', compact('trips', 'totalCount', 'totalPages', 'params', 'cities'));
}
}
