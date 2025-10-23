@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu nâng cấp')

@section('page-title', 'Chi tiết yêu cầu #' . $upgradeRequest->id)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.upgrade-requests') }}">Yêu cầu nâng cấp</a></li>
<li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Thông tin yêu cầu -->
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
                        <p><strong>Người duyệt:</strong> {{ $upgradeRequest->approver->fullname ?? 'N/A' }}</p>
                        @endif
                        @if($upgradeRequest->rejected_at)
                        <p><strong>Ngày từ chối:</strong> {{ $upgradeRequest->rejected_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                <hr>

                <h5>Thông tin người dùng</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Họ tên:</strong> {{ $upgradeRequest->user->fullname ?? $upgradeRequest->user->username }}</p>
                        <p><strong>Email:</strong> {{ $upgradeRequest->user->email }}</p>
                        <p><strong>SĐT:</strong> {{ $upgradeRequest->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Role hiện tại:</strong> <span class="badge badge-info">{{ $upgradeRequest->user->role }}</span></p>
                        <p><strong>Ngày đăng ký:</strong> {{ $upgradeRequest->user->created_at ? \Carbon\Carbon::parse($upgradeRequest->user->created_at)->format('d/m/Y') : 'N/A' }}</p>
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
                        @if(isset($upgradeRequest->business_info['ma_nha_xe']))
                        <p><strong>Mã nhà xe đã tạo:</strong> 
                            <span class="badge badge-success">{{ $upgradeRequest->business_info['ma_nha_xe'] }}</span>
                        </p>
                        @endif
                    </div>
                </div>
                @if(isset($upgradeRequest->business_info['ma_nha_xe']))
                <div class="alert alert-success mt-2">
                    <i class="fas fa-check-circle mr-2"></i>
                    Nhà xe <strong>"{{ $upgradeRequest->business_info['company_name'] }}"</strong> đã được tạo tự động khi gửi yêu cầu (Mã: {{ $upgradeRequest->business_info['ma_nha_xe'] }})
                </div>
                @endif
                @endif

                @if($upgradeRequest->admin_note)
                <hr>
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle mr-2"></i>Ghi chú Admin</h5>
                    <p class="mb-0">{{ $upgradeRequest->admin_note }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Thông tin thanh toán -->
        @if($upgradeRequest->payment)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin thanh toán</h3>
                <div class="card-tools">
                    <span class="badge {{ $upgradeRequest->payment->getStatusBadgeClass() }} badge-lg">
                        {{ $upgradeRequest->payment->getStatusLabel() }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã GD:</strong> {{ $upgradeRequest->payment->transaction_id }}</p>
                        <p><strong>Số tiền:</strong> {{ number_format($upgradeRequest->payment->amount) }}đ</p>
                        <p><strong>Phương thức:</strong> {{ $upgradeRequest->payment->getPaymentMethodLabel() }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ngân hàng:</strong> {{ $upgradeRequest->payment->bank_name }}</p>
                        <p><strong>Số TK:</strong> {{ $upgradeRequest->payment->account_number }}</p>
                        @if($upgradeRequest->payment->paid_at)
                        <p><strong>Ngày thanh toán:</strong> {{ $upgradeRequest->payment->paid_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                @if($upgradeRequest->payment->payment_proof)
                <hr>
                <h5>Chứng từ thanh toán</h5>
                <div class="text-center">
                    <a href="{{ $upgradeRequest->payment->payment_proof }}" target="_blank">
                        <img src="{{ $upgradeRequest->payment->payment_proof }}" alt="Payment Proof" class="img-fluid rounded" style="max-width: 500px;">
                    </a>
                    <p class="mt-2"><a href="{{ $upgradeRequest->payment->payment_proof }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="fas fa-external-link-alt mr-1"></i>Xem ảnh gốc
                    </a></p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Form phê duyệt -->
        @if($upgradeRequest->status === 'paid')
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Phê duyệt yêu cầu</h3>
            </div>
            <form action="{{ route('admin.users.approve-upgrade', $upgradeRequest->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    @if(isset($upgradeRequest->business_info['ma_nha_xe']))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Nhà xe đã được tạo tự động (Mã: <strong>{{ $upgradeRequest->business_info['ma_nha_xe'] }}</strong>). 
                        Khi phê duyệt, hệ thống sẽ tự động gán nhà xe này cho user.
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="ma_nha_xe">Gán nhà xe {{ isset($upgradeRequest->business_info['ma_nha_xe']) ? '(tùy chọn - đã có nhà xe tự động)' : '' }}</label>
                        <select name="ma_nha_xe" id="ma_nha_xe" class="form-control">
                            <option value="">{{ isset($upgradeRequest->business_info['ma_nha_xe']) ? '-- Dùng nhà xe đã tạo tự động --' : '-- Chưa gán --' }}</option>
                            @foreach($busCompanies as $company)
                            <option value="{{ $company->ma_nha_xe }}" 
                                {{ isset($upgradeRequest->business_info['ma_nha_xe']) && $company->ma_nha_xe == $upgradeRequest->business_info['ma_nha_xe'] ? 'selected' : '' }}>
                                {{ $company->ten_nha_xe }} (Mã: {{ $company->ma_nha_xe }})
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            {{ isset($upgradeRequest->business_info['ma_nha_xe']) ? 'Để trống để dùng nhà xe đã tạo tự động, hoặc chọn nhà xe khác' : 'Có thể gán sau' }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="admin_note">Ghi chú</label>
                        <textarea name="admin_note" id="admin_note" class="form-control" rows="3" placeholder="Ghi chú cho người dùng (tùy chọn)"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Bạn chắc chắn muốn phê duyệt yêu cầu này?')">
                        <i class="fas fa-check mr-2"></i>Phê duyệt
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title">Từ chối yêu cầu</h3>
            </div>
            <form action="{{ route('admin.users.reject-upgrade', $upgradeRequest->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="form-group">
                        <label for="reject_note">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea name="admin_note" id="reject_note" class="form-control @error('admin_note') is-invalid @enderror" rows="3" required placeholder="Nhập lý do từ chối"></textarea>
                        @error('admin_note')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Tối thiểu 10 ký tự</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn từ chối yêu cầu này?')">
                        <i class="fas fa-times mr-2"></i>Từ chối
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hành động</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.users.show', $upgradeRequest->user) }}" class="btn btn-info btn-block">
                    <i class="fas fa-user mr-2"></i>Xem thông tin User
                </a>
                <a href="{{ route('admin.users.upgrade-requests') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Lịch sử</h3>
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
                            <div class="timeline-body">
                                Bởi: {{ $upgradeRequest->user->fullname ?? $upgradeRequest->user->username }}
                            </div>
                        </div>
                    </div>

                    @if($upgradeRequest->payment && $upgradeRequest->payment->paid_at)
                    <div>
                        <i class="fas fa-credit-card bg-success"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $upgradeRequest->payment->paid_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Đã thanh toán</h3>
                            <div class="timeline-body">
                                {{ number_format($upgradeRequest->payment->amount) }}đ
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($upgradeRequest->approved_at)
                    <div>
                        <i class="fas fa-check bg-success"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $upgradeRequest->approved_at->format('H:i') }}</span>
                            <h3 class="timeline-header">Được phê duyệt</h3>
                            <div class="timeline-body">
                                Bởi: {{ $upgradeRequest->approver->fullname ?? 'Admin' }}
                            </div>
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

        <!-- Thống kê user -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thống kê người dùng</h3>
            </div>
            <div class="card-body">
                <p><strong>Tổng vé đã đặt:</strong> {{ $upgradeRequest->user->datVe()->count() }}</p>
                <p><strong>Vé đã thanh toán:</strong> {{ $upgradeRequest->user->datVe()->where('trang_thai', 'Đã thanh toán')->count() }}</p>
                <p class="mb-0"><strong>Tổng chi tiêu:</strong> 
                    {{ number_format($upgradeRequest->user->datVe()->with('chuyenXe')->get()->where('trang_thai', 'Đã thanh toán')->sum(function($booking) {
                        return $booking->chuyenXe->gia_ve ?? 0;
                    })) }}đ
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
