<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UpgradeRequest;
use App\Models\Payment;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpgradeController extends Controller
{
    // Hiển thị form yêu cầu nâng cấp
    public function index()
    {
        $user = Auth::user();

        // Kiểm tra nếu user đã là Bus_owner
        if ($user->isBusOwner()) {
            return redirect()->route('user.dashboard')
                ->with('info', 'Bạn đã là nhà xe rồi.');
        }

        // Lấy yêu cầu nâng cấp hiện tại (nếu có)
        $activeRequest = $user->getActiveUpgradeRequest();

        // Lấy tất cả yêu cầu nâng cấp của user
        $upgradeRequests = $user->upgradeRequests()
            ->with('payment')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('AdminLTE.user.upgrade.index', compact('user', 'activeRequest', 'upgradeRequests'));
    }

    // Tạo yêu cầu nâng cấp mới
    public function store(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra nếu đã có yêu cầu đang chờ xử lý
        if ($user->hasPendingUpgradeRequest()) {
            return back()->with('error', 'Bạn đã có yêu cầu nâng cấp đang được xử lý.');
        }

        $request->validate([
            'reason' => 'required|string|min:20|max:1000',
            'company_name' => 'required|string|max:255',
            'tax_code' => 'nullable|string|max:50',
            'business_address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:15',
            'contact_email' => 'required|email|max:100',
        ]);

        $businessInfo = [
            'company_name' => $request->company_name,
            'tax_code' => $request->tax_code,
            'business_address' => $request->business_address,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
        ];

        DB::beginTransaction();
        try {
            // Tạo nhà xe mới tự động
            $nhaXe = NhaXe::create([
                'ten_nha_xe' => $request->company_name,
                'dia_chi' => $request->business_address,
                'so_dien_thoai' => $request->contact_phone,
                'email' => $request->contact_email,
            ]);

            // Lưu ma_nha_xe vào business_info
            $businessInfo['ma_nha_xe'] = $nhaXe->ma_nha_xe;

            // Tạo yêu cầu nâng cấp
            $upgradeRequest = UpgradeRequest::create([
                'user_id' => $user->id,
                'request_type' => 'Bus_owner',
                'amount' => 0, // Phí nâng cấp 0đ (miễn phí)
                'status' => 'payment_pending', // Chuyển luôn sang trạng thái chờ thanh toán
                'reason' => $request->reason,
                'business_info' => $businessInfo,
            ]);

            // Tạo thông tin thanh toán
            $transactionId = 'UPG' . date('Ymd') . $user->id . rand(1000, 9999);

            $payment = Payment::create([
                'upgrade_request_id' => $upgradeRequest->id,
                'transaction_id' => $transactionId,
                'amount' => 0,
                'payment_method' => 'qr_code',
                'status' => 'completed', // Tự động hoàn tất vì miễn phí
                'bank_name' => 'Vietcombank',
                'account_number' => '1234567890',
                'account_name' => 'CONG TY TMDT BUS CITY',
                'notes' => 'Nâng cấp tài khoản lên Nhà xe - MIỄN PHÍ',
                'paid_at' => now(), // Đánh dấu đã thanh toán
            ]);

            // Tạo QR code URL (sử dụng API của VietQR hoặc tương tự)
            $qrContent = $this->generateQRContent($payment);
            $payment->update(['qr_code_url' => $qrContent]);

            // Tự động chuyển sang trạng thái đã thanh toán vì miễn phí
            $upgradeRequest->update(['status' => 'paid']);

            DB::commit();

            return redirect()->route('user.upgrade.payment', $upgradeRequest->id)
                ->with('success', 'Yêu cầu nâng cấp đã được tạo. Nhà xe "' . $request->company_name . '" đã được tạo tự động và đang chờ admin duyệt.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Hiển thị trang thanh toán
    public function payment(UpgradeRequest $upgradeRequest)
    {
        $user = Auth::user();

        // Kiểm tra quyền truy cập
        if ($upgradeRequest->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền truy cập yêu cầu này.');
        }

        // Kiểm tra trạng thái
        if (!in_array($upgradeRequest->status, ['payment_pending', 'paid'])) {
            return redirect()->route('user.upgrade.index')
                ->with('error', 'Yêu cầu này không thể thanh toán.');
        }

        $payment = $upgradeRequest->payment;

        return view('AdminLTE.user.upgrade.payment', compact('upgradeRequest', 'payment'));
    }

    // Upload chứng từ thanh toán
    public function uploadProof(Request $request, UpgradeRequest $upgradeRequest)
    {
        $user = Auth::user();

        if ($upgradeRequest->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        $payment = $upgradeRequest->payment;

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'payment_proof_' . $upgradeRequest->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            $payment->update([
                'payment_proof' => '/storage/' . $path,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Cập nhật trạng thái yêu cầu
            $upgradeRequest->update(['status' => 'paid']);

            return back()->with('success', 'Chứng từ thanh toán đã được gửi. Vui lòng chờ admin xác nhận.');
        }

        return back()->with('error', 'Không thể upload chứng từ thanh toán.');
    }

    // Xác nhận đã thanh toán (không cần upload ảnh)
    public function confirmPayment(UpgradeRequest $upgradeRequest)
    {
        $user = Auth::user();

        if ($upgradeRequest->user_id !== $user->id) {
            abort(403);
        }

        $payment = $upgradeRequest->payment;

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $upgradeRequest->update(['status' => 'paid']);

        return redirect()->route('user.upgrade.index')
            ->with('success', 'Đã xác nhận thanh toán. Vui lòng chờ admin phê duyệt.');
    }

    // Hủy yêu cầu nâng cấp
    public function cancel(UpgradeRequest $upgradeRequest)
    {
        $user = Auth::user();

        if ($upgradeRequest->user_id !== $user->id) {
            abort(403);
        }

        if (!$upgradeRequest->canBeCancelled()) {
            return back()->with('error', 'Không thể hủy yêu cầu này.');
        }

        DB::beginTransaction();
        try {
            // Xóa nhà xe đã tạo tự động (nếu có)
            if (isset($upgradeRequest->business_info['ma_nha_xe'])) {
                $maNhaXe = $upgradeRequest->business_info['ma_nha_xe'];
                $nhaXe = NhaXe::find($maNhaXe);
                
                if ($nhaXe) {
                    // Chỉ xóa nếu nhà xe chưa có dữ liệu liên quan
                    $hasRelatedData = $nhaXe->chuyenXe()->exists() 
                        || $nhaXe->nhanVien()->exists() 
                        || $nhaXe->users()->where('id', '!=', $user->id)->exists();
                    
                    if (!$hasRelatedData) {
                        $nhaXe->delete();
                    }
                }
            }

            $upgradeRequest->update(['status' => 'cancelled']);

            if ($upgradeRequest->payment) {
                $upgradeRequest->payment->update(['status' => 'failed']);
            }

            DB::commit();

            return redirect()->route('user.upgrade.index')
                ->with('success', 'Yêu cầu nâng cấp đã được hủy.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi khi hủy yêu cầu: ' . $e->getMessage());
        }
    }

    // Tạo nội dung QR code
    private function generateQRContent($payment)
    {
        // Sử dụng VietQR API để tạo QR code
        // Format: https://img.vietqr.io/image/{BANK_BIN}-{ACCOUNT_NO}-{TEMPLATE}.png?amount={AMOUNT}&addInfo={INFO}

        $bankBin = '970436'; // Vietcombank
        $accountNo = $payment->account_number;
        $template = 'compact2'; // hoặc 'compact', 'qr_only', 'print'
        $amount = $payment->amount;
        $addInfo = urlencode('UPG ' . $payment->transaction_id);

        $qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$addInfo}&accountName=" . urlencode($payment->account_name);

        return $qrUrl;
    }

    // Xem chi tiết yêu cầu
    public function show(UpgradeRequest $upgradeRequest)
    {
        $user = Auth::user();

        if ($upgradeRequest->user_id !== $user->id) {
            abort(403);
        }

        $payment = $upgradeRequest->payment;

        return view('AdminLTE.user.upgrade.show', compact('upgradeRequest', 'payment'));
    }
}
