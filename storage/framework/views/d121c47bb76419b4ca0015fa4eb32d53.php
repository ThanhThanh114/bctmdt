<?php $__env->startSection('title', 'Thông tin tài khoản'); ?>

<?php $__env->startSection('page-title', 'Thông tin tài khoản'); ?>
<?php $__env->startSection('breadcrumb', 'Hồ sơ'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Profile Information Card -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-2"></i>Thông tin cá nhân
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="profile-avatar-large">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4 class="mt-3"><?php echo e($user->fullname); ?></h4>
                        <p class="text-muted"><?php echo e($user->role); ?></p>
                        
                        
                        <span class="badge badge-success">
                            <i class="fas fa-user-check"></i> Hoạt động
                        </span>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tên đăng nhập:</strong></td>
                                <td><?php echo e($user->username); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo e($user->email); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Số điện thoại:</strong></td>
                                <td><?php echo e($user->phone); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ:</strong></td>
                                <td><?php echo e($user->address ?? 'Chưa cập nhật'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày sinh:</strong></td>
                                <td><?php echo e($user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : 'Chưa cập nhật'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Giới tính:</strong></td>
                                <td><?php echo e($user->gender ?? 'Chưa cập nhật'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo tài khoản:</strong></td>
                                <td><?php echo e($user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : 'Chưa cập nhật'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Lần đăng nhập cuối:</strong></td>
                                <td><?php echo e($user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') : 'Chưa cập nhật'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-2"></i>Thống kê tài khoản
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if(strtolower($user->role) === 'user'): ?>
                    <div class="col-md-4">
                        <div class="info-box bg-primary">
                            <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tổng vé đã đặt</span>
                                <span class="info-box-number"><?php echo e($user->datVe()->count()); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Vé đã xác nhận</span>
                                <span class="info-box-number"><?php echo e($user->datVe()->whereStatus('confirmed')->count()); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tổng chi tiêu</span>
                                <span class="info-box-number"><?php echo e(number_format($user->datVe()->whereStatus('confirmed')->get()->sum(function($booking) { return $booking->chuyenXe->gia_ve ?? 0; }))); ?>đ</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(strtolower($user->role) === 'staff'): ?>
                    <div class="col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đặt vé chờ xử lý</span>
                                <span class="info-box-number"><?php echo e(App\Models\DatVe::whereStatus('pending')->count()); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đặt vé hôm nay</span>
                                <span class="info-box-number"><?php echo e(App\Models\DatVe::whereDate('created_at', date('Y-m-d'))->count()); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(strtolower($user->role) === 'bus_owner'): ?>
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-bus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Chuyến xe</span>
                                <span class="info-box-number"><?php echo e($user->nhaXe ? $user->nhaXe->chuyenXe()->count() : 0); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Doanh thu tháng</span>
                                <span class="info-box-number"><?php echo e(number_format($user->nhaXe ? $user->nhaXe->chuyenXe()->whereMonth('ngay_di', date('m'))->get()->sum(function($trip) { return $trip->datVe()->whereStatus('confirmed')->get()->sum(function($booking) { return $booking->chuyenXe->gia_ve ?? 0; }); }) : 0)); ?>đ</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Settings -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>Thao tác nhanh
                </h3>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-warning btn-block">
                    <i class="fas fa-edit mr-2"></i> Chỉnh sửa thông tin
                </a>

                <a href="<?php echo e(route('password.edit')); ?>" class="btn btn-info btn-block">
                    <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                </a>

                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Status -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Trạng thái tài khoản
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Email xác thực:</span>
                    
                    
                    <span class="badge badge-success">Đang hoạt động</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Vai trò:</span>
                    <span class="badge badge-info"><?php echo e($user->role); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Trạng thái:</span>
                    <span class="badge badge-success">Hoạt động</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Ngày tham gia:</span>
                    <span><?php echo e(\Carbon\Carbon::parse($user->created_at)->format('d/m/Y')); ?></span>
                </div>
            </div>
        </div>

        <?php if(strtolower($user->role) === 'bus_owner' && $user->nhaXe): ?>
        <!-- Bus Company Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bus mr-2"></i>Thông tin nhà xe
                </h3>
            </div>
            <div class="card-body">
                <h5 class="text-primary"><?php echo e($user->nhaXe->name); ?></h5>
                <p class="text-muted"><?php echo e($user->nhaXe->description ?? 'Chưa có mô tả'); ?></p>
                <div class="d-flex justify-content-between mb-2">
                    <span>Số chuyến:</span>
                    <span class="badge badge-primary"><?php echo e($user->nhaXe->chuyenXe()->count()); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Đánh giá:</span>
                    <span class="badge badge-success">
                        <i class="fas fa-star"></i> <?php echo e(number_format($user->nhaXe->rating ?? 5, 1)); ?>

                    </span>
                </div>
                <a href="<?php echo e(route('bus-owner.trips.index')); ?>" class="btn btn-sm btn-primary btn-block">
                    Quản lý chuyến xe
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .profile-avatar-large {
        margin-bottom: 20px;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border-radius: 0.25rem;
        margin-bottom: 1rem;
        background-color: #fff;
        display: block;
    }

    .info-box .info-box-icon {
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0.25rem;
        display: block;
        float: left;
        height: 70px;
        width: 70px;
        text-align: center;
        font-size: 30px;
        line-height: 70px;
        background-color: rgba(0, 0, 0, .2);
    }

    .info-box .info-box-content {
        padding: 5px 10px;
        margin-left: 70px;
    }

    .info-box .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .info-box .info-box-number {
        display: block;
        font-weight: 700;
        font-size: 18px;
    }

    .bg-primary {
        background-color: #007bff !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/profile/show.blade.php ENDPATH**/ ?>