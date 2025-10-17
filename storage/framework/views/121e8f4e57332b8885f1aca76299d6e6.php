<?php $__env->startSection('title', 'Thêm chuyến xe mới'); ?>

<?php $__env->startSection('page-title', 'Thêm chuyến xe mới'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.trips.index')); ?>">Quản lý chuyến xe</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin chuyến xe</h3>
            </div>

            <form action="<?php echo e(route('bus-owner.trips.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
                                    value="<?php echo e(old('ten_xe')); ?>" required>
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
                                <label for="ma_tram_di">Trạm đi <span class="text-danger">*</span></label>
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
                                    <?php $__currentLoopData = \App\Models\TramXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tram->ma_tram_xe); ?>" <?php echo e(old('ma_tram_di') == $tram->ma_tram_xe ? 'selected' : ''); ?>>
                                        <?php echo e($tram->ten_tram); ?> - <?php echo e($tram->tinh_thanh); ?>

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
                                <label for="ma_tram_den">Trạm đến <span class="text-danger">*</span></label>
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
                                    <?php $__currentLoopData = \App\Models\TramXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tram->ma_tram_xe); ?>" <?php echo e(old('ma_tram_den') == $tram->ma_tram_xe ? 'selected' : ''); ?>>
                                        <?php echo e($tram->ten_tram); ?> - <?php echo e($tram->tinh_thanh); ?>

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
                                <label for="tram_trung_gian">Trạm trung gian (tùy chọn)</label>
                                <select class="form-control select2" id="tram_trung_gian" name="tram_trung_gian[]" multiple>
                                    <?php $__currentLoopData = \App\Models\TramXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tram->ma_tram_xe); ?>">
                                        <?php echo e($tram->ten_tram); ?> - <?php echo e($tram->tinh_thanh); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="form-text text-muted">Chọn nhiều trạm trung gian (nếu có). Giữ Ctrl để chọn nhiều.</small>
                                <?php $__errorArgs = ['tram_trung_gian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
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
                                    id="ngay_di" name="ngay_di" value="<?php echo e(old('ngay_di', date('Y-m-d'))); ?>"
                                    min="<?php echo e(date('Y-m-d')); ?>" required>
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
                                    id="gio_di" name="gio_di" value="<?php echo e(old('gio_di')); ?>" required>
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
                                    <option value="Giường nằm" <?php echo e(old('loai_xe') == 'Giường nằm' ? 'selected' : ''); ?>>Giường nằm</option>
                                    <option value="Ghế ngồi" <?php echo e(old('loai_xe') == 'Ghế ngồi' ? 'selected' : ''); ?>>Ghế ngồi</option>
                                    <option value="Limousine" <?php echo e(old('loai_xe') == 'Limousine' ? 'selected' : ''); ?>>Limousine</option>
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
                                    <option value="Một chiều" <?php echo e(old('loai_chuyen') == 'Một chiều' ? 'selected' : ''); ?>>Một chiều</option>
                                    <option value="Khứ hồi" <?php echo e(old('loai_chuyen') == 'Khứ hồi' ? 'selected' : ''); ?>>Khứ hồi</option>
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
                                    value="<?php echo e(old('gia_ve')); ?>" min="0" step="1000" required>
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
                                    value="<?php echo e(old('so_cho')); ?>" min="1" required>
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
                                    value="<?php echo e(old('so_ve', 0)); ?>" min="0">
                                <small class="form-text text-muted">Để trống hoặc nhập 0 nếu chưa bán vé nào</small>
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
                                <label for="tai_xe_id">Tài xế</label>
                                <select class="form-control <?php $__errorArgs = ['tai_xe_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="tai_xe_id" name="tai_xe_id">
                                    <option value="">-- Chọn tài xế --</option>
                                    <?php $__currentLoopData = \App\Models\NhanVien::where('ma_nha_xe', $bus_company->ma_nha_xe)->where('chuc_vu', 'Tài xế')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taixe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($taixe->ma_nv); ?>"
                                        data-name="<?php echo e($taixe->ten_nv); ?>"
                                        data-phone="<?php echo e($taixe->so_dien_thoai); ?>"
                                        <?php echo e(old('tai_xe_id') == $taixe->ma_nv ? 'selected' : ''); ?>>
                                        <?php echo e($taixe->ten_nv); ?> - <?php echo e($taixe->so_dien_thoai); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" name="ten_tai_xe" id="ten_tai_xe" value="<?php echo e(old('ten_tai_xe')); ?>">
                                <input type="hidden" name="sdt_tai_xe" id="sdt_tai_xe" value="<?php echo e(old('sdt_tai_xe')); ?>">
                                <?php $__errorArgs = ['tai_xe_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">Chọn tài xế từ danh sách nhân viên</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gio_den">Giờ đến (dự kiến)</label>
                                <input type="time" class="form-control <?php $__errorArgs = ['gio_den'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="gio_den" name="gio_den" value="<?php echo e(old('gio_den')); ?>">
                                <?php $__errorArgs = ['gio_den'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">Thời gian đến dự kiến</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Lưu chuyến xe
                    </button>
                    <a href="<?php echo e(route('bus-owner.trips.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for trạm trung gian
        $('#tram_trung_gian').select2({
            theme: 'bootstrap4',
            placeholder: 'Chọn trạm trung gian',
            allowClear: true,
            width: '100%'
        });
        
        // Auto fill driver info when selecting from dropdown
        $('#tai_xe_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var driverName = selectedOption.data('name');
            var driverPhone = selectedOption.data('phone');

            $('#ten_tai_xe').val(driverName || '');
            $('#sdt_tai_xe').val(driverPhone || '');
        });

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
        });

        $('#so_cho').on('input', function() {
            var soVe = parseInt($('#so_ve').val()) || 0;
            var soCho = parseInt($(this).val()) || 0;

            if (soVe > soCho) {
                $('#so_ve').val(soCho);
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/trips/create.blade.php ENDPATH**/ ?>