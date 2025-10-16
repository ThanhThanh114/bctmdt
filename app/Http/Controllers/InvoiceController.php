<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    public function index()
    {
        $error_message = Session::get('error_message');
        Session::forget('error_message');

        return view('invoice.index', compact('error_message'));
    }

    public function check(Request $request)
    {
        $request->validate(['ma_bimat' => 'required|string']);

        $ma_bimat = $request->input('ma_bimat');

        // Query to fetch invoice data based on the secret code
        $invoice_data = DB::table('dat_ve as dv')
            ->select(
                'dv.ma_ve AS invoice_number',
                'dv.ngay_dat AS invoice_date',
                'dv.trang_thai AS invoice_status',
                'dv.so_ghe AS seat_number',
                'u.fullname AS cust_name',
                'u.phone AS cust_phone',
                'u.email AS cust_email',
                'cx.ten_xe AS bus_name',
                'cx.loai_xe AS bus_type',
                'cx.ngay_di AS trip_date',
                'cx.gio_di AS trip_time',
                'cx.gia_ve AS ticket_price',
                'km.giam_gia AS discount_percentage',
                'tdi.ten_tram AS departure_station',
                'tdi.tinh_thanh AS departure_province',
                'tden.ten_tram AS arrival_station',
                'tden.tinh_thanh AS arrival_province',
                'nx.ten_nha_xe AS bus_company_name',
                'nx.dia_chi AS bus_company_address',
                'nx.so_dien_thoai AS bus_company_phone'
            )
            ->join('users as u', 'dv.user_id', '=', 'u.id')
            ->join('chuyen_xe as cx', 'dv.chuyen_xe_id', '=', 'cx.id')
            ->join('tram_xe as tdi', 'cx.ma_tram_di', '=', 'tdi.ma_tram_xe')
            ->join('tram_xe as tden', 'cx.ma_tram_den', '=', 'tden.ma_tram_xe')
            ->join('nha_xe as nx', 'cx.ma_nha_xe', '=', 'nx.ma_nha_xe')
            ->leftJoin('ve_khuyenmai as vkm', 'dv.id', '=', 'vkm.dat_ve_id')
            ->leftJoin('khuyen_mai as km', 'vkm.ma_km', '=', 'km.ma_km')
            ->where('dv.ma_ve', $ma_bimat)
            ->first();

        if ($invoice_data) {
            // Store invoice data in session
            Session::put('invoice_data', (array) $invoice_data);
            return redirect()->route('invoice.show'); // Redirect to a show route
        } else {
            Session::put('error_message', 'Không tìm thấy hóa đơn với mã bí mật này. Vui lòng kiểm tra lại.');
            return redirect()->route('invoice.index');
        }
    }

    public function show()
    {
        $invoice_data = Session::get('invoice_data');
        if (!$invoice_data) {
            return redirect()->route('invoice.index')->with('error_message', 'Không có dữ liệu hóa đơn.');
        }

        return view('invoice.check', compact('invoice_data'));
    }
}
