@extends('layouts.admin')

@section('title', 'Thanh toán nâng cấp')

@section('page-title', 'Thanh toán phí nâng cấp')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.upgrade.index') }}">Nâng cấp</a></li>
<li class="breadcrumb-item active">Thanh toán</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Thông tin thanh toán</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Thông tin yêu cầu</h5>
                        <p><strong>Mã yêu cầu:</strong> #{{ $upgradeRequest->id }}</p>
                        <p><strong>Loại:</strong> Nâng cấp lên Nhà xe</p>
                        <p><strong>Ngày tạo:</strong> {{ $upgradeRequest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Số tiền cần thanh toán</h5>
                        <h2 class="text-success">MIỄN PHÍ (0đ)</h2>
                        <p><strong>Trạng thái:</strong> <span class="badge {{ $payment->getStatusBadgeClass() }}">{{ $payment->getStatusLabel() }}</span></p>
                    </div>
                </div>

                @if($payment->status === 'pending')
                
                @if($payment->amount > 0)
                <div class="row">
                    <div class="col-md-6">
                        <h5>Quét mã QR để thanh toán</h5>
                        <div class="text-center bg-light p-3 rounded">
                            <img src="{{ $payment->qr_code_url }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                            <p class="mt-2 mb-0 text-muted">Quét mã QR bằng ứng dụng ngân hàng</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Hoặc chuyển khoản</h5>
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <p class="mb-2"><strong>Ngân hàng:</strong> {{ $payment->bank_name }}</p>
                                <p class="mb-2"><strong>Số tài khoản:</strong> 
                                    <span class="text-primary">{{ $payment->account_number }}</span>
                                    <button class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('{{ $payment->account_number }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </p>
                                <p class="mb-2"><strong>Chủ tài khoản:</strong> {{ $payment->account_name }}</p>
                                <p class="mb-2"><strong>Số tiền:</strong> 
                                    <span class="text-danger">{{ number_format($payment->amount) }}đ</span>
                                </p>
                                <p class="mb-0"><strong>Nội dung:</strong> 
                                    <span class="text-info">UPG {{ $payment->transaction_id }}</span>
                                    <button class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('UPG {{ $payment->transaction_id }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Vui lòng ghi <strong>chính xác nội dung</strong> để hệ thống tự động xác nhận thanh toán
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5>Upload chứng từ thanh toán (tùy chọn)</h5>
                <form action="{{ route('user.upgrade.upload-proof', $upgradeRequest->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="payment_proof">Ảnh chụp chứng từ thanh toán</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('payment_proof') is-invalid @enderror" 
                                   id="payment_proof" name="payment_proof" accept="image/*">
                            <label class="custom-file-label" for="payment_proof">Chọn ảnh...</label>
                        </div>
                        <small class="form-text text-muted">Hỗ trợ: JPG, PNG. Tối đa 5MB</small>
                        @error('payment_proof')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload mr-2"></i>Upload chứng từ
                    </button>
                </form>
                @else
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle mr-2"></i>Nâng cấp MIỄN PHÍ</h5>
                    <p class="mb-0">Bạn không cần thanh toán. Nhấn nút bên dưới để hoàn tất yêu cầu nâng cấp.</p>
                </div>
                @endif

                <div class="mt-4">
                    <form action="{{ route('user.upgrade.confirm-payment', $upgradeRequest->id) }}" method="POST" 
                          onsubmit="return confirm('{{ $payment->amount > 0 ? 'Bạn đã thanh toán xong chưa?' : 'Xác nhận hoàn tất yêu cầu nâng cấp?' }}')">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check-circle mr-2"></i>{{ $payment->amount > 0 ? 'Tôi đã thanh toán' : 'Hoàn tất yêu cầu' }}
                        </button>
                    </form>
                    <small class="text-muted d-block mt-2">
                        {{ $payment->amount > 0 ? 'Nhấn nút này sau khi bạn đã hoàn tất thanh toán. Admin sẽ xác nhận trong thời gian sớm nhất.' : 'Nhấn để gửi yêu cầu đến admin xét duyệt.' }}
                    </small>
                </div>
                @else
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle mr-2"></i>Đã xác nhận thanh toán</h5>
                    <p class="mb-0">Yêu cầu của bạn đang được xử lý. Vui lòng chờ admin phê duyệt.</p>
                    @if($payment->paid_at)
                    <p class="mb-0"><strong>Thời gian thanh toán:</strong> {{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>

                @if($payment->payment_proof)
                <div class="mt-3">
                    <h5>Chứng từ đã upload</h5>
                    <img src="{{ $payment->payment_proof }}" alt="Payment Proof" class="img-fluid rounded" style="max-width: 400px;">
                </div>
                @endif
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('user.upgrade.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Hướng dẫn thanh toán -->
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Hướng dẫn thanh toán</h3>
            </div>
            <div class="card-body">
                <ol class="pl-3">
                    <li class="mb-3">
                        <strong>Quét mã QR:</strong><br>
                        Mở app ngân hàng → Chọn Quét QR → Quét mã bên trái
                    </li>
                    <li class="mb-3">
                        <strong>Hoặc chuyển khoản:</strong><br>
                        Nhập thông tin chuyển khoản như bên trái. Nhớ ghi đúng nội dung!
                    </li>
                    <li class="mb-3">
                        <strong>Xác nhận:</strong><br>
                        Nhấn "Tôi đã thanh toán" sau khi chuyển tiền
                    </li>
                    <li class="mb-0">
                        <strong>Chờ duyệt:</strong><br>
                        Admin sẽ xác nhận trong 1-2 ngày làm việc
                    </li>
                </ol>
            </div>
        </div>

        <!-- Thông tin giao dịch -->
        <div class="card">
            <div class="card-header bg-secondary">
                <h3 class="card-title">Thông tin giao dịch</h3>
            </div>
            <div class="card-body">
                <p><strong>Mã GD:</strong> {{ $payment->transaction_id }}</p>
                <p><strong>Phương thức:</strong> {{ $payment->getPaymentMethodLabel() }}</p>
                <p><strong>Ngày tạo:</strong> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
                @if($payment->paid_at)
                <p class="mb-0"><strong>Ngày thanh toán:</strong> {{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- Hỗ trợ -->
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Cần hỗ trợ?</h3>
            </div>
            <div class="card-body">
                <p class="mb-2"><i class="fas fa-phone mr-2"></i>Hotline: 1900-xxxx</p>
                <p class="mb-2"><i class="fas fa-envelope mr-2"></i>Email: support@buscity.vn</p>
                <p class="mb-0"><i class="fas fa-clock mr-2"></i>8:00 - 22:00 hàng ngày</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('Đã copy: ' + text);
    }, function(err) {
        toastr.error('Không thể copy');
    });
}

// Preview image before upload
document.getElementById('payment_proof').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var label = e.target.nextElementSibling;
    label.textContent = fileName;
});
</script>
@endpush
