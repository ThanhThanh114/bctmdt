$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle delete button click with SweetAlert
    $('.btn-delete').on('click', function() {
        const nhanVienId = $(this).data('id');
        const nhanVienName = $(this).data('name');

        Swal.fire({
            title: 'Xác nhận xóa?',
            html: `Bạn có chắc chắn muốn xóa nhân viên:<br><strong>${nhanVienName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Xóa',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Hủy',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${nhanVienId}`).submit();
            }
        });
    });

    // Auto-submit form on select change
    $('select[name="chuc_vu"], select[name="per_page"]').on('change', function() {
        $('#searchForm').submit();
    });

    // Handle Enter key in search input
    $('input[name="search"]').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#searchForm').submit();
        }
    });
});
