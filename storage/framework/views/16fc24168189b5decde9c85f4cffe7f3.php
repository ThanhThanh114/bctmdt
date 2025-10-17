<?php $__env->startSection('title', 'Quản lý người dùng'); ?>

<?php $__env->startSection('page-title', 'Quản lý người dùng'); ?>
<?php $__env->startSection('breadcrumb', 'Người dùng'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách người dùng</h3>
                <div class="card-tools">
                    <form method="GET" class="d-flex">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Tìm kiếm..."
                                value="<?php echo e(request('search')); ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <select name="role" class="form-control form-control-sm ml-2" style="width: auto;"
                            onchange="this.form.submit()">
                            <option value="all" <?php echo e(request('role') == 'all' ? 'selected' : ''); ?>>Tất cả vai trò</option>
                            <option value="User" <?php echo e(request('role') == 'User' ? 'selected' : ''); ?>>Người dùng</option>
                            <option value="Staff" <?php echo e(request('role') == 'Staff' ? 'selected' : ''); ?>>Nhân viên</option>
                            <option value="Bus_owner" <?php echo e(request('role') == 'Bus_owner' ? 'selected' : ''); ?>>Nhà xe
                            </option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($user->id); ?></td>
                            <td>
                                <strong><?php echo e($user->username); ?></strong>
                                
                                
                            </td>
                            <td><?php echo e($user->fullname); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->phone); ?></td>
                            <td>
                                <?php if(strtolower($user->role) === 'admin'): ?>
                                <span class="badge badge-danger">Quản trị</span>
                                <?php elseif($user->role === 'Staff'): ?>
                                <span class="badge badge-warning">Nhân viên</span>
                                <?php elseif($user->role === 'Bus_owner'): ?>
                                <span class="badge badge-info">Nhà xe</span>
                                <?php else: ?>
                                <span class="badge badge-success">Người dùng</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                
                                
                                <span class="badge badge-info">Hoạt động</span>
                            </td>
                            <td><?php echo e($user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : 'Chưa cập nhật'); ?>

                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-sm btn-info"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-warning"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($user->role !== 'Admin'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>"
                                        style="display: inline;"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Không tìm thấy người dùng nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($users->hasPages()): ?>
            
        <?php endif; ?>
    </div>
</div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e(App\Models\User::where('role', 'User')->count()); ?></h3>
                <p>Người dùng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?php echo e(route('admin.users.index', ['role' => 'User'])); ?>" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e(App\Models\User::where('role', 'Staff')->count()); ?></h3>
                <p>Nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="<?php echo e(route('admin.users.index', ['role' => 'Staff'])); ?>" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e(App\Models\User::where('role', 'Bus_owner')->count()); ?></h3>
                <p>Nhà xe</p>
            </div>
            <div class="icon">
                <i class="fas fa-bus"></i>
            </div>
            <a href="<?php echo e(route('admin.users.index', ['role' => 'Bus_owner'])); ?>" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e(App\Models\User::count()); ?></h3>
                <p>Tổng người dùng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .small-box {
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        position: relative;
        display: block;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, .125);
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
    }

    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 3rem;
        color: rgba(255, 255, 255, .15);
    }

    .small-box .inner {
        padding: 10px;
    }

    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }

    .small-box p {
        font-size: 1rem;
        margin: 0;
    }

    .small-box-footer {
        background-color: rgba(0, 0, 0, .1);
        color: rgba(255, 255, 255, .8);
        display: block;
        padding: 3px 10px;
        position: relative;
        text-decoration: none;
        transition: all .15s linear;
    }

    .small-box-footer:hover {
        text-decoration: none;
        color: #fff;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/admin/users/index.blade.php ENDPATH**/ ?>