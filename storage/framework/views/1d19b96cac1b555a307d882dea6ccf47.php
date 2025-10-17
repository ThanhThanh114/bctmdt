<?php $__env->startSection('title', 'Quản lý Trạm xe'); ?>
<?php $__env->startSection('page-title', 'Danh sách Trạm xe'); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('bus-owner.dashboard')); ?>">Dashboard</a></li>
<li class="breadcrumb-item active">Trạm xe</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-check"></i> <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-ban"></i> <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-exclamation-triangle"></i> <?php echo e(session('warning')); ?>

        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>Danh sách trạm xe
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('bus-owner.tram-xe.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Thêm trạm xe mới
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="<?php echo e(route('bus-owner.tram-xe.index')); ?>" method="GET">
                            <div class="input-group">
                                <input type="text"
                                    name="search"
                                    id="searchInput"
                                    class="form-control"
                                    placeholder="Tìm kiếm theo tên, địa chỉ, tỉnh/thành..."
                                    value="<?php echo e(request('search')); ?>">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <?php if(request('search')): ?>
                        <a href="<?php echo e(route('bus-owner.tram-xe.index')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times mr-1"></i> Xóa tìm kiếm
                        </a>
                        <span class="text-muted ml-2">
                            Tìm kiếm: <strong>"<?php echo e(request('search')); ?>"</strong>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tramXeTable">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px" class="text-center">Mã trạm</th>
                                <th>Tên trạm</th>
                                <th style="width: 150px">Tỉnh/Thành phố</th>
                                <th>Địa chỉ</th>
                                <th style="width: 180px">Nhà xe</th>
                                <th style="width: 120px" class="text-center">Liên hệ</th>
                                <th style="width: 150px" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $tramXe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tram): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-id="<?php echo e($tram->ma_tram_xe); ?>">
                                <td class="text-center">
                                    <span class="badge badge-primary"><?php echo e($tram->ma_tram_xe); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo e($tram->ten_tram); ?></strong>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                    <?php echo e($tram->tinh_thanh ?? 'N/A'); ?>

                                </td>
                                <td><?php echo e($tram->dia_chi ?? $tram->dia_chi_tram ?? 'Chưa cập nhật'); ?></td>
                                <td>
                                    <?php if($tram->nhaXe): ?>
                                    <span class="badge badge-success" title="<?php echo e($tram->nhaXe->ten_nha_xe); ?>">
                                        <i class="fas fa-building mr-1"></i><?php echo e($tram->nhaXe->ten_nha_xe); ?>

                                    </span>
                                    <?php else: ?>
                                    <span class="badge badge-secondary">Chưa gán</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($tram->nhaXe && $tram->nhaXe->so_dien_thoai): ?>
                                    <a href="tel:<?php echo e($tram->nhaXe->so_dien_thoai); ?>" class="text-success" title="<?php echo e($tram->nhaXe->so_dien_thoai); ?>">
                                        <i class="fas fa-phone"></i> <?php echo e($tram->nhaXe->so_dien_thoai); ?>

                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('bus-owner.tram-xe.show', $tram->ma_tram_xe)); ?>"
                                            class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('bus-owner.tram-xe.edit', $tram->ma_tram_xe)); ?>"
                                            class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-danger btn-delete"
                                            data-id="<?php echo e($tram->ma_tram_xe); ?>"
                                            data-name="<?php echo e($tram->ten_tram); ?>"
                                            title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr id="noDataRow">
                                <td colspan="7" class="text-center text-muted py-5">
                                    <?php if(request('search')): ?>
                                    <i class="fas fa-search fa-3x mb-3 d-block text-secondary"></i>
                                    <h5>Không tìm thấy kết quả</h5>
                                    <p>Không có trạm xe nào khớp với từ khóa: <strong>"<?php echo e(request('search')); ?>"</strong></p>
                                    <a href="<?php echo e(route('bus-owner.tram-xe.index')); ?>" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-times mr-1"></i> Xóa bộ lọc
                                    </a>
                                    <?php else: ?>
                                    <i class="fas fa-info-circle fa-3x mb-3 d-block text-secondary"></i>
                                    <h5>Chưa có trạm xe nào</h5>
                                    <p class="text-muted">Bấm nút "Thêm trạm xe mới" để bắt đầu</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if($tramXe->hasPages()): ?>
            <div class="card-footer clearfix">
                <div class="float-left">
                    <p class="text-muted mb-0">
                        <?php if($tramXe->total() > 0): ?>
                        Hiển thị <?php echo e($tramXe->firstItem()); ?> - <?php echo e($tramXe->lastItem()); ?>

                        trong tổng số <?php echo e($tramXe->total()); ?> trạm xe
                        <?php if(request('search')): ?>
                        <span class="badge badge-info ml-2">
                            <i class="fas fa-filter"></i> Đang lọc
                        </span>
                        <?php endif; ?>
                        <?php else: ?>
                        Không có dữ liệu
                        <?php endif; ?>
                    </p>
                </div>
                <div class="float-right">
                    <?php echo e($tramXe->appends(request()->query())->links()); ?>

                </div>
            </div>
            <?php elseif($tramXe->total() > 0): ?>
            <div class="card-footer">
                <p class="text-muted mb-0">
                    Tổng số: <?php echo e($tramXe->total()); ?> trạm xe
                    <?php if(request('search')): ?>
                    <span class="badge badge-info ml-2">
                        <i class="fas fa-filter"></i> Đang lọc: "<?php echo e(request('search')); ?>"
                    </span>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa trạm xe này?</p>
                <p class="text-danger font-weight-bold" id="deleteTramName"></p>
                <p class="text-muted small">
                    <i class="fas fa-info-circle mr-1"></i>
                    Lưu ý: Không thể xóa trạm xe đang được sử dụng trong chuyến xe.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash mr-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        let deleteId = null;

        // Real-time search with debounce AND form submit on Enter
        let searchTimeout;
        $('#searchInput').on('keyup', function(e) {
            // Nếu nhấn Enter, submit form tìm kiếm server-side
            if (e.keyCode === 13) {
                $(this).closest('form').submit();
                return;
            }

            clearTimeout(searchTimeout);
            const value = $(this).val().toLowerCase().trim();

            searchTimeout = setTimeout(function() {
                let visibleCount = 0;

                $('#tramXeTable tbody tr').each(function() {
                    if ($(this).attr('id') === 'noDataRow' || $(this).attr('id') === 'noResultsRow') return;

                    const text = $(this).text().toLowerCase();
                    const isVisible = value === '' || text.indexOf(value) > -1;
                    $(this).toggle(isVisible);

                    if (isVisible) visibleCount++;
                });

                // Show "no results" message if needed
                if (visibleCount === 0 && value !== '') {
                    if ($('#noResultsRow').length === 0) {
                        $('#tramXeTable tbody').append(
                            '<tr id="noResultsRow"><td colspan="7" class="text-center text-muted py-4">' +
                            '<i class="fas fa-search mr-2"></i>Không tìm thấy kết quả trên trang này. ' +
                            '<button type="button" class="btn btn-sm btn-primary ml-2" onclick="$(\'#searchInput\').closest(\'form\').submit()">Tìm trên toàn bộ</button></td></tr>'
                        );
                    }
                } else {
                    $('#noResultsRow').remove();
                }
            }, 300); // Debounce 300ms
        });

        // Delete button click
        $('.btn-delete').click(function() {
            deleteId = $(this).data('id');
            const tramName = $(this).data('name');
            $('#deleteTramName').text(tramName);
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirmDelete').click(function() {
            if (!deleteId) return;

            const btn = $(this);
            const originalHtml = btn.html();

            // Disable button and show loading
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang xóa...');

            $.ajax({
                url: `/bus-owner/tram-xe/${deleteId}`,
                type: 'DELETE',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Remove row from table
                        $(`tr[data-id="${deleteId}"]`).fadeOut(300, function() {
                            $(this).remove();

                            // Check if table is empty
                            if ($('#tramXeTable tbody tr:visible').length === 0) {
                                $('#tramXeTable tbody').html(
                                    '<tr id="noDataRow"><td colspan="7" class="text-center text-muted py-4">' +
                                    '<i class="fas fa-info-circle mr-2"></i>Chưa có trạm xe nào</td></tr>'
                                );
                            }
                        });

                        $('#deleteModal').modal('hide');

                        // Show success message
                        const successAlert = `
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon fas fa-check"></i> ${response.message}
                        </div>
                    `;
                        $('.col-12').prepend(successAlert);

                        // Auto dismiss after 3 seconds
                        setTimeout(function() {
                            $('.alert-success').fadeOut();
                        }, 3000);
                    } else {
                        showError(response.message);
                    }
                },
                error: function(xhr) {
                    let message = 'Có lỗi xảy ra khi xóa trạm xe!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showError(message);
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        });

        function showError(message) {
            $('#deleteModal').modal('hide');
            const errorAlert = `
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-ban"></i> ${message}
            </div>
        `;
            $('.col-12').prepend(errorAlert);

            // Auto dismiss after 5 seconds
            setTimeout(function() {
                $('.alert-danger').fadeOut();
            }, 5000);
        }

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\bctmdt\resources\views/AdminLTE/bus_owner/tram_xe/index.blade.php ENDPATH**/ ?>