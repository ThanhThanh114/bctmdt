@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu nâng cấp')

@section('page-title', 'Chi tiết yêu cầu #' . $upgradeRequest->id)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.upgrade.index') }}">Nâng cấp</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin yêu cầu</h3>
                <div class="card-tools">
                    <span class="badge {{ $upgradeRequest->getStatusBadgeClass() }} badge-lg">
                        {{ $upgradeRequest->getStatusLabel() }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã yêu cầu:</strong> #{{ $upgradeRequest->id }}</p>
                        <p><strong>Loại nâng cấp:</strong> {{ $upgradeRequest->request_type }}</p>
                        <p><strong>Ngày tạo:</strong> {{ $upgradeRequest->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Số tiền:</strong> <span class="text-danger">{{ number_format($upgradeRequest->amount) }}đ</span></p>
                    </div>
                    <div class="col-md-6">
                        @if($upgradeRequest->approved_at)
                        <p><strong>Ngày duyệt:</strong> {{ $upgradeRequest->approved_at->format('d/m/Y H:i') }}</p>
                        @endif
                        @if($upgradeRequest->rejected_at)
                        <p><strong>Ngày từ chối:</strong> {{ $upgradeRequest->rejected_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                <hr>

                <h5>Lý do nâng cấp</h5>
                <p class="text-muted">{{ $upgradeRequest->reason }}</p>

                @if($upgradeRequest->business_info)
                <hr>
                <h5>Thông tin doanh nghiệp</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tên công ty:</strong> {{ $upgradeRequest->business_info['company_name'] ?? 'N/A' }}</p>
                        <p><strong>Mã số thuế:</strong> {{ $upgradeRequest->business_info['tax_code'] ?? 'N/A' }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $upgradeRequest->business_info['business_address'] ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>SĐT liên hệ:</strong> {{ $upgradeRequest->business_info['contact_phone'] ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $upgradeRequest->business_info['contact_email'] ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif

                @if($upgradeRequest->admin_note)
                <hr>
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle mr-2"></i>Ghi chú từ Admin</h5>
                    <p class="mb-0">{{ $upgradeRequest->admin_note }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($payment)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin thanh toán</h3>
                <div class="card-tools">
                    <span class="badge {{ $payment->getStatusBadgeClass() }} badge-lg">
                        {{ $payment->getStatusLabel() }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã giao dịch:</strong> {{ $payment->transaction_id }}</p>
                        <p><strong>Số tiền:</strong> {{ number_format($payment->amount) }}đ</p>
                        <p><strong>Phương thức:</strong> {{ $payment->getPaymentMethodLabel() }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ngân hàng:</strong> {{ $payment->bank_name }}</p>
                        <p><strong>Số TK:</strong> {{ $payment->account_number }}</p>
                        @if($payment->paid_at)
                        <p><strong>Ngày thanh toán:</strong> {{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                @if($payment->payment_proof)
                <hr>
                <h5>Chứng từ thanh toán</h5>
                <div class="text-center">
                    <img src="{{ $payment->payment_proof }}" alt="Payment Proof" class="img-fluid rounded" style="max-width: 500px;">
                </div>
                @endif

                @if($payment->status === 'pending')
                <div class="mt-3">
                    <a href="{{ route('user.upgrade.payment', $upgradeRequest->id) }}" class="btn btn-primary">
                        <i class="fas fa-credit-card mr-2"></i>Thanh toán ngay
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hành động</h3>
            </div>
            <div class="card-body">
                @if($upgradeRequest->status === 'payment_pending' || $upgradeRequest->status === 'paid')
                <a href="{{ route('user.upgrade.payment', $upgradeRequest->id) }}" class="btn btn-primary btn-block">
                    <i class="fas fa-credit-card mr-2"></i>Thanh toán
                </a>
                @endif

                @if($upgradeRequest->canBeCancelled())
                <form action="{{ route('user.upgrade.cancel', $upgradeRequest->id) }}" method="POST" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-times mr-2"></i>Hủy yêu cầu
                    </button>
                </form>
                @endif

                <a href="{{ route('user.upgrade.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-inverse">
                    <div class="time-label">
                        <span class="bg-primary">{{ $upgradeRequest->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <i class="fas fa-file bg-primary"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $upgradeRequest->created_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Yêu cầu được tạo</h3>
                        </div>
                    </div>

                    @if($payment && $payment->paid_at)
                    <div>
                        <i class="fas fa-credit-card bg-success"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $payment->paid_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Đã thanh toán</h3>
                        </div>
                    </div>
                    @endif

                    @if($upgradeRequest->approved_at)
                    <div>
                        <i class="fas fa-check bg-success"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $upgradeRequest->approved_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Được phê duyệt</h3>
                        </div>
                    </div>
                    @endif

                    @if($upgradeRequest->rejected_at)
                    <div>
                        <i class="fas fa-times bg-danger"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $upgradeRequest->rejected_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Bị từ chối</h3>
                        </div>
                    </div>
                    @endif

                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
