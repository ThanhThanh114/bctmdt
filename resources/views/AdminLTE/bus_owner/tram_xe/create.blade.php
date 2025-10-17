@extends('layouts.admin')

@section('title', 'Thêm Trạm xe mới')
@section('page-title', 'Thêm Trạm xe mới')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bus-owner.tram-xe.index') }}">Trạm xe</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle mr-2"></i>Thêm trạm xe mới
                </h3>
            </div>

            <form action="{{ route('bus-owner.tram-xe.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="icon fas fa-ban"></i> {{ session('error') }}
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="ten_tram">Tên trạm xe <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('ten_tram') is-invalid @enderror"
                            id="ten_tram"
                            name="ten_tram"
                            value="{{ old('ten_tram') }}"
                            placeholder="VD: Bến xe Miền Đông"
                            required>
                        @error('ten_tram')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tinh_thanh">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                        <select class="form-control select2 @error('tinh_thanh') is-invalid @enderror"
                            id="tinh_thanh"
                            name="tinh_thanh"
                            required>
                            <option value="">-- Chọn tỉnh/thành phố --</option>
                            <option value="Hà Nội" {{ old('tinh_thanh') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                            <option value="Hồ Chí Minh" {{ old('tinh_thanh') == 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                            <option value="Đà Nẵng" {{ old('tinh_thanh') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                            <option value="Hải Phòng" {{ old('tinh_thanh') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                            <option value="Cần Thơ" {{ old('tinh_thanh') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                            <option value="An Giang" {{ old('tinh_thanh') == 'An Giang' ? 'selected' : '' }}>An Giang</option>
                            <option value="Bà Rịa - Vũng Tàu" {{ old('tinh_thanh') == 'Bà Rịa - Vũng Tàu' ? 'selected' : '' }}>Bà Rịa - Vũng Tàu</option>
                            <option value="Bắc Giang" {{ old('tinh_thanh') == 'Bắc Giang' ? 'selected' : '' }}>Bắc Giang</option>
                            <option value="Bắc Kạn" {{ old('tinh_thanh') == 'Bắc Kạn' ? 'selected' : '' }}>Bắc Kạn</option>
                            <option value="Bạc Liêu" {{ old('tinh_thanh') == 'Bạc Liêu' ? 'selected' : '' }}>Bạc Liêu</option>
                            <option value="Bắc Ninh" {{ old('tinh_thanh') == 'Bắc Ninh' ? 'selected' : '' }}>Bắc Ninh</option>
                            <option value="Bến Tre" {{ old('tinh_thanh') == 'Bến Tre' ? 'selected' : '' }}>Bến Tre</option>
                            <option value="Bình Định" {{ old('tinh_thanh') == 'Bình Định' ? 'selected' : '' }}>Bình Định</option>
                            <option value="Bình Dương" {{ old('tinh_thanh') == 'Bình Dương' ? 'selected' : '' }}>Bình Dương</option>
                            <option value="Bình Phước" {{ old('tinh_thanh') == 'Bình Phước' ? 'selected' : '' }}>Bình Phước</option>
                            <option value="Bình Thuận" {{ old('tinh_thanh') == 'Bình Thuận' ? 'selected' : '' }}>Bình Thuận</option>
                            <option value="Cà Mau" {{ old('tinh_thanh') == 'Cà Mau' ? 'selected' : '' }}>Cà Mau</option>
                            <option value="Cao Bằng" {{ old('tinh_thanh') == 'Cao Bằng' ? 'selected' : '' }}>Cao Bằng</option>
                            <option value="Đắk Lắk" {{ old('tinh_thanh') == 'Đắk Lắk' ? 'selected' : '' }}>Đắk Lắk</option>
                            <option value="Đắk Nông" {{ old('tinh_thanh') == 'Đắk Nông' ? 'selected' : '' }}>Đắk Nông</option>
                            <option value="Điện Biên" {{ old('tinh_thanh') == 'Điện Biên' ? 'selected' : '' }}>Điện Biên</option>
                            <option value="Đồng Nai" {{ old('tinh_thanh') == 'Đồng Nai' ? 'selected' : '' }}>Đồng Nai</option>
                            <option value="Đồng Tháp" {{ old('tinh_thanh') == 'Đồng Tháp' ? 'selected' : '' }}>Đồng Tháp</option>
                            <option value="Gia Lai" {{ old('tinh_thanh') == 'Gia Lai' ? 'selected' : '' }}>Gia Lai</option>
                            <option value="Hà Giang" {{ old('tinh_thanh') == 'Hà Giang' ? 'selected' : '' }}>Hà Giang</option>
                            <option value="Hà Nam" {{ old('tinh_thanh') == 'Hà Nam' ? 'selected' : '' }}>Hà Nam</option>
                            <option value="Hà Tĩnh" {{ old('tinh_thanh') == 'Hà Tĩnh' ? 'selected' : '' }}>Hà Tĩnh</option>
                            <option value="Hải Dương" {{ old('tinh_thanh') == 'Hải Dương' ? 'selected' : '' }}>Hải Dương</option>
                            <option value="Hậu Giang" {{ old('tinh_thanh') == 'Hậu Giang' ? 'selected' : '' }}>Hậu Giang</option>
                            <option value="Hòa Bình" {{ old('tinh_thanh') == 'Hòa Bình' ? 'selected' : '' }}>Hòa Bình</option>
                            <option value="Hưng Yên" {{ old('tinh_thanh') == 'Hưng Yên' ? 'selected' : '' }}>Hưng Yên</option>
                            <option value="Khánh Hòa" {{ old('tinh_thanh') == 'Khánh Hòa' ? 'selected' : '' }}>Khánh Hòa</option>
                            <option value="Kiên Giang" {{ old('tinh_thanh') == 'Kiên Giang' ? 'selected' : '' }}>Kiên Giang</option>
                            <option value="Kon Tum" {{ old('tinh_thanh') == 'Kon Tum' ? 'selected' : '' }}>Kon Tum</option>
                            <option value="Lai Châu" {{ old('tinh_thanh') == 'Lai Châu' ? 'selected' : '' }}>Lai Châu</option>
                            <option value="Lâm Đồng" {{ old('tinh_thanh') == 'Lâm Đồng' ? 'selected' : '' }}>Lâm Đồng</option>
                            <option value="Lạng Sơn" {{ old('tinh_thanh') == 'Lạng Sơn' ? 'selected' : '' }}>Lạng Sơn</option>
                            <option value="Lào Cai" {{ old('tinh_thanh') == 'Lào Cai' ? 'selected' : '' }}>Lào Cai</option>
                            <option value="Long An" {{ old('tinh_thanh') == 'Long An' ? 'selected' : '' }}>Long An</option>
                            <option value="Nam Định" {{ old('tinh_thanh') == 'Nam Định' ? 'selected' : '' }}>Nam Định</option>
                            <option value="Nghệ An" {{ old('tinh_thanh') == 'Nghệ An' ? 'selected' : '' }}>Nghệ An</option>
                            <option value="Ninh Bình" {{ old('tinh_thanh') == 'Ninh Bình' ? 'selected' : '' }}>Ninh Bình</option>
                            <option value="Ninh Thuận" {{ old('tinh_thanh') == 'Ninh Thuận' ? 'selected' : '' }}>Ninh Thuận</option>
                            <option value="Phú Thọ" {{ old('tinh_thanh') == 'Phú Thọ' ? 'selected' : '' }}>Phú Thọ</option>
                            <option value="Phú Yên" {{ old('tinh_thanh') == 'Phú Yên' ? 'selected' : '' }}>Phú Yên</option>
                            <option value="Quảng Bình" {{ old('tinh_thanh') == 'Quảng Bình' ? 'selected' : '' }}>Quảng Bình</option>
                            <option value="Quảng Nam" {{ old('tinh_thanh') == 'Quảng Nam' ? 'selected' : '' }}>Quảng Nam</option>
                            <option value="Quảng Ngãi" {{ old('tinh_thanh') == 'Quảng Ngãi' ? 'selected' : '' }}>Quảng Ngãi</option>
                            <option value="Quảng Ninh" {{ old('tinh_thanh') == 'Quảng Ninh' ? 'selected' : '' }}>Quảng Ninh</option>
                            <option value="Quảng Trị" {{ old('tinh_thanh') == 'Quảng Trị' ? 'selected' : '' }}>Quảng Trị</option>
                            <option value="Sóc Trăng" {{ old('tinh_thanh') == 'Sóc Trăng' ? 'selected' : '' }}>Sóc Trăng</option>
                            <option value="Sơn La" {{ old('tinh_thanh') == 'Sơn La' ? 'selected' : '' }}>Sơn La</option>
                            <option value="Tây Ninh" {{ old('tinh_thanh') == 'Tây Ninh' ? 'selected' : '' }}>Tây Ninh</option>
                            <option value="Thái Bình" {{ old('tinh_thanh') == 'Thái Bình' ? 'selected' : '' }}>Thái Bình</option>
                            <option value="Thái Nguyên" {{ old('tinh_thanh') == 'Thái Nguyên' ? 'selected' : '' }}>Thái Nguyên</option>
                            <option value="Thanh Hóa" {{ old('tinh_thanh') == 'Thanh Hóa' ? 'selected' : '' }}>Thanh Hóa</option>
                            <option value="Thừa Thiên Huế" {{ old('tinh_thanh') == 'Thừa Thiên Huế' ? 'selected' : '' }}>Thừa Thiên Huế</option>
                            <option value="Tiền Giang" {{ old('tinh_thanh') == 'Tiền Giang' ? 'selected' : '' }}>Tiền Giang</option>
                            <option value="Trà Vinh" {{ old('tinh_thanh') == 'Trà Vinh' ? 'selected' : '' }}>Trà Vinh</option>
                            <option value="Tuyên Quang" {{ old('tinh_thanh') == 'Tuyên Quang' ? 'selected' : '' }}>Tuyên Quang</option>
                            <option value="Vĩnh Long" {{ old('tinh_thanh') == 'Vĩnh Long' ? 'selected' : '' }}>Vĩnh Long</option>
                            <option value="Vĩnh Phúc" {{ old('tinh_thanh') == 'Vĩnh Phúc' ? 'selected' : '' }}>Vĩnh Phúc</option>
                            <option value="Yên Bái" {{ old('tinh_thanh') == 'Yên Bái' ? 'selected' : '' }}>Yên Bái</option>
                        </select>
                        @error('tinh_thanh')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="dia_chi">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('dia_chi') is-invalid @enderror"
                            id="dia_chi"
                            name="dia_chi"
                            rows="3"
                            placeholder="VD: 292 Đinh Bộ Lĩnh, Phường 26, Quận Bình Thạnh"
                            required>{{ old('dia_chi') }}</textarea>
                        @error('dia_chi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Số điện thoại liên hệ sẽ lấy từ thông tin nhà xe: <strong>{{ $busCompany->so_dien_thoai ?? 'Chưa có' }}</strong>
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Lưu trạm xe
                    </button>
                    <a href="{{ route('bus-owner.tram-xe.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: '-- Chọn tỉnh/thành phố --',
            allowClear: true
        });
    });
</script>
@endpush