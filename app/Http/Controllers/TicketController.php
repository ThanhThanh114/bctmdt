<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function search(Request $request)
    {
        $result = null;
        $error = "";

        if ($request->isMethod('post')) {
            $phone = $request->input('phone');
            $ma_ve = $request->input('code');

            if (empty($phone) || empty($ma_ve)) {
                $error = "Vui lòng nhập SĐT và Mã vé";
            } else {
                $result = DB::table('dat_ve as dv')
                    ->join('users as u', 'dv.user_id', '=', 'u.id')
                    ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
                    ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
                    ->join('tram_xe as tdi', 'cx.ma_tram_di', '=', 'tdi.ma_tram_xe')
                    ->join('tram_xe as tden', 'cx.ma_tram_den', '=', 'tden.ma_tram_xe')
                    ->select(
                        'u.fullname', 'u.phone',
                        'dv.ma_ve', 'dv.so_ghe', 'dv.trang_thai',
                        'cx.ten_xe',
                        'nx.ten_nha_xe AS nha_xe_ten',
                        'tdi.ten_tram AS diem_di_ten',
                        'tden.ten_tram AS diem_den_ten',
                        'cx.ngay_di', 'cx.gio_di', 'cx.gia_ve'
                    )
                    ->where('u.phone', $phone)
                    ->where('dv.ma_ve', $ma_ve)
                    ->first();

                if (!$result) {
                    $error = "Không tìm thấy vé với thông tin đã nhập";
                }
            }
        }

        return view('tickets.search', compact('result', 'error'));
    }
    public function schedule(Request $request)
{
    $start = trim($request->input('start', ''));
    $end = trim($request->input('end', ''));
    $date = $request->input('date', '');
    $busType = $request->input('bus_type', 'all');
    $sortBy = $request->input('sort', 'time');
    $page = max(1, intval($request->input('page', 1)));
    $perPage = 12;
    $offset = ($page - 1) * $perPage;

    $trips = [];
    $totalCount = 0;
    $totalPages = 1;
    $cities = [];

    try {
        // Lấy danh sách tỉnh thành từ database
        $cities = DB::table('tram_xe')->distinct()->orderBy('ten_tram')->pluck('ten_tram');

        // Xây dựng điều kiện tìm kiếm
        $query = DB::table('chuyen_xe as cx')
            ->join('tram_xe as tx1', 'cx.ma_tram_di', '=', 'tx1.ma_tram_xe')
            ->join('tram_xe as tx2', 'cx.ma_tram_den', '=', 'tx2.ma_tram_xe')
            ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
            ->leftJoin('dat_ve as dv', 'cx.id', '=', 'dv.chuyen_xe_id') // Giả sử bạn có bảng đặt vé
            ->select(
                'cx.*',
                'tx1.ten_tram as diem_di',
                'tx2.ten_tram as diem_den',
                'nx.ten_nha_xe',
                DB::raw('(cx.so_cho - COUNT(dv.id)) as available_seats')
            )
            ->groupBy('cx.id', 'tx1.ten_tram', 'tx2.ten_tram', 'nx.ten_nha_xe') // Nhóm theo các trường cần thiết

            ->where('cx.ngay_di', '>=', now());

        if (!empty($start)) {
            $query->where('tx1.ten_tram', 'LIKE', "%$start%");
        }

        if (!empty($end)) {
            $query->where('tx2.ten_tram', 'LIKE', "%$end%");
        }

        if (!empty($date)) {
            $query->whereDate('cx.ngay_di', $date);
        }

        if ($busType !== 'all') {
            $query->where('cx.loai_xe', $busType);
        }

        $totalCount = $query->count();
        $totalPages = ceil($totalCount / $perPage);

        $trips = $query->skip($offset)->take($perPage)
            ->orderBy(($sortBy === 'price' ? 'cx.gia_ve' : 'cx.gio_di'), 'asc')
            ->get();

    } catch (\Exception $e) {
        Log::error("Error querying database: " . $e->getMessage());
    }

    return view('tickets.schedule', compact('trips', 'totalCount', 'totalPages', 'page', 'start', 'end', 'date', 'busType', 'sortBy', 'cities'));
}
}