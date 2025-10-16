<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo biến
        $result = null;
        $error = "";

        return view('tracking.tracking', compact('result', 'error'));
    }

    public function search(Request $request)
    {
        $result = null;
        $error = "";

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
                    'u.fullname',
                    'u.phone',
                    'dv.ma_ve',
                    'dv.so_ghe',
                    'dv.trang_thai',
                    'cx.ten_xe',
                    'nx.ten_nha_xe AS nha_xe_ten',
                    'tdi.ten_tram AS diem_di_ten',
                    'tden.ten_tram AS diem_den_ten',
                    'cx.ngay_di',
                    'cx.gio_di',
                    'cx.gia_ve'
                )
                ->where('u.phone', $phone)
                ->where('dv.ma_ve', $ma_ve)
                ->first();

            if (!$result) {
                $error = "Không tìm thấy vé với thông tin đã nhập";
            }
        }

        return view('tracking.tracking', compact('result', 'error'));
    }
}
