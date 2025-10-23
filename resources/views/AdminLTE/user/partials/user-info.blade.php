<!-- User Info -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thông tin cá nhân</h3>
    </div>
    <div class="card-body">
        <div class="text-center mb-3">
            <i class="fas fa-user-circle fa-4x text-primary"></i>
        </div>
        <h5 class="text-center">{{ $user->fullname ?? $user->username }}</h5>
        <p class="text-center text-muted">{{ $user->email }}</p>
        <p class="text-center">
            <span class="badge badge-{{ $user->role === 'User' ? 'info' : 'success' }} badge-lg">
                {{ $user->role }}
            </span>
        </p>

        <div class="d-flex justify-content-between mb-2">
            <span>Số điện thoại:</span>
            <span>{{ $user->phone ?? 'Chưa cập nhật' }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span>Ngày sinh:</span>
            <span>{{ $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Địa chỉ:</span>
            <span>{{ $user->address ?? 'Chưa cập nhật' }}</span>
        </div>
    </div>
</div>
