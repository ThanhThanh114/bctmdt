<?php

namespace App\Http\Controllers;

use App\Models\ChuyenXe;
use App\Models\TramXe;
use App\Models\TinTuc;
use App\Models\TuyenPhoBien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy dữ liệu từ request
        $start = $request->get('start', '');
        $end = $request->get('end', '');
        $date = $request->get('date', '');
        $busType = $request->get('bus', '');
        $ticket = (int) $request->get('ticket', 1);
        $trip = $request->get('trip', '');

        // Tạo mảng params
        $params = [
            'start' => $start,
            'end' => $end,
            'date' => $date,
            'bus_type' => $busType,
            'ticket' => $ticket,
            'trip' => $trip,
        ];

        // Tìm chuyến xe
        $resultData = [];
        if (!empty($start) && !empty($end) && !empty($date)) {
            $resultData = $this->searchTrips($start, $end, $date, $busType, $ticket, $trip);
        }

        // Phân trang
        $perPage = 6;
        $totalTrips = count($resultData);
        $totalPages = max(1, ceil($totalTrips / $perPage));
        $page = max(1, (int) $request->get('page', 1));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $startIndex = ($page - 1) * $perPage;
        $pagedData = array_slice($resultData, $startIndex, $perPage);

        // Lấy tuyến phổ biến
        $tuyenPhoBien = $this->getTuyenPhoBien();

        // Lấy tin tức mới nhất (12 tin để hiển thị trong slider)
        $latestNews = TinTuc::orderBy('ngay_dang', 'desc')
            ->limit(12)
            ->get();

        // Lấy danh sách trạm (cities) để hiển thị trong select
        $cities = TramXe::orderBy('ten_tram')->get();

        return view('home.home', compact(
            'pagedData',
            'tuyenPhoBien',
            'latestNews',
            'totalPages',
            'page',
            'cities',
            'params' // Thêm params vào view
        ));
    }

    private function searchTrips($start, $end, $date, $busType, $ticket, $trip)
    {
        $query = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
            ->byRoute($start, $end)
            ->byDate($date);

        // Lọc theo loại chuyến
        if ($trip === "oneway") {
            $query->where('loai_chuyen', 'Một chiều');
        } elseif ($trip === "round") {
            $query->where('loai_chuyen', 'Khứ hồi');
        }

        // Lọc theo loại xe
        if (!empty($busType) && $busType !== "Tất cả") {
            $query->where('loai_xe', $busType);
        }

        $results = $query->get();

        // Thêm thông tin bổ sung
        return $results->map(function ($trip) use ($ticket) {
            // đảm bảo ngày/giờ an toàn nếu không phải Carbon
            $ngay_di = null;
            if (is_object($trip->ngay_di) && method_exists($trip->ngay_di, 'format')) {
                $ngay_di = $trip->ngay_di->format('Y-m-d');
            } else {
                $ngay_di = date('Y-m-d', strtotime($trip->ngay_di));
            }

            $gio_di = null;
            if (is_object($trip->gio_di) && method_exists($trip->gio_di, 'format')) {
                $gio_di = $trip->gio_di->format('H:i');
            } else {
                $gio_di = date('H:i', strtotime($trip->gio_di));
            }

            return [
                'id' => $trip->id,
                'ten_xe' => $trip->ten_xe,
                'nha_xe' => $trip->nhaXe->ten_nha_xe,
                'ten_tai_xe' => $trip->ten_tai_xe,
                'sdt_tai_xe' => $trip->sdt_tai_xe,
                'diem_di' => $trip->tramDi->ten_tram,
                'diem_den' => $trip->tramDen->ten_tram,
                'ngay_di' => $ngay_di,
                'gio_di' => $gio_di,
                'loai_xe' => $trip->loai_xe,
                'loai_chuyen' => $trip->loai_chuyen,
                'so_ve' => $trip->so_ve,
                'gia_ve' => $trip->gia_ve,
                'ticket_request' => $ticket
            ];
        })->toArray();
    }

    private function getTuyenPhoBien()
    {
        $sql = "SELECT t_di.ten_tram AS diem_di, t.imgtpb, SUM(t.soluongdatdi) as total_book
                FROM tuyenphobien t
                JOIN chuyen_xe c ON t.ma_xe = c.id
                JOIN tram_xe t_di ON c.ma_tram_di = t_di.ma_tram_xe
                GROUP BY t_di.ten_tram, t.imgtpb
                ORDER BY total_book DESC
                LIMIT 6";

        $results = DB::select($sql);
        $tuyenPhoBien = [];

        foreach ($results as $row) {
            $diem_di = $row->diem_di;
            $tuyenPhoBien[$diem_di] = [
                'imgtpb' => $row->imgtpb,
                'routes' => []
            ];

            // Lấy tối đa 3 chuyến cho mỗi điểm đi
            $sqlChuyen = "SELECT t_den.ten_tram AS diem_den, c.gia_ve, c.ngay_di, c.gio_di, c.loai_xe, c.loai_chuyen, t.soluongdatdi
              FROM chuyen_xe c
              JOIN tuyenphobien t ON c.id = t.ma_xe
              JOIN tram_xe t_di ON c.ma_tram_di = t_di.ma_tram_xe
              JOIN tram_xe t_den ON c.ma_tram_den = t_den.ma_tram_xe
              WHERE t_di.ten_tram = ?
              ORDER BY t.soluongdatdi DESC
              LIMIT 3";

            $routes = DB::select($sqlChuyen, [$diem_di]);
            $tuyenPhoBien[$diem_di]['routes'] = $routes;
        }

        return $tuyenPhoBien;
    }
}
