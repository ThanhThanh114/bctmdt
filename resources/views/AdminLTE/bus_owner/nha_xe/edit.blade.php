@extends('layouts.admin')

@section('title', 'Chỉnh sửa thông tin Nhà xe')
@section('page-title', 'Chỉnh sửa thông tin Nhà xe')
@section('breadcrumb', 'Quản lý Nhà xe')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Cập nhật thông tin nhà xe
                </h3>
            </div>

            <form action="{{ route('bus-owner.nha-xe.update', $busCompany->ma_nha_xe) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5><i class="icon fas fa-ban"></i> Có lỗi xảy ra!</h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <!-- Mã nhà xe (readonly) -->
                    <div class="form-group">
                        <label for="ma_nha_xe">
                            <i class="fas fa-hashtag text-muted mr-1"></i>
                            Mã nhà xe
                        </label>
                        <input type="text"
                            class="form-control bg-light"
                            id="ma_nha_xe"
                            value="{{ $busCompany->ma_nha_xe }}"
                            readonly>
                        <small class="form-text text-muted">Mã nhà xe không thể thay đổi</small>
                    </div>

                    <!-- Tên nhà xe -->
                    <div class="form-group">
                        <label for="ten_nha_xe">
                            <i class="fas fa-building text-primary mr-1"></i>
                            Tên nhà xe <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('ten_nha_xe') is-invalid @enderror"
                            id="ten_nha_xe"
                            name="ten_nha_xe"
                            value="{{ old('ten_nha_xe', $busCompany->ten_nha_xe) }}"
                            required
                            placeholder="Nhập tên nhà xe">
                        @error('ten_nha_xe')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Địa chỉ -->
                    <div class="form-group">
                        <label for="dia_chi">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                            Địa chỉ
                        </label>
                        <textarea class="form-control @error('dia_chi') is-invalid @enderror"
                            id="dia_chi"
                            name="dia_chi"
                            rows="3"
                            placeholder="Nhập địa chỉ đầy đủ của nhà xe">{{ old('dia_chi', $busCompany->dia_chi) }}</textarea>
                        @error('dia_chi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Ví dụ: 272 Đệ Thám, Phường Phạm Ngũ Lão, Quận 1, TP.HCM
                        </small>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="form-group">
                        <label for="so_dien_thoai">
                            <i class="fas fa-phone text-success mr-1"></i>
                            Số điện thoại
                        </label>
                        <input type="tel"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            id="so_dien_thoai"
                            name="so_dien_thoai"
                            value="{{ old('so_dien_thoai', $busCompany->so_dien_thoai) }}"
                            placeholder="Nhập số điện thoại liên hệ"
                            pattern="[0-9]{10,11}">
                        @error('so_dien_thoai')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Nhập 10-11 số, không có khoảng trắng
                        </small>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope text-info mr-1"></i>
                            Email
                        </label>
                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email', $busCompany->email) }}"
                            placeholder="example@domain.com">
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Email chính thức cho liên hệ và thông báo
                        </small>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info-circle"></i> Lưu ý:</h5>
                        <p class="mb-0">
                            • Tên nhà xe là thông tin bắt buộc<br>
                            • Thông tin liên hệ sẽ được hiển thị cho khách hàng<br>
                            • Vui lòng cung cấp thông tin chính xác và đầy đủ
                        </p>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('bus-owner.nha-xe.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Quay lại
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="reset" class="btn btn-warning mr-2">
                                <i class="fas fa-undo mr-2"></i>
                                Đặt lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Card thống kê bên dưới -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-bus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng chuyến xe</span>
                        <span class="info-box-number">{{ $busCompany->chuyenXe()->count() }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Chuyến xe đang hoạt động
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Nhân viên</span>
                        <span class="info-box-number">{{ $busCompany->nhanVien()->count() }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Nhân viên trong hệ thống
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm before reset
        $('button[type="reset"]').on('click', function(e) {
            if (!confirm('Bạn có chắc muốn đặt lại tất cả các trường về giá trị ban đầu?')) {
                e.preventDefault();
            }
        });

        // Confirm before submit
        $('form').on('submit', function(e) {
            var btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...');
        });

        // Format phone number input
        $('#so_dien_thoai').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validate email format
        $('#email').on('blur', function() {
            var email = $(this).val();
            if (email && !isValidEmail(email)) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<span class="invalid-feedback d-block">Email không đúng định dạng</span>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
</script>
@endpush

@push('styles')
<style>
    .card-primary:not(.card-outline)>.card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }

    .callout.callout-info {
        border-left-color: #667eea;
        background-color: #f8f9ff;
    }

    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    label {
        font-weight: 600;
        color: #495057;
    }

    .form-text {
        font-size: 0.875rem;
    }
</style>
@endpush