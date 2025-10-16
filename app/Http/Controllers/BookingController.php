<?php
namespace App\Http\Controllers;

use App\Models\ChuyenXe;
use App\Models\DatVe;
use App\Services\BankPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Lấy dữ liệu từ request
        $start = $request->input('start', '');
        $end = $request->input('end', '');
        $date = $request->input('date', '');
        $ticketCount = (int) $request->input('ticket', 1);
        $tripType = $request->input('trip', 'oneway');
        $busType = $request->input('bus_type', 'all');
        $sortBy = $request->input('sort', 'time');
        $page = (int) $request->input('page', 1);
        $itemsPerPage = 12;

        // Nếu có đầy đủ thông tin tìm kiếm, chuyển hướng đến trang chi tiết chuyến xe đầu tiên
        if (!empty($start) && !empty($end) && !empty($date)) {
            $firstTrip = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
                ->select('chuyen_xe.*', DB::raw('chuyen_xe.so_cho - COALESCE(booked_seats, 0) as available_seats'))
                ->leftJoin(DB::raw('(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai IN ("Đã đặt", "Đã thanh toán") GROUP BY chuyen_xe_id) dv'), 'chuyen_xe.id', '=', 'dv.chuyen_xe_id')
                ->whereHas('tramDi', function ($q) use ($start) {
                    $q->where('ten_tram', 'like', "%$start%");
                })
                ->whereHas('tramDen', function ($q) use ($end) {
                    $q->where('ten_tram', 'like', "%$end%");
                })
                ->whereDate('ngay_di', $date)
                ->where('ngay_di', '>=', now())
                ->having('available_seats', '>', 0)
                ->orderBy('gio_di')
                ->first();

            if ($firstTrip) {
                return redirect()->route('booking.show', $firstTrip->id);
            }
        }

        // Lấy danh sách thành phố
        $cities = DB::table('tram_xe')->distinct()->pluck('ten_tram')->sort()->toArray();

        // Xây dựng điều kiện tìm kiếm
        $query = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
            ->where('ngay_di', '>=', now());

        if (!empty($start)) {
            $query->whereHas('tramDi', function ($q) use ($start) {
                $q->where('ten_tram', 'like', "%$start%");
            });
        }

        if (!empty($end)) {
            $query->whereHas('tramDen', function ($q) use ($end) {
                $q->where('ten_tram', 'like', "%$end%");
            });
        }

        if (!empty($date)) {
            $query->whereDate('ngay_di', $date);
        }

        if ($busType !== 'all') {
            $query->where('loai_xe', $busType);
        }

        // Sử dụng subquery để tính chỗ trống (tránh lỗi GROUP BY)
        $query->addSelect([
            'available_seats' => DB::table('dat_ve')
                ->selectRaw('chuyen_xe.so_cho - COALESCE(COUNT(dat_ve.id), 0)')
                ->whereColumn('dat_ve.chuyen_xe_id', 'chuyen_xe.id')
                ->whereIn('dat_ve.trang_thai', ['Đã đặt', 'Đã thanh toán'])
                ->groupBy('dat_ve.chuyen_xe_id')
                ->limit(1)
        ]);

        // Đếm tổng số kết quả
        $totalCount = $query->get()->count();
        $totalPages = ceil($totalCount / $itemsPerPage);

        // Lấy dữ liệu chuyến xe với phân trang
        $trips = $query->orderBy($sortBy === 'price' ? 'gia_ve' : 'gio_di')
            ->paginate($itemsPerPage);

        return view('booking.booking', compact('trips', 'totalCount', 'totalPages', 'page', 'start', 'end', 'date', 'busType'));
    }

    public function show($id)
    {
        $selectedTrip = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
            ->select('chuyen_xe.*', DB::raw('chuyen_xe.so_cho - COALESCE(booked_seats, 0) as available_seats'))
            ->leftJoin(DB::raw('(SELECT chuyen_xe_id, COUNT(*) as booked_seats FROM dat_ve WHERE trang_thai IN ("Đã đặt", "Đã thanh toán") GROUP BY chuyen_xe_id) dv'), 'chuyen_xe.id', '=', 'dv.chuyen_xe_id')
            ->where('chuyen_xe.id', $id)
            ->firstOrFail();

        // Lấy danh sách thành phố cho search form
        $cities = DB::table('tram_xe')->orderBy('ten_tram')->get();

        // Lấy thông tin cho search form từ chuyến xe đã chọn
        $start = $selectedTrip->tramDi->ten_tram;
        $end = $selectedTrip->tramDen->ten_tram;
        $date = \Carbon\Carbon::parse($selectedTrip->ngay_di)->format('Y-m-d');

        return view('booking.results', compact('selectedTrip', 'cities', 'start', 'end', 'date'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'trip_id' => 'required|exists:chuyen_xe,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:15',
            'passenger_email' => 'nullable|email|max:255',
            'seat_count' => 'required|integer|min:1|max:5',
        ]);

        // Kiểm tra đã đăng nhập
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để đặt vé!');
        }

        // Lưu thông tin vào session để chọn ghế
        Session::put('booking_info', [
            'trip_id' => $request->trip_id,
            'passenger_name' => $request->passenger_name,
            'passenger_phone' => $request->passenger_phone,
            'passenger_email' => $request->passenger_email,
            'seat_count' => $request->seat_count,
        ]);

        return redirect()->route('booking.seat_selection');
    }

    public function seatSelection()
    {
        if (!Session::has('booking_info')) {
            return redirect()->route('booking.booking')->with('error', 'Không tìm thấy thông tin đặt vé!');
        }

        $bookingInfo = Session::get('booking_info');
        $trip = ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])->findOrFail($bookingInfo['trip_id']);

        // CHỈ LẤY GHẾ ĐÃ THANH TOÁN - Ghế "Đã đặt" vẫn cho phép người khác chọn
        // Ai thanh toán trước thì được ghế đó
        $bookedSeats = DatVe::where('chuyen_xe_id', $trip->id)
            ->where('trang_thai', 'Đã thanh toán')
            ->pluck('so_ghe')
            ->toArray();

        return view('booking.seat_selection', compact('trip', 'bookingInfo', 'bookedSeats'));
    }

    public function completeBooking(Request $request)
    {
        $request->validate([
            'selected_seats' => 'required|string',
        ]);

        if (!Session::has('booking_info')) {
            return redirect()->route('booking.booking')->with('error', 'Phiên đặt vé đã hết hạn!');
        }

        $bookingInfo = Session::get('booking_info');
        $selectedSeats = json_decode($request->selected_seats, true);

        if (count($selectedSeats) != $bookingInfo['seat_count']) {
            return back()->with('error', 'Số ghế chọn không khớp với số vé yêu cầu!');
        }

        DB::beginTransaction();
        try {
            $bookingCode = 'BK' . date('YmdHis') . rand(100, 999);
            $trip = ChuyenXe::findOrFail($bookingInfo['trip_id']);
            $baseAmount = $trip->gia_ve * count($selectedSeats);
            
            // Xử lý mã giảm giá
            $discountAmount = 0;
            $discountCode = $request->discount_code;
            $khuyenMaiId = null;

            if ($discountCode) {
                $khuyenMai = \App\Models\KhuyenMai::where('ma_khuyen_mai', strtoupper($discountCode))
                    ->where('trang_thai', 'Đang áp dụng')
                    ->where('ngay_bat_dau', '<=', now())
                    ->where('ngay_ket_thuc', '>=', now())
                    ->where('so_luong', '>', 0)
                    ->first();

                if ($khuyenMai) {
                    $discountAmount = intval($request->discount_amount ?? 0);
                    $khuyenMaiId = $khuyenMai->id;
                    
                    // Giảm số lượng mã khuyến mãi
                    $khuyenMai->decrement('so_luong');
                }
            }

            $totalAmount = $baseAmount - $discountAmount;

            foreach ($selectedSeats as $seat) {
                $datVe = DatVe::create([
                    'user_id' => Auth::id(),
                    'chuyen_xe_id' => $bookingInfo['trip_id'],
                    'ma_ve' => $bookingCode,
                    'so_ghe' => $seat,
                    'trang_thai' => 'Đã đặt', // Chờ thanh toán
                ]);

                // Gắn mã khuyến mãi vào vé
                if ($khuyenMaiId) {
                    $datVe->khuyenMais()->attach($khuyenMaiId);
                }
            }

            DB::commit();

            // Lưu thông tin để thanh toán
            Session::put('payment_info', [
                'booking_code' => $bookingCode,
                'total_amount' => $totalAmount,
                'base_amount' => $baseAmount,
                'discount_amount' => $discountAmount,
                'discount_code' => $discountCode,
                'seats' => $selectedSeats,
                'trip_id' => $bookingInfo['trip_id']
            ]);

            Session::forget('booking_info');

            // Chuyển đến trang thanh toán
            return redirect()->route('payment.show', ['code' => $bookingCode]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đặt vé. Vui lòng thử lại!');
        }
    }

    public function showPayment($code)
    {
        if (!Session::has('payment_info')) {
            return redirect()->route('booking.booking')->with('error', 'Không tìm thấy thông tin thanh toán!');
        }

        $paymentInfo = Session::get('payment_info');

        if ($paymentInfo['booking_code'] != $code) {
            return redirect()->route('booking.booking')->with('error', 'Mã booking không hợp lệ!');
        }

        $bookings = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen'])
            ->where('ma_ve', $code)
            ->where('user_id', Auth::id())
            ->get();

        if ($bookings->isEmpty()) {
            abort(404);
        }

        return view('payment.show', compact('bookings', 'code', 'paymentInfo'));
    }

    public function verifyPayment(Request $request)
    {
        $bookingCode = $request->booking_code;

        if (!Session::has('payment_info')) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên thanh toán đã hết hạn!'
            ]);
        }

        $paymentInfo = Session::get('payment_info');

        // KIỂM TRA CONFLICT: Có người khác đã thanh toán ghế này chưa?
        $myBookings = DatVe::where('ma_ve', $bookingCode)->get();
        $mySeats = $myBookings->pluck('so_ghe')->toArray();
        $tripId = $myBookings->first()->chuyen_xe_id;

        // Tìm ghế đã có người thanh toán (không phải booking của mình)
        $conflictSeats = DatVe::where('chuyen_xe_id', $tripId)
            ->where('trang_thai', 'Đã thanh toán')
            ->where('ma_ve', '!=', $bookingCode)
            ->whereIn('so_ghe', $mySeats)
            ->pluck('so_ghe')
            ->toArray();

        if (!empty($conflictSeats)) {
            // Có người khác đã thanh toán trước → Hủy booking này
            DatVe::where('ma_ve', $bookingCode)->update(['trang_thai' => 'Đã hủy']);

            return response()->json([
                'success' => false,
                'message' => 'Rất tiếc! Ghế ' . implode(', ', $conflictSeats) . ' đã có người thanh toán trước. Vui lòng chọn ghế khác.'
            ]);
        }

        // Kiểm tra thanh toán
        $bankService = new BankPaymentService();
        $result = $bankService->verifyTransaction($bookingCode, $paymentInfo['total_amount']);

        if ($result['verified']) {
            // Cập nhật trạng thái booking
            DatVe::where('ma_ve', $bookingCode)
                ->update(['trang_thai' => 'Đã thanh toán']);

            Session::forget('payment_info');

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán thành công!',
                'redirect' => route('booking.success', ['code' => $bookingCode])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }

    public function success($code)
    {
        // Xóa session để tránh lỗi khi đặt vé mới
        Session::forget('booking_info');
        Session::forget('payment_info');

        $bookings = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen'])
            ->where('ma_ve', $code)
            ->where('user_id', Auth::id())
            ->get();

        if ($bookings->isEmpty()) {
            abort(404);
        }

        return view('booking.success', compact('bookings', 'code'));
    }

    public function history()
    {
        $user = Auth::user();

        // Lấy lịch sử đặt vé của user với thông tin chuyến xe
        $bookings = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen'])
            ->where('user_id', $user->id)
            ->orderBy('ngay_dat', 'desc')
            ->paginate(10);

        return view('booking.history', compact('bookings'));
    }

    /**
     * Kiểm tra mã giảm giá
     */
    public function checkDiscount(Request $request)
    {
        $code = strtoupper($request->code);
        $totalAmount = $request->total_amount;

        // Tìm mã khuyến mãi
        $discount = \App\Models\KhuyenMai::where('ma_khuyen_mai', $code)
            ->where('trang_thai', 'Đang áp dụng')
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->first();

        if (!$discount) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn!'
            ]);
        }

        // Kiểm tra số lượng còn lại
        if ($discount->so_luong <= 0) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng!'
            ]);
        }

        // Tính số tiền giảm
        $discountPercent = $discount->giam_gia;
        $discountAmount = ($totalAmount * $discountPercent) / 100;

        // Làm tròn đến 1000đ
        $discountAmount = round($discountAmount / 1000) * 1000;

        return response()->json([
            'valid' => true,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'message' => "Giảm {$discountPercent}% - Tiết kiệm " . number_format($discountAmount, 0, ',', '.') . 'đ'
        ]);
    }
}