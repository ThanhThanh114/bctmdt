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
                $val = $v === '' ? null : (string) $v;
                $def = array_key_exists($k, $defaults) ? (string) $defaults[$k] : null;
                if ($val !== $def) {
                    $allDefault = false;
                    break;
                }
            }
            if ($allDefault) {
                return redirect()->route('trips.trips');
            }
        }

        // Log query parameters cho debug
        Log::info('Filter Parameters:', [
            'bus_type' => $params['bus_type'],
            'bus_company' => $params['bus_company'],
            'driver' => $params['driver'],
            'price_range' => $params['price_range']
        ]);

        // Lấy danh sách tỉnh thành
        $cities = DB::table('tram_xe')->orderBy('ten_tram')->get();
        $query = DB::table('chuyen_xe as cx')
            ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
            ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
            ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
            ->select(
                'cx.*',
                'tx1.ten_tram as diem_di',
                'tx2.ten_tram as diem_den',
                'nx.ten_nha_xe',
                'nx.ma_nha_xe',
                DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats')
            )
            ->leftJoin(
                DB::raw("(
                SELECT chuyen_xe_id, COUNT(*) as booked_seats
                FROM dat_ve
                WHERE trang_thai = 'Đã thanh toán'
                GROUP BY chuyen_xe_id
            ) dv"),
                'cx.id',
                '=',
                'dv.chuyen_xe_id'
            )
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
        $filterKeys = ['start', 'end', 'date', 'bus_type', 'price_range', 'bus_company', 'driver', 'departure_date', 'arrival_date'];
        $hasActiveFilters = false;
        foreach ($filterKeys as $k) {
            if ($request->has($k)) {
                $v = $request->query($k);
                // normalize empty strings to null
                $val = $v === '' ? null : (string) $v;
                $def = array_key_exists($k, $defaults) ? (string) $defaults[$k] : null;
                if ($val !== $def && $val !== null) {
                    $hasActiveFilters = true;
                    break;
                }
            }
        }

        // Log incoming params and whether filters are considered active for debugging
        Log::debug('TripController::index incoming params', ['params' => $params, 'hasActiveFilters' => $hasActiveFilters, 'query' => $request->query()]);

        // Apply default date cutoff only when the user applied active filters
        // This preserves the previous UX: when no filters are chosen show all trips (including past),
        // but when the user starts filtering we show recent trips by default (unless `date` or `show_all`).
        // Use a very lenient cutoff (7 days ago) to handle databases with past dates
        if ($hasActiveFilters && empty($params['date']) && !$request->boolean('show_all')) {
            $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
            $query->whereDate('cx.ngay_di', '>=', $sevenDaysAgo);
        }

        // Normalize and apply bus_type filter (case-insensitive, UTF-8 safe)
        $busType = isset($params['bus_type']) ? trim($params['bus_type']) : 'all';
        if ($busType !== 'all' && $busType !== '') {
            // Use a case-insensitive comparison with proper UTF-8 handling
            $busTypeLower = mb_strtolower($busType, 'UTF-8');
            $query->where(function ($q) use ($busType, $busTypeLower) {
                $q->whereRaw('LOWER(cx.loai_xe) = ?', [$busTypeLower])
                    ->orWhereRaw('cx.loai_xe = ?', [$busType]);
            });
        }

        // Normalize and apply bus_company filter (cast to int for safety)
        $busCompany = isset($params['bus_company']) ? trim($params['bus_company']) : 'all';
        if ($busCompany !== 'all' && $busCompany !== '') {
            // If the client passed a numeric id, match by ma_nha_xe. Otherwise try matching by name.
            if (ctype_digit((string) $busCompany)) {
                $query->where('nx.ma_nha_xe', '=', intval($busCompany));
            } else {
                $name = trim($busCompany);
                $query->where('nx.ten_nha_xe', 'like', '%' . $name . '%');
            }
        }

        // Lọc theo ngày đi
        if (!empty($params['departure_date'])) {
            $query->whereDate('cx.ngay_di', '=', $params['departure_date']);
        }

        // Lọc theo ngày đến
        if (!empty($params['arrival_date'])) {
            $query->whereDate('cx.ngay_den', '=', $params['arrival_date']);
        }

        // Normalize and apply driver filter (trim)
        $driver = isset($params['driver']) ? trim($params['driver']) : 'all';
        if ($driver !== 'all' && $driver !== '') {
            // match driver name - support both exact name and partial match
            $d = trim($driver);
            $query->where('cx.ten_tai_xe', 'like', '%' . $d . '%');
        }

        // Lọc theo khoảng giá (defensive parsing)
        if (isset($params['price_range']) && $params['price_range'] !== 'all' && $params['price_range'] !== '') {
            $raw = (string) $params['price_range'];
            // support formats: 'min-max', 'min-' (open-ended), '-max'
            $parts = explode('-', $raw, 2);
            $min = preg_replace('/[^0-9]/', '', $parts[0] ?? '');
            $max = isset($parts[1]) ? preg_replace('/[^0-9]/', '', $parts[1]) : '';
            if ($min !== '' && $max !== '') {
                $query->whereBetween('cx.gia_ve', [intval($min), intval($max)]);
            } elseif ($min !== '') {
                $query->where('cx.gia_ve', '>=', intval($min));
            } elseif ($max !== '') {
                $query->where('cx.gia_ve', '<=', intval($max));
            }
        }

        // Debug: log the built SQL and bindings before execution when filters are present
        if ($hasActiveFilters) {
            try {
                Log::debug('TripController SQL (pre-exec)', [
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                ]);
            } catch (\Exception $e) {
                // toSql/getBindings can sometimes fail when raw subqueries exist; swallow error
                Log::debug('TripController SQL debug failed', ['error' => $e->getMessage()]);
            }
        }

        // Apply available seats filter at SQL level so counts and pagination are correct
        $query->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

        // Đếm tổng số kết quả (sử dụng bản sao của query để đếm chính xác)
        try {
            $countQuery = clone $query;
            $totalCount = $countQuery->count();
        } catch (\Exception $e) {
            // Fallback: fetch and count in PHP (should be rare)
            $allTrips = $query->get();
            $totalCount = $allTrips->filter(function ($trip) {
                return ($trip->available_seats ?? 0) > 0;
            })->count();
            Log::warning('TripController count fallback used', ['error' => $e->getMessage()]);
        }

        if ($totalCount === 0) {
            // Log helpful snapshot for debugging when no trips match
            try {
                $sample = $query->limit(20)->get()->map(function ($t) {
                    return [
                        'id' => $t->id ?? null,
                        'ma_xe' => $t->ma_xe ?? null,
                        'gia_ve' => $t->gia_ve ?? null,
                        'available_seats' => $t->available_seats ?? null,
                    ];
                })->toArray();
            } catch (\Exception $e) {
                $sample = [];
            }
            Log::info('TripController: no trips after filters', [
                'total_count' => $totalCount,
                'request_query' => $request->query(),
                'sample_rows' => $sample,
            ]);
        }

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

        // Lấy dữ liệu chuyến xe với phân trang (đã lọc chỗ trống) at SQL level
        $trips = $query->orderBy($orderBy, $orderDirection)
            ->offset($offset)
            ->limit($perPage)
            ->get();

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

    /**
     * Dev-only endpoint: return the built SQL, bindings and a small sample of rows
     * for the current trips filters. Enabled only in local or debug mode.
     */
    public function debug(Request $request)
    {
        if (!(app()->environment('local') || config('app.debug'))) {
            abort(404);
        }

        $params = $request->only(['start', 'end', 'date', 'ticket', 'trip', 'bus_type', 'sort', 'page', 'price_range', 'bus_company', 'departure_date', 'arrival_date', 'driver']);
        $params['bus_type'] = $params['bus_type'] ?? 'all';
        $params['sort'] = $params['sort'] ?? 'date_asc';
        $params['price_range'] = $params['price_range'] ?? 'all';
        $params['bus_company'] = $params['bus_company'] ?? 'all';
        $params['departure_date'] = $params['departure_date'] ?? null;
        $params['arrival_date'] = $params['arrival_date'] ?? null;
        $params['driver'] = $params['driver'] ?? 'all';

        // Build the base query similar to index()
        $query = DB::table('chuyen_xe as cx')
            ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
            ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
            ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
            ->select(
                'cx.*',
                'tx1.ten_tram as diem_di',
                'tx2.ten_tram as diem_den',
                'nx.ten_nha_xe',
                DB::raw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) as available_seats')
            )
            ->leftJoin(DB::raw("(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai IN ('Đã thanh toán') GROUP BY chuyen_xe_id) dv"), 'cx.id', '=', 'dv.chuyen_xe_id');

        // apply filters (reuse same normalization as index)
        if (!empty($params['start'])) {
            $query->where('tx1.ten_tram', 'like', '%' . $params['start'] . '%');
        }
        if (!empty($params['end'])) {
            $query->where('tx2.ten_tram', 'like', '%' . $params['end'] . '%');
        }
        if (!empty($params['date'])) {
            $query->whereDate('cx.ngay_di', '=', $params['date']);
        }

        // bus_type
        $busType = isset($params['bus_type']) ? trim($params['bus_type']) : 'all';
        if ($busType !== 'all' && $busType !== '') {
            // Use a case-insensitive comparison with proper UTF-8 handling
            $busTypeLower = mb_strtolower($busType, 'UTF-8');
            $query->where(function ($q) use ($busType, $busTypeLower) {
                $q->whereRaw('LOWER(cx.loai_xe) = ?', [$busTypeLower])
                    ->orWhereRaw('cx.loai_xe = ?', [$busType]);
            });
        }

        // bus_company
        $busCompany = isset($params['bus_company']) ? trim($params['bus_company']) : 'all';
        if ($busCompany !== 'all' && $busCompany !== '') {
            if (ctype_digit((string) $busCompany)) {
                $query->where('nx.ma_nha_xe', '=', intval($busCompany));
            } else {
                $query->where('nx.ten_nha_xe', 'like', '%' . $busCompany . '%');
            }
        }

        if (!empty($params['departure_date'])) {
            $query->whereDate('cx.ngay_di', '=', $params['departure_date']);
        }
        if (!empty($params['arrival_date'])) {
            $query->whereDate('cx.ngay_den', '=', $params['arrival_date']);
        }

        // driver
        $driver = isset($params['driver']) ? trim($params['driver']) : 'all';
        if ($driver !== 'all' && $driver !== '') {
            // match driver name - support both exact name and partial match
            $d = trim($driver);
            $query->where('cx.ten_tai_xe', 'like', '%' . $d . '%');
        }

        // price_range
        if (isset($params['price_range']) && $params['price_range'] !== 'all' && $params['price_range'] !== '') {
            $raw = (string) $params['price_range'];
            $parts = explode('-', $raw, 2);
            $min = preg_replace('/[^0-9]/', '', $parts[0] ?? '');
            $max = isset($parts[1]) ? preg_replace('/[^0-9]/', '', $parts[1]) : '';
            if ($min !== '' && $max !== '') {
                $query->whereBetween('cx.gia_ve', [intval($min), intval($max)]);
            } elseif ($min !== '') {
                $query->where('cx.gia_ve', '>=', intval($min));
            } elseif ($max !== '') {
                $query->where('cx.gia_ve', '<=', intval($max));
            }
        }

        // apply available seats condition
        $query->whereRaw('(cx.so_cho - COALESCE(dv.booked_seats, 0)) > 0');

        // Apply same date cutoff logic as in index method
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        $query->whereDate('cx.ngay_di', '>=', $sevenDaysAgo);

        // prepare response
        try {
            $sql = $query->toSql();
            $bindings = $query->getBindings();
        } catch (\Exception $e) {
            $sql = 'failed to generate sql: ' . $e->getMessage();
            $bindings = [];
        }

        try {
            $count = $query->count();
        } catch (\Exception $e) {
            $count = null;
        }

        try {
            $sample = $query->limit(20)->get();
        } catch (\Exception $e) {
            $sample = [];
        }

        return response()->json([
            'params' => $params,
            'sql' => $sql,
            'bindings' => $bindings,
            'count' => $count,
            'sample' => $sample,
        ]);
    }

    /**
     * API endpoint để lấy danh sách tài xế theo nhà xe
     * Sử dụng khi user thay đổi dropdown nhà xe
     */
    public function getDriversByBusCompany(Request $request)
    {
        $busCompany = $request->get('bus_company');

        if (!$busCompany || $busCompany === 'all') {
            // Nếu chọn "Tất cả nhà xe" thì trả về tất cả tài xế
            $drivers = \App\Models\ChuyenXe::select('ten_tai_xe')
                ->whereNotNull('ten_tai_xe')
                ->where('ten_tai_xe', '!=', '')
                ->distinct()
                ->orderBy('ten_tai_xe')
                ->get();
        } else {
            // Nếu chọn nhà xe cụ thể, chỉ trả về tài xế của nhà xe đó
            $query = \App\Models\ChuyenXe::select('ten_tai_xe')
                ->whereNotNull('ten_tai_xe')
                ->where('ten_tai_xe', '!=', '');

            // Kiểm tra xem bus_company là ID hay tên
            if (ctype_digit((string) $busCompany)) {
                $query->where('ma_nha_xe', intval($busCompany));
            } else {
                // Nếu là tên nhà xe, cần join với bảng nha_xe
                $query->whereHas('nhaXe', function ($q) use ($busCompany) {
                    $q->where('ten_nha_xe', 'like', '%' . $busCompany . '%');
                });
            }

            $drivers = $query->distinct()
                ->orderBy('ten_tai_xe')
                ->get();
        }

        return response()->json([
            'drivers' => $drivers->pluck('ten_tai_xe')->toArray()
        ]);
    }
}
