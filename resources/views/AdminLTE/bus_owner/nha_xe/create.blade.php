@extends('layouts.admin')

@section('title', 'Thêm nhà xe mới')
@section('page-title', 'Thêm nhà xe mới')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.nha-xe.index') }}">Quản lý Nhà xe</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Thêm nhà xe mới
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bus-owner.nha-xe.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <form action="{{ route('bus-owner.nha-xe.store') }}" method="POST" id="createForm">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <!-- Tên nhà xe -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ten_nha_xe" class="required">
                                        <i class="fas fa-building text-primary mr-1"></i>
                                        Tên nhà xe
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('ten_nha_xe') is-invalid @enderror"
                                        id="ten_nha_xe"
                                        name="ten_nha_xe"
                                        value="{{ old('ten_nha_xe') }}"
                                        placeholder="Nhập tên nhà xe"
                                        required
                                        maxlength="100">
                                    @error('ten_nha_xe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tối đa 100 ký tự</small>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        <i class="fas fa-envelope text-info mr-1"></i>
                                        Email
                                    </label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="email@example.com"
                                        maxlength="100">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Email liên hệ của nhà xe</small>
                                </div>
                            </div>

                            <!-- Địa chỉ -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="dia_chi" class="required">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                        Địa chỉ
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('dia_chi') is-invalid @enderror"
                                        id="dia_chi"
                                        name="dia_chi"
                                        value="{{ old('dia_chi') }}"
                                        placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                        required
                                        maxlength="255">
                                    @error('dia_chi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Địa chỉ trụ sở chính</small>
                                </div>
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="so_dien_thoai" class="required">
                                        <i class="fas fa-phone text-success mr-1"></i>
                                        Số điện thoại
                                    </label>
                                    <input type="tel"
                                        class="form-control form-control-lg @error('so_dien_thoai') is-invalid @enderror"
                                        id="so_dien_thoai"
                                        name="so_dien_thoai"
                                        value="{{ old('so_dien_thoai') }}"
                                        placeholder="0xxxxxxxxx"
                                        required
                                        maxlength="15"
                                        pattern="[0-9]{10,11}">
                                    @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">10-11 chữ số</small>
                                </div>
                            </div>
                        </div>

                        <!-- Alert -->
                        <div class="alert alert-info mt-3">
                            <i class="icon fas fa-info-circle"></i>
                            <strong>Lưu ý:</strong> Các trường có dấu <span class="text-danger">*</span> là bắt buộc phải nhập.
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Lưu thông tin
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-redo mr-2"></i>
                                    Làm mới
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('bus-owner.nha-xe.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times mr-2"></i>
                                    Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        $('#createForm').on('submit', function(e) {
            var isValid = true;

            // Validate phone number
            var phone = $('#so_dien_thoai').val();
            if (phone && !/^[0-9]{10,11}$/.test(phone)) {
                $('#so_dien_thoai').addClass('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Vui lòng kiểm tra lại thông tin nhập vào.',
                });
            }
        });

        // Show validation errors if any
        @if($errors - > any())
        Swal.fire({
            icon: 'error',
            title: 'Có lỗi xảy ra!',
            html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        });
        @endif
    });
</script>
@endpush

@push('styles')
<style>
    .required:after {
        content: " *";
        color: red;
    }

    .form-control-lg {
        border-radius: 8px;
    }

    .card {
        border-radius: 15px;
        border: none;
    }

    .card-header {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .invalid-feedback {
        display: block;
        font-weight: 500;
    }
</style>
@endpush
@endsection