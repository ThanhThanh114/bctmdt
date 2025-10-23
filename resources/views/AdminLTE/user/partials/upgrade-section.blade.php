<!-- Nâng cấp lên Nhà xe -->
@if($user->isUser())
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-star mr-2"></i>Nâng cấp tài khoản</h3>
    </div>
    <div class="card-body">
        @if($user->hasPendingUpgradeRequest())
            @php $activeRequest = $user->getActiveUpgradeRequest(); @endphp
            <div class="alert alert-info mb-3">
                <h5><i class="fas fa-info-circle mr-2"></i>Yêu cầu đang xử lý</h5>
                <p class="mb-2"><strong>Trạng thái:</strong>
                    <span class="badge {{ $activeRequest->getStatusBadgeClass() }}">
                        {{ $activeRequest->getStatusLabel() }}
                    </span>
                </p>
                <p class="mb-0"><strong>Ngày tạo:</strong> {{ $activeRequest->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('user.upgrade.index') }}" class="btn btn-info btn-block">
                <i class="fas fa-eye mr-2"></i>Xem chi tiết
            </a>
        @else
            <h5 class="text-primary">Trở thành Nhà xe!</h5>
            <p class="text-muted">Nâng cấp để quản lý chuyến xe, nhân viên và nhiều tính năng khác.</p>
            <ul class="pl-3 mb-3">
                <li>Quản lý nhà xe</li>
                <li>Thêm/sửa chuyến xe</li>
                <li>Quản lý nhân viên</li>
                <li>Báo cáo thống kê</li>
            </ul>
            <div class="text-center mb-3">
                <h3 class="text-success mb-0">MIỄN PHÍ</h3>
                <small class="text-muted">Nâng cấp hoàn toàn miễn phí</small>
            </div>
            <a href="{{ route('user.upgrade.index') }}" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-rocket mr-2"></i>Nâng cấp ngay
            </a>
        @endif
    </div>
</div>
@endif
