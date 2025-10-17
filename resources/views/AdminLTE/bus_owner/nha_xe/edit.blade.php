@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhà xe')
@section('page-title', 'Chỉnh sửa nhà xe')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.nha-xe.index') }}">Quản lý Nhà xe</a></li>
<li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-warning text-white">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Chỉnh sửa thông tin nhà xe
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bus-owner.nha-xe.show', $nhaXe->ma_nha_xe) }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <form action="{{ route('bus-owner.nha-xe.update', $nhaXe->ma_nha_xe) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            <!-- Mã nhà xe (readonly) -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ma_nha_xe">
                                        <i class="fas fa-hashtag text-muted mr-1"></i>
                                        Mã nhà xe
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg"
                                        id="ma_nha_xe"
                                        value="{{ $nhaXe->ma_nha_xe }}"
                                        readonly
                                        disabled>
                                    <small class="form-text text-muted">Không thể thay đổi</small>
                                </div>
                            </div>

                            <!-- Tên nhà xe -->
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="ten_nha_xe" class="required">
                                        <i class="fas fa-building text-primary mr-1"></i>
                                        Tên nhà xe
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('ten_nha_xe') is-invalid @enderror"
                                        id="ten_nha_xe"
                                        name="ten_nha_xe"
                                        value="{{ old('ten_nha_xe', $nhaXe->ten_nha_xe) }}"
                                        placeholder="Nhập tên nhà xe"
                                        required
                                        maxlength="100">
                                    @error('ten_nha_xe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        value="{{ old('dia_chi', $nhaXe->dia_chi) }}"
                                        placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                        required
                                        maxlength="255">
                                    @error('dia_chi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        value="{{ old('so_dien_thoai', $nhaXe->so_dien_thoai) }}"
                                        placeholder="0xxxxxxxxx"
                                        required
                                        maxlength="15"
                                        pattern="[0-9]{10,11}">
                                    @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">
                                        <i class="fas fa-envelope text-info mr-1"></i>
                                        Email
                                    </label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $nhaXe->email) }}"
                                        placeholder="email@example.com"
                                        maxlength="100">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Change Tracking Info -->
                        <div class="alert alert-light border mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <i class="fas fa-info-circle text-info mr-2"></i>
                                    <strong>Ghi chú:</strong> Các trường có dấu <span class="text-danger">*</span> là bắt buộc
                                </div>
                                <div class="col-md-6 text-right text-muted">
                                    <small>
                                        <i class="fas fa-clock mr-1"></i>
                                        Cập nhật lần cuối: {{ now()->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Cập nhật
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-undo mr-2"></i>
                                    Hoàn tác
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('bus-owner.nha-xe.show', $nhaXe->ma_nha_xe) }}" class="btn btn-outline-secondary btn-lg">
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
        $('#editForm').on('submit', function(e) {
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

        // Confirm before leaving if form is dirty
        var formChanged = false;
        $('#editForm :input').on('change', function() {
            formChanged = true;
        });

        $(window).on('beforeunload', function() {
            if (formChanged) {
                return 'Bạn có thay đổi chưa được lưu. Bạn có chắc chắn muốn rời khỏi trang?';
            }
        });

        $('#editForm').on('submit', function() {
            formChanged = false;
        });
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