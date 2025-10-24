$(document).ready(function() {
    // Delete button
    $('#deleteBtn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        Swal.fire({
            title: 'Xác nhận xóa?',
            html: `Bạn có chắc chắn muốn xóa nhân viên:<br><strong>` + $('#deleteBtn').data('name') + `</strong>?<br><br><span class="text-danger">Hành động này không thể hoàn tác!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Xóa',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Hủy',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form').submit();
            }
        });
    });
});
