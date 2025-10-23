@extends('layouts.admin')

@section('title', 'Nâng cấp tài khoản')

@section('page-title', 'Nâng cấp lên Nhà xe')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Nâng cấp tài khoản</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        @if($activeRequest)
        <!-- Yêu cầu đang xử lý -->
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Yêu cầu nâng cấp đang xử lý</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã yêu cầu:</strong> #{{ $activeRequest->id }}</p>
                        <p><strong>Ngày tạo:</strong> {{ $activeRequest->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Số tiền:</strong> <span class="text-danger">{{ number_format($activeRequest->amount) }}đ</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Trạng thái:</strong> <span class="badge {{ $activeRequest->getStatusBadgeClass() }}">{{ $activeRequest->getStatusLabel() }}</span></p>
                        @if($activeRequest->approved_at)
                        <p><strong>Ngày duyệt:</strong> {{ $activeRequest->approved_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                @if($activeRequest->status === 'payment_pending' || $activeRequest->status === 'paid')
                <div class="mt-3">
                    <a href="{{ route('user.upgrade.payment', $activeRequest->id) }}" class="btn btn-primary">
                        <i class="fas fa-credit-card mr-2"></i>Thanh toán ngay
                    </a>
                    @if($activeRequest->canBeCancelled())
                    <form action="{{ route('user.upgrade.cancel', $activeRequest->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times mr-2"></i>Hủy yêu cầu
                        </button>
                    </form>
                    @endif
                </div>
                @endif

                @if($activeRequest->admin_note)
                <div class="alert alert-info mt-3">
                    <h5><i class="icon fas fa-info"></i> Ghi chú từ Admin:</h5>
                    {{ $activeRequest->admin_note }}
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- Form tạo yêu cầu mới -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Đăng ký nâng cấp lên Nhà xe</h3>
            </div>
            <form action="{{ route('user.upgrade.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle"></i> Thông tin nâng cấp</h5>
                        <ul class="mb-0">
                            <li>Phí nâng cấp: <strong class="text-success">MIỄN PHÍ (0đ)</strong></li>
                            <li>Thời gian xử lý: 1-2 ngày làm việc</li>
                            <li>Quyền lợi: Quản lý nhà xe, chuyến xe, nhân viên</li>
                        </ul>
                    </div>

                    <h5 class="mt-4 mb-3">Thông tin doanh nghiệp</h5>

                    <div class="form-group">
                        <label for="company_name">Tên công ty/Nhà xe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                        @error('company_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_code">Mã số thuế</label>
                                <input type="text" class="form-control @error('tax_code') is-invalid @enderror" 
                                       id="tax_code" name="tax_code" value="{{ old('tax_code') }}">
                                @error('tax_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_phone">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $user->phone) }}" required>
                                @error('contact_phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="business_address">Địa chỉ kinh doanh <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('business_address') is-invalid @enderror" 
                               id="business_address" name="business_address" value="{{ old('business_address') }}" required>
                        @error('business_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact_email">Email liên hệ <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                               id="contact_email" name="contact_email" value="{{ old('contact_email', $user->email) }}" required>
                        @error('contact_email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reason">Lý do nâng cấp <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                  id="reason" name="reason" rows="4" required>{{ old('reason') }}</textarea>
                        <small class="form-text text-muted">Vui lòng mô tả rõ lý do bạn muốn nâng cấp tài khoản (tối thiểu 20 ký tự)</small>
                        @error('reason')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>Gửi yêu cầu nâng cấp
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
        @endif

        <!-- Lịch sử yêu cầu -->
        @if($upgradeRequests->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch sử yêu cầu nâng cấp</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã YC</th>
                            <th>Ngày tạo</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upgradeRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($request->amount) }}đ</td>
                            <td><span class="badge {{ $request->getStatusBadgeClass() }}">{{ $request->getStatusLabel() }}</span></td>
                            <td>
                                <a href="{{ route('user.upgrade.show', $request->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Hướng dẫn -->
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Hướng dẫn nâng cấp</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div>
                        <i class="fas fa-edit bg-primary"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Bước 1: Điền thông tin</h3>
                            <div class="timeline-body">
                                Điền đầy đủ thông tin doanh nghiệp và lý do nâng cấp
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-gift bg-success"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Bước 2: Thanh toán</h3>
                            <div class="timeline-body">
                                <strong class="text-success">MIỄN PHÍ (0đ)</strong> - Không cần thanh toán, chỉ cần gửi yêu cầu và chờ admin duyệt
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-clock bg-info"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Bước 3: Chờ duyệt</h3>
                            <div class="timeline-body">
                                Admin sẽ xem xét và phê duyệt trong 1-2 ngày làm việc
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-check bg-success"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">Bước 4: Hoàn tất</h3>
                            <div class="timeline-body">
                                Tài khoản được nâng cấp lên Nhà xe và có đầy đủ quyền quản lý
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quyền lợi -->
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Quyền lợi Nhà xe</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Quản lý nhà xe</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Thêm/sửa/xóa chuyến xe</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Quản lý nhân viên</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Xem báo cáo thống kê</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Quản lý đặt vé</li>
                    <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Hỗ trợ ưu tiên</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
