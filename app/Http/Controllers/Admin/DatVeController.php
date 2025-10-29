<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\User;
use App\Models\ChuyenXe;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DatVeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DatVe::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe']);

        // Tìm kiếm theo mã vé
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ma_ve', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('fullname', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai') && $request->trang_thai !== 'all') {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo ngày
        if ($request->filled('from_date')) {
            $query->whereDate('ngay_dat', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('ngay_dat', '<=', $request->to_date);
        }

        $datVe = $query->orderBy('ngay_dat', 'desc')->paginate(15);

        // Thống kê
        $stats = [
            'total' => DatVe::count(),
            'da_dat' => DatVe::where('trang_thai', 'Đã đặt')->count(),
            'da_thanh_toan' => DatVe::where('trang_thai', 'Đã thanh toán')->count(),
            'da_huy' => DatVe::where('trang_thai', 'Đã hủy')->count(),
        ];

        return view('AdminLTE.admin.dat_ve.index', compact('datVe', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DatVe $datve)
    {
        $datve->load(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe', 'khuyenMais']);

        // Tính tổng tiền
        $totalAmount = 0;
        if ($datve->chuyenXe) {
            $seats = explode(',', $datve->so_ghe);
            $seatCount = count(array_filter(array_map('trim', $seats)));

            // Làm sạch giá vé (loại bỏ dấu chấm và chuyển thành số)
            $rawPrice = $datve->chuyenXe->gia_ve;
            $cleanPrice = preg_replace('/[^0-9\.]/', '', (string)$rawPrice);
            $pricePerSeat = $cleanPrice === '' ? 0.0 : (float)$cleanPrice;

            $totalAmount = $pricePerSeat * $seatCount;

            // Áp dụng khuyến mãi
            foreach ($datve->khuyenMais as $km) {
                $discount = ($totalAmount * $km->giam_gia) / 100;
                $totalAmount -= $discount;
            }
        }

        // Sử dụng tên biến $datVe để khớp với view
        $datVe = $datve;

        return view('AdminLTE.admin.dat_ve.show', compact('datVe', 'totalAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('fullname')->get();
        $chuyenXes = ChuyenXe::with(['tramDi', 'tramDen', 'nhaXe'])
            ->orderBy('ngay_di', 'desc')
            ->get();

        return view('AdminLTE.admin.dat_ve.create', compact('users', 'chuyenXes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chuyen_xe_id' => 'required|exists:chuyen_xe,id',
            'so_ghe' => 'required|string',
            'trang_thai' => 'required|in:Đã đặt,Đã thanh toán,Đã hủy',
        ]);

        // Tạo mã vé tự động
        $ma_ve = 'BK' . date('Ymd') . str_pad(DatVe::count() + 1, 8, '0', STR_PAD_LEFT);

        $datVe = DatVe::create([
            'user_id' => $validated['user_id'],
            'chuyen_xe_id' => $validated['chuyen_xe_id'],
            'ma_ve' => $ma_ve,
            'so_ghe' => $validated['so_ghe'],
            'trang_thai' => $validated['trang_thai'],
            'ngay_dat' => now(),
        ]);

        return redirect()->route('admin.datve.show', $datVe->id)
            ->with('success', 'Đã tạo đặt vé thành công!');
    }

    /**
     * Update booking status - Chỉ cho phép thay đổi nếu chưa thanh toán
     */
    public function updateStatus(Request $request, DatVe $datve)
    {
        // Không cho phép thay đổi nếu đã thanh toán
        if ($datve->trang_thai === 'Đã thanh toán') {
            return redirect()->back()->with('error', 'Không thể thay đổi trạng thái vé đã thanh toán!');
        }

        $validated = $request->validate([
            'trang_thai' => 'required|in:Đã đặt,Đã thanh toán,Đã hủy',
        ]);

        $datve->update($validated);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Cancel booking - Chỉ hủy nếu chưa thanh toán
     */
    public function cancel(DatVe $datve)
    {
        // Không cho phép hủy nếu đã thanh toán
        if ($datve->trang_thai === 'Đã thanh toán') {
            return redirect()->back()->with('error', 'Không thể hủy vé đã thanh toán!');
        }

        $datve->update(['trang_thai' => 'Đã hủy']);

        return redirect()->back()->with('success', 'Đã hủy vé thành công!');
    }

    /**
     * Remove the specified resource from storage (soft delete by changing status)
     */
    public function destroy(DatVe $datve)
    {
        // Không cho phép xóa nếu đã thanh toán
        if ($datve->trang_thai === 'Đã thanh toán') {
            return redirect()->back()->with('error', 'Không thể hủy vé đã thanh toán!');
        }

        // Chuyển trạng thái thành "Đã hủy" thay vì xóa
        $datve->update(['trang_thai' => 'Đã hủy']);

        return redirect()->route('admin.datve.index')->with('success', 'Đã hủy vé thành công!');
    }

    /**
     * Export booking data to Excel
     */
    public function export(Request $request)
    {
        $query = DatVe::with(['user', 'chuyenXe.tramDi', 'chuyenXe.tramDen', 'chuyenXe.nhaXe']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ma_ve', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('fullname', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('trang_thai') && $request->trang_thai !== 'all') {
            $query->where('trang_thai', $request->trang_thai);
        }

        $bookings = $query->orderBy('ngay_dat', 'desc')->get();

        // Create CSV
        $filename = 'danh_sach_dat_ve_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, [
                'Mã vé',
                'Khách hàng',
                'Email',
                'Số điện thoại',
                'Chuyến xe',
                'Số ghế',
                'Giá vé',
                'Tổng tiền',
                'Ngày đặt',
                'Trạng thái'
            ]);

            // Data
            foreach ($bookings as $booking) {
                $customerName = $booking->user ? $booking->user->fullname : 'N/A';
                $customerEmail = $booking->user ? $booking->user->email : 'N/A';
                $customerPhone = $booking->user ? $booking->user->phone : 'N/A';

                $route = 'N/A';
                $pricePerSeat = 0;
                if ($booking->chuyenXe) {
                    $route = ($booking->chuyenXe->tramDi->ten_tram ?? 'N/A') . ' → ' .
                        ($booking->chuyenXe->tramDen->ten_tram ?? 'N/A');

                    // Làm sạch giá vé (loại bỏ dấu chấm và chuyển thành số)
                    $rawPrice = $booking->chuyenXe->gia_ve;
                    $cleanPrice = preg_replace('/[^0-9\.]/', '', (string)$rawPrice);
                    $pricePerSeat = $cleanPrice === '' ? 0.0 : (float)$cleanPrice;
                }

                $seats = explode(',', $booking->so_ghe);
                $seatCount = count(array_filter(array_map('trim', $seats)));
                $totalAmount = $pricePerSeat * $seatCount;

                fputcsv($file, [
                    $booking->ma_ve,
                    $customerName,
                    $customerEmail,
                    $customerPhone,
                    $route,
                    $booking->so_ghe,
                    number_format($pricePerSeat, 0, ',', '.') . ' VNĐ',
                    number_format($totalAmount, 0, ',', '.') . ' VNĐ',
                    $booking->ngay_dat ? $booking->ngay_dat->format('d/m/Y H:i') : 'N/A',
                    $booking->trang_thai
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Auto cancel expired bookings (chưa thanh toán quá 1 giờ)
     */
    public function autoCancelExpired()
    {
        $oneHourAgo = Carbon::now()->subHour();

        $expiredBookings = DatVe::where('trang_thai', 'Đã đặt')
            ->where('ngay_dat', '<=', $oneHourAgo)
            ->get();

        $canceledCount = 0;
        foreach ($expiredBookings as $booking) {
            $booking->update(['trang_thai' => 'Đã hủy']);
            $canceledCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Đã tự động hủy {$canceledCount} vé quá hạn thanh toán",
            'count' => $canceledCount
        ]);
    }

    /**
     * Statistics page
     */
    public function statistics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        $stats = [
            'today' => DatVe::whereDate('ngay_dat', $today)->count(),
            'this_month' => DatVe::whereMonth('ngay_dat', $thisMonth)
                ->whereYear('ngay_dat', $thisYear)
                ->count(),
            'this_year' => DatVe::whereYear('ngay_dat', $thisYear)->count(),
            'revenue_today' => $this->calculateRevenue(DatVe::whereDate('ngay_dat', $today)->get()),
            'revenue_month' => $this->calculateRevenue(
                DatVe::whereMonth('ngay_dat', $thisMonth)
                    ->whereYear('ngay_dat', $thisYear)
                    ->get()
            ),
            'revenue_year' => $this->calculateRevenue(DatVe::whereYear('ngay_dat', $thisYear)->get()),
        ];

        return view('AdminLTE.admin.dat_ve.statistics', compact('stats'));
    }

    /**
     * Calculate revenue from bookings
     */
    private function calculateRevenue($bookings)
    {
        $total = 0;
        foreach ($bookings as $booking) {
            if ($booking->chuyenXe && $booking->trang_thai !== 'Đã hủy') {
                $seats = explode(',', $booking->so_ghe);
                $seatCount = count(array_filter(array_map('trim', $seats)));

                // Làm sạch giá vé (loại bỏ dấu chấm và chuyển thành số)
                $rawPrice = $booking->chuyenXe->gia_ve;
                $cleanPrice = preg_replace('/[^0-9\.]/', '', (string)$rawPrice);
                $pricePerSeat = $cleanPrice === '' ? 0.0 : (float)$cleanPrice;

                $total += $pricePerSeat * $seatCount;
            }
        }
        return $total;
    }

    /**
     * Xác nhận thanh toán thủ công (bằng tay)
     */
    public function confirmPayment($id)
    {
        try {
            $booking = DatVe::findOrFail($id);

            // Kiểm tra trạng thái hiện tại
            if ($booking->trang_thai === 'Đã thanh toán') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vé này đã được thanh toán trước đó!'
                ]);
            }

            if ($booking->trang_thai === 'Đã hủy') {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xác nhận thanh toán cho vé đã hủy!'
                ]);
            }

            // Cập nhật trạng thái thành "Đã thanh toán"
            $booking->update([
                'trang_thai' => 'Đã thanh toán'
            ]);

            return response()->json([
                'success' => true,
                'message' => "✓ Đã xác nhận thanh toán cho mã vé {$booking->ma_ve}",
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
