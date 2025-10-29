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
    $params = $request->only(['start', 'end', 'date', 'ticket', 'trip', 'bus_type', 'sort', 'page', 'price_range', 'bus_company', 'departure_date', 'arrival_date', 'driver']);
    //Log::info('Tham số tìm kiếm:', $params);

    $params['bus_type'] = $params['bus_type'] ?? 'all'; // Đặt giá trị mặc định cho bus_type
    $params['sort'] = $params['sort'] ?? 'date_asc'; // Đặt giá trị mặc định cho sort
    $params['price_range'] = $params['price_range'] ?? 'all'; // Đặt giá trị mặc định cho price_range
    $params['bus_company'] = $params['bus_company'] ?? 'all'; // Đặt giá trị mặc định cho bus_company
    $params['departure_date'] = $params['departure_date'] ?? null; // Ngày đi
    $params['arrival_date'] = $params['arrival_date'] ?? null; // Ngày đến
    $params['driver'] = $params['driver'] ?? 'all'; // Tài xế
    $params['page'] = max(1, intval($params['page'] ?? 1));
    $perPage = 6; // Hiển thị tối đa 6 chuyến xe trên mỗi trang
    $offset = ($params['page'] - 1) * $perPage;

    // Nếu URL chỉ chứa các query parameter ở giá trị mặc định (ví dụ: ?driver=all&page=1),
    // redirect về đường dẫn gốc /lichtrinh để ẩn query string và tránh duplicate URLs.
    $defaults = [
        'bus_type' => 'all',
        'sort' => 'date_asc',
        'price_range' => 'all',
        'bus_company' => 'all',
        'departure_date' => null,
        'arrival_date' => null,
        'driver' => 'all',
        'page' => '1',
    ];

    $query = $request->query();
    if (!empty($query)) {
        $allDefault = true;
        foreach ($query as $k => $v) {
            // normalize empty strings to null for date fields
            $val = $v === '' ? null : (string)$v;
            $def = array_key_exists($k, $defaults) ? (string)$defaults[$k] : null;
            if ($val !== $def) {
                $allDefault = false;
                break;
            }
        }
        if ($allDefault) {
            return redirect()->route('trips.trips');
        }
    }

    // Lấy danh sách tỉnh thành
    $cities = DB::table('tram_xe')->orderBy('ten_tram')->get();

    // Xây dựng điều kiện tìm kiếm - Chỉ hiển thị chuyến xe chưa khởi hành
    $query = DB::table('chuyen_xe as cx')
        ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
        ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
        ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
        ->select('cx.*', 'tx1.ten_tram as diem_di', 'tx2.ten_tram as diem_den', 'nx.ten_nha_xe',
            DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats'))
    ->leftJoin(DB::raw("(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai IN ('Đã thanh toán') GROUP BY chuyen_xe_id) dv"), 'cx.id', '=', 'dv.chuyen_xe_id')
    // Date/time cutoff: by default show trips whose `ngay_di` is today or later.
    // If the request provides an explicit `date` filter we won't apply this cutoff (the date filter below will handle it).
    // Developers can bypass the cutoff by adding `show_all=1` to the query string for testing.
    ;

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

    // Determine whether the user applied active filters (exclude pagination and default params)
    $filterKeys = ['start','end','date','bus_type','price_range','bus_company','driver','departure_date','arrival_date'];
    $hasActiveFilters = false;
    foreach ($filterKeys as $k) {
        if ($request->has($k)) {
            $v = $request->query($k);
            if ($v !== null && $v !== '' ) {
                // treat default values as not active
                if (($k === 'bus_type' || $k === 'price_range' || $k === 'bus_company' || $k === 'driver') && in_array($v, ['all','date_asc'])) {
                    continue;
                }
                $hasActiveFilters = true;
                break;
            }
        }
    }

    // Apply default cutoff only when the user applied active filters (so pagination alone won't trigger it)
    if ($hasActiveFilters && empty($params['date']) && !$request->boolean('show_all')) {
        // use date-only comparison so trips scheduled today are shown regardless of time-of-day
        $query->whereDate('cx.ngay_di', '>=', date('Y-m-d'));
    }

    // Kiểm tra bus_type
    if ($params['bus_type'] !== 'all') {
        $query->where('cx.loai_xe', '=', $params['bus_type']);
    }

    // Lọc theo nhà xe
    if ($params['bus_company'] !== 'all') {
        $query->where('nx.ma_nha_xe', '=', $params['bus_company']);
    }

    // Lọc theo ngày đi
    if (!empty($params['departure_date'])) {
        $query->whereDate('cx.ngay_di', '=', $params['departure_date']);
    }

    // Lọc theo ngày đến
    if (!empty($params['arrival_date'])) {
        $query->whereDate('cx.ngay_den', '=', $params['arrival_date']);
    }

    // Lọc theo tài xế
    if ($params['driver'] !== 'all') {
        $query->where('cx.ten_tai_xe', 'like', '%' . $params['driver'] . '%');
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
    // Helpful debug log when no trips pass the available seats filter
    if ($filteredTrips->isEmpty()) {
        Log::info('TripController: no available trips after filtering', [
            'all_trips_count' => $allTrips->count(),
            'available_seats_snapshot' => $allTrips->pluck('available_seats')->toArray(),
            'request_query' => $request->query(),
        ]);
    }
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

    // Lấy tên các trạm trung gian cho mỗi chuyến xe
    foreach ($trips as $trip) {
        if (!empty($trip->tram_trung_gian)) {
            $tramIds = explode(',', $trip->tram_trung_gian);
            $tramNames = DB::table('tram_xe')
                ->whereIn('ma_tram_xe', $tramIds)
                ->pluck('ten_tram')
                ->toArray();
            $trip->tram_trung_gian_names = $tramNames;
        } else {
            $trip->tram_trung_gian_names = [];
        }
    }

    return view('trips.trips', compact('trips', 'totalCount', 'totalPages', 'params', 'cities'));
}
}
