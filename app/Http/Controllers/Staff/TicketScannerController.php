<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketScannerController extends Controller
{
    // Trang soát vé với camera QR scanner
    public function index()
    {
        // Thống kê soát vé hôm nay
        $todayScanned = DB::table('ticket_scans')
            ->whereDate('scanned_at', today())
            ->count();

        $todayBookings = DatVe::whereDate('ngay_dat', today())
            ->whereIn('trang_thai', ['Đã đặt', 'Đã thanh toán', 'Đã xác nhận'])
            ->count();

        return view('AdminLTE.staff.ticket_scanner.index', compact('todayScanned', 'todayBookings'));
    }

    // Xác thực vé từ QR code
    public function verify(Request $request)
    {
        try {
            $request->validate([
                'qr_data' => 'required|string'
            ]);

            // Giải mã dữ liệu QR
            $qrData = decrypt($request->qr_data);

            // Tìm vé
            $booking = DatVe::with(['chuyenXe.nhaXe', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'user'])
                ->find($qrData['booking_id']);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy vé này trong hệ thống!'
                ], 404);
            }

            // Kiểm tra mã vé khớp
            if ($booking->ma_ve !== $qrData['ticket_code']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã vé không khớp! Vé có thể đã bị giả mạo.'
                ], 400);
            }

            // Kiểm tra trạng thái vé
            if (!$booking->canBeScanned()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vé này không thể soát! Trạng thái: ' . $booking->trang_thai
                ], 400);
            }

            // Kiểm tra vé đã được soát chưa
            $alreadyScanned = DB::table('ticket_scans')
                ->where('booking_id', $booking->id)
                ->first();

            // Chuẩn bị thông tin vé
            $ticketInfo = [
                'booking_id' => $booking->id,
                'ticket_code' => $booking->ma_ve,
                'status' => $booking->trang_thai,
                'seats' => $booking->so_ghe,
                'booking_date' => $booking->ngay_dat->format('d/m/Y H:i'),
                'passenger' => [
                    'name' => $booking->user->fullname ?? $booking->user->username,
                    'phone' => $booking->user->phone ?? 'N/A',
                    'email' => $booking->user->email ?? 'N/A',
                ],
                'trip' => [
                    'trip_id' => $booking->chuyenXe->id,
                    'trip_code' => $booking->chuyenXe->ma_xe ?? 'N/A',
                    'name' => $booking->chuyenXe->ten_xe ?? ($booking->chuyenXe->ma_xe ?? 'Không có tên'),
                    'driver' => $booking->chuyenXe->ten_tai_xe ?? 'N/A',
                    'driver_phone' => $booking->chuyenXe->sdt_tai_xe ?? 'N/A',
                    'from' => $booking->chuyenXe->tramDi->ten_tram ?? $booking->chuyenXe->diem_di,
                    'to' => $booking->chuyenXe->tramDen->ten_tram ?? $booking->chuyenXe->diem_den,
                    'departure_date' => $booking->chuyenXe->ngay_di,
                    'departure_time' => $booking->chuyenXe->gio_di,
                    'arrival_time' => $booking->chuyenXe->gio_den ?? 'N/A',
                    'vehicle_type' => $booking->chuyenXe->loai_xe ?? 'N/A',
                    'total_seats' => $booking->chuyenXe->so_cho ?? 'N/A',
                    'price' => number_format($booking->chuyenXe->gia_ve) . 'đ',
                    'company' => $booking->chuyenXe->nhaXe->ten_nha_xe ?? 'N/A',
                ],
                'already_scanned' => $alreadyScanned ? true : false,
                'scanned_info' => $alreadyScanned ? [
                    'scanned_at' => \Carbon\Carbon::parse($alreadyScanned->scanned_at)->format('d/m/Y H:i:s'),
                    'scanned_by' => $alreadyScanned->staff_name ?? 'N/A',
                ] : null
            ];

            return response()->json([
                'success' => true,
                'message' => $alreadyScanned ? 'Vé đã được soát trước đó!' : 'Vé hợp lệ!',
                'ticket' => $ticketInfo
            ]);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mã QR không hợp lệ! Không thể giải mã.'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Ticket Scanner Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Check-in vé (đánh dấu đã soát)
    public function checkIn(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:dat_ve,id',
                'qr_data' => 'required|string'
            ]);

            // Giải mã lại để chắc chắn
            $qrData = decrypt($request->qr_data);

            $booking = DatVe::find($request->booking_id);

            if (!$booking || !$booking->canBeScanned()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể check-in vé này!'
                ], 400);
            }

            // Kiểm tra đã soát chưa
            $alreadyScanned = DB::table('ticket_scans')
                ->where('booking_id', $booking->id)
                ->first();

            if ($alreadyScanned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vé này đã được soát lúc: ' . 
                        \Carbon\Carbon::parse($alreadyScanned->scanned_at)->format('d/m/Y H:i:s')
                ], 400);
            }

            // Lưu thông tin soát vé
            DB::table('ticket_scans')->insert([
                'booking_id' => $booking->id,
                'ticket_code' => $booking->ma_ve,
                'staff_id' => auth()->id(),
                'staff_name' => auth()->user()->fullname ?? auth()->user()->username,
                'scanned_at' => now(),
                'scan_location' => $request->ip(),
            ]);

            // Cập nhật trạng thái vé thành "Đã xác nhận" nếu đang "Đã đặt"
            if ($booking->trang_thai === 'Đã đặt') {
                $booking->update(['trang_thai' => 'Đã xác nhận']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công! Khách đã lên xe.',
                'scanned_at' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('Ticket Check-in Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xem danh sách hành khách theo chuyến xe
    public function tripPassengers($tripId)
    {
        try {
            $trip = \App\Models\ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
                ->findOrFail($tripId);

            // Lấy tất cả vé của chuyến này
            $bookings = DatVe::with('user')
                ->where('chuyen_xe_id', $tripId)
                ->whereIn('trang_thai', ['Đã đặt', 'Đã thanh toán', 'Đã xác nhận'])
                ->orderBy('ngay_dat', 'desc')
                ->get();

            // Kiểm tra vé nào đã check-in
            $scannedBookingIds = DB::table('ticket_scans')
                ->whereIn('booking_id', $bookings->pluck('id'))
                ->pluck('booking_id')
                ->toArray();

            $passengers = $bookings->map(function($booking) use ($scannedBookingIds) {
                return [
                    'booking_id' => $booking->id,
                    'ticket_code' => $booking->ma_ve,
                    'passenger_name' => $booking->user->fullname ?? $booking->user->username,
                    'passenger_phone' => $booking->user->phone ?? 'N/A',
                    'seats' => $booking->so_ghe,
                    'status' => $booking->trang_thai,
                    'checked_in' => in_array($booking->id, $scannedBookingIds),
                    'booking_date' => $booking->ngay_dat->format('d/m/Y H:i'),
                ];
            });

            $stats = [
                'total_bookings' => $bookings->count(),
                'checked_in' => count($scannedBookingIds),
                'not_checked_in' => $bookings->count() - count($scannedBookingIds),
            ];

            return view('AdminLTE.staff.ticket_scanner.trip_passengers', compact('trip', 'passengers', 'stats'));

        } catch (\Exception $e) {
            Log::error('Trip Passengers Error: ' . $e->getMessage());
            return back()->with('error', 'Không tìm thấy chuyến xe!');
        }
    }

    // Xem danh sách các chuyến xe hôm nay
    public function todayTrips()
    {
        $trips = \App\Models\ChuyenXe::with(['nhaXe', 'tramDi', 'tramDen'])
            ->whereDate('ngay_di', today())
            ->orderBy('gio_di', 'asc')
            ->paginate(10);

        $tripsWithStats = $trips->getCollection()->map(function($trip) {
            $totalBookings = DatVe::where('chuyen_xe_id', $trip->id)
                ->whereIn('trang_thai', ['Đã đặt', 'Đã thanh toán', 'Đã xác nhận'])
                ->count();

            $checkedIn = DB::table('ticket_scans')
                ->whereIn('booking_id', function($query) use ($trip) {
                    $query->select('id')
                        ->from('dat_ve')
                        ->where('chuyen_xe_id', $trip->id);
                })
                ->whereDate('scanned_at', today())
                ->count();

            return [
                'trip' => $trip,
                'total_bookings' => $totalBookings,
                'checked_in' => $checkedIn,
                'not_checked_in' => $totalBookings - $checkedIn,
            ];
        });

        $trips->setCollection($tripsWithStats);

        return view('AdminLTE.staff.ticket_scanner.today_trips', ['tripsWithStats' => $trips]);
    }
}
