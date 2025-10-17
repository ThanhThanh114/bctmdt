// Bus Owner Custom JavaScript Functions

$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert:not(.alert-permanent)').fadeOut('slow');
    }, 5000);
    
    // Confirm delete actions
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Bạn có chắc chắn muốn xóa? Hành động này không thể hoàn tác!')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Real-time search for tables
    $('#tableSearch').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#dataTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    // Advanced filter toggle
    $('#advancedFilterToggle').on('click', function() {
        $('#advancedFilters').slideToggle();
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Export to Excel
    $('#exportExcel').on('click', function() {
        var table = $('#dataTable');
        var html = table.clone();
        var blob = new Blob([html.html()], {
            type: 'application/vnd.ms-excel'
        });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'export_' + Date.now() + '.xls';
        a.click();
    });
    
    // Print table
    $('#printTable').on('click', function() {
        window.print();
    });
    
    // Select all checkboxes
    $('#selectAll').on('change', function() {
        $('.item-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkActionsButton();
    });
    
    // Update bulk actions button
    $('.item-checkbox').on('change', function() {
        updateBulkActionsButton();
    });
    
    function updateBulkActionsButton() {
        var checkedCount = $('.item-checkbox:checked').length;
        if (checkedCount > 0) {
            $('#bulkActions').removeClass('d-none').find('.count').text(checkedCount);
        } else {
            $('#bulkActions').addClass('d-none');
        }
    }
    
    // Bulk delete
    $('#bulkDelete').on('click', function() {
        var ids = [];
        $('.item-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        
        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một mục');
            return;
        }
        
        if (confirm('Bạn có chắc chắn muốn xóa ' + ids.length + ' mục đã chọn?')) {
            // Send AJAX request to delete
            $.ajax({
                url: '/bus-owner/bulk-delete',
                method: 'POST',
                data: {
                    ids: ids,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            });
        }
    });
    
    // Date range picker
    if ($.fn.daterangepicker) {
        $('#dateRange').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Áp dụng',
                cancelLabel: 'Hủy',
                fromLabel: 'Từ',
                toLabel: 'Đến',
                customRangeLabel: 'Tùy chỉnh',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            },
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
    }
    
    // Number formatting
    $('.format-number').each(function() {
        var value = $(this).text();
        if (!isNaN(value) && value !== '') {
            $(this).text(parseInt(value).toLocaleString('vi-VN'));
        }
    });
    
    // Currency formatting
    $('.format-currency').each(function() {
        var value = $(this).text();
        if (!isNaN(value) && value !== '') {
            $(this).text(parseInt(value).toLocaleString('vi-VN') + 'đ');
        }
    });
    
    // Auto-save form data
    if ($('form[data-autosave]').length > 0) {
        $('form[data-autosave] input, form[data-autosave] textarea, form[data-autosave] select').on('change', function() {
            var formData = $('form[data-autosave]').serialize();
            localStorage.setItem('formData', formData);
            showNotification('Đã lưu tự động', 'success');
        });
        
        // Restore form data
        var savedData = localStorage.getItem('formData');
        if (savedData) {
            // Parse and fill form
            var params = new URLSearchParams(savedData);
            params.forEach(function(value, key) {
                $('form[data-autosave] [name="' + key + '"]').val(value);
            });
        }
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        var alertClass = 'alert-' + type;
        var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show notification-popup" role="alert">' +
            '<i class="fas fa-check-circle mr-2"></i>' + message +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '</div>');
        
        $('body').append(notification);
        notification.css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            zIndex: 9999,
            minWidth: '300px'
        });
        
        setTimeout(function() {
            notification.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Ajax form submission
    $('form[data-ajax]').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var data = form.serialize();
        
        // Show loading
        var submitBtn = form.find('[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
        
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
                showNotification(response.message || 'Thao tác thành công!', 'success');
                if (response.redirect) {
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var errorMsg = Object.values(errors).flat().join('<br>');
                    showNotification(errorMsg, 'danger');
                } else {
                    showNotification('Có lỗi xảy ra. Vui lòng thử lại!', 'danger');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Lưu');
            }
        });
    });
    
    // Quick stats animation
    $('.counter').each(function() {
        var $this = $(this);
        var countTo = $this.attr('data-count');
        
        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 2000,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum).toLocaleString('vi-VN'));
            },
            complete: function() {
                $this.text(parseInt(countTo).toLocaleString('vi-VN'));
            }
        });
    });
    
    // Chart responsiveness
    if (window.Chart) {
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.maintainAspectRatio = false;
    }
});

// Loading overlay functions
function showLoading() {
    var overlay = $('<div class="spinner-overlay"><div class="spinner"></div></div>');
    $('body').append(overlay);
}

function hideLoading() {
    $('.spinner-overlay').remove();
}

// Export functions
window.busOwner = {
    showLoading: showLoading,
    hideLoading: hideLoading
};
