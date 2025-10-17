

<?php $__env->startSection('title', 'Chỉnh sửa chuyến xe'); ?>

<?php $__env->startSection('page-title', 'Chỉnh sửa chuyến xe'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.trips.index')); ?>">Quản lý chuyến xe</a></li>
<li class="breadcrumb-item active">Chỉnh sửa</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Chỉnh sửa thông tin chuyến xe</h3>
            </div>

            <form action="<?php echo e(route('bus-owner.trips.update', $trip)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ten_xe">Tên chuyến xe <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['ten_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="ten_xe" name="ten_xe" placeholder="VD: Hà Nội - Hải Phòng"
                                    value="<?php echo e(old('ten_xe', $trip->ten_xe)); ?>" required>
                                <?php $__errorArgs = ['ten_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="ma_tram_di">Trạm đi</label>
                                <select class="form-control <?php $__errorArgs = ['ma_tram_di'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="ma_tram_di" name="ma_tram_di">
                                    <option value="">-- Chọn trạm đi --</option>
                                    <?php $__currentLoopData = \App\Models\TramXe::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tram->ma_tram_xe); ?>"
                                        <?php echo e(old('ma_tram_di', $trip->ma_tram_di) == $tram->ma_tram_xe ? 'selected' : ''); ?>>
                                        <?php echo e($tram->ten_tram); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['ma_tram_di'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="ma_tram_den">Trạm đến</label>
                                <select class="form-control <?php $__errorArgs = ['ma_tram_den'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="ma_tram_den" name="ma_tram_den">
                                    <option value="">-- Chọn trạm đến --</option>
                                    <?php $__currentLoopData = \App\Models\TramXe::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tram->ma_tram_xe); ?>"
                                        <?php echo e(old('ma_tram_den', $trip->ma_tram_den) == $tram->ma_tram_xe ? 'selected' : ''); ?>>
                                        <?php echo e($tram->ten_tram); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['ma_tram_den'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="ngay_di">Ngày đi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['ngay_di'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="ngay_di" name="ngay_di"
                                    value="<?php echo e(old('ngay_di', $trip->ngay_di ? \Carbon\Carbon::parse($trip->ngay_di)->format('Y-m-d') : '')); ?>"
                                    required>
                                <?php $__errorArgs = ['ngay_di'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="gio_di">Giờ đi <span class="text-danger">*</span></label>
                                <input type="time" class="form-control <?php $__errorArgs = ['gio_di'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="gio_di" name="gio_di"
                                    value="<?php echo e(old('gio_di', $trip->gio_di ? \Carbon\Carbon::parse($trip->gio_di)->format('H:i') : '')); ?>"
                                    required>
                                <?php $__errorArgs = ['gio_di'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loai_xe">Loại xe</label>
                                <select class="form-control <?php $__errorArgs = ['loai_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="loai_xe" name="loai_xe">
                                    <option value="">-- Chọn loại xe --</option>
                                    <option value="Giường nằm" <?php echo e(old('loai_xe', $trip->loai_xe) == 'Giường nằm' ? 'selected' : ''); ?>>Giường nằm</option>
                                    <option value="Ghế ngồi" <?php echo e(old('loai_xe', $trip->loai_xe) == 'Ghế ngồi' ? 'selected' : ''); ?>>Ghế ngồi</option>
                                    <option value="Limousine" <?php echo e(old('loai_xe', $trip->loai_xe) == 'Limousine' ? 'selected' : ''); ?>>Limousine</option>
                                </select>
                                <?php $__errorArgs = ['loai_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="loai_chuyen">Loại chuyến <span class="text-danger">*</span></label>
                                <select class="form-control <?php $__errorArgs = ['loai_chuyen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="loai_chuyen" name="loai_chuyen" required>
                                    <option value="Một chiều" <?php echo e(old('loai_chuyen', $trip->loai_chuyen) == 'Một chiều' ? 'selected' : ''); ?>>Một chiều</option>
                                    <option value="Khứ hồi" <?php echo e(old('loai_chuyen', $trip->loai_chuyen) == 'Khứ hồi' ? 'selected' : ''); ?>>Khứ hồi</option>
                                </select>
                                <?php $__errorArgs = ['loai_chuyen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="gia_ve">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php $__errorArgs = ['gia_ve'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="gia_ve" name="gia_ve" placeholder="VD: 150000"
                                    value="<?php echo e(old('gia_ve', $trip->gia_ve)); ?>" min="0" step="1000" required>
                                <?php $__errorArgs = ['gia_ve'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="so_cho">Tổng số chỗ ngồi <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php $__errorArgs = ['so_cho'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="so_cho" name="so_cho" placeholder="VD: 40"
                                    value="<?php echo e(old('so_cho', $trip->so_cho)); ?>" min="1" required>
                                <?php $__errorArgs = ['so_cho'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="so_ve">Số vé đã bán</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['so_ve'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="so_ve" name="so_ve" placeholder="VD: 0"
                                    value="<?php echo e(old('so_ve', $trip->so_ve)); ?>" min="0">
                                <small class="form-text text-muted">Ghế trống: <?php echo e($trip->so_cho - $trip->so_ve); ?></small>
                                <?php $__errorArgs = ['so_ve'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ten_tai_xe">Tên tài xế</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['ten_tai_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="ten_tai_xe" name="ten_tai_xe" placeholder="VD: Nguyễn Văn A"
                                    value="<?php echo e(old('ten_tai_xe', $trip->ten_tai_xe)); ?>">
                                <?php $__errorArgs = ['ten_tai_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sdt_tai_xe">Số điện thoại tài xế</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['sdt_tai_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="sdt_tai_xe" name="sdt_tai_xe" placeholder="VD: 0912345678"
                                    value="<?php echo e(old('sdt_tai_xe', $trip->sdt_tai_xe)); ?>">
                                <?php $__errorArgs = ['sdt_tai_xe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i>Cập nhật chuyến xe
                    </button>
                    <a href="<?php echo e(route('bus-owner.trips.show', $trip)); ?>" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Hủy bỏ
                    </a>
                    <a href="<?php echo e(route('bus-owner.trips.index')); ?>" class="btn btn-info">
                        <i class="fas fa-list mr-2"></i>Danh sách chuyến xe
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Validate form before submit
        $('#so_ve').on('input', function() {
            var soVe = parseInt($(this).val()) || 0;
            var soCho = parseInt($('#so_cho').val()) || 0;

            if (soVe > soCho) {
                $(this).addClass('is-invalid');
                alert('Số vé đã bán không được vượt quá tổng số chỗ ngồi!');
                $(this).val(soCho);
            } else {
                $(this).removeClass('is-invalid');
            }

            // Update available seats display
            $('.form-text').text('Ghế trống: ' + (soCho - soVe));
        });

        $('#so_cho').on('input', function() {
            var soVe = parseInt($('#so_ve').val()) || 0;
            var soCho = parseInt($(this).val()) || 0;

            if (soVe > soCho) {
                $('#so_ve').val(soCho);
            }

            // Update available seats display
            $('.form-text').text('Ghế trống: ' + (soCho - soVe));
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/trips/edit.blade.php ENDPATH**/ ?>