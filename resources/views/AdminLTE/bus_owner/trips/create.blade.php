@extends('layouts.admin')

@section('title', 'Thêm chuyến xe mới')

@section('page-title', 'Thêm chuyến xe mới')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bus-owner.trips.index') }}">Quản lý chuyến xe</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin chuyến xe</h3>
            </div>

            <form action="{{ route('bus-owner.trips.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ten_xe">Tên chuyến xe <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten_xe') is-invalid @enderror"
                                    id="ten_xe" name="ten_xe" placeholder="VD: Hà Nội - Hải Phòng"
                                    value="{{ old('ten_xe') }}" required>
                                @error('ten_xe')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ma_tram_di">Trạm đi <span class="text-danger">*</span></label>
                                <select class="form-control @error('ma_tram_di') is-invalid @enderror"
                                    id="ma_tram_di" name="ma_tram_di">
                                    <option value="">-- Chọn trạm đi --</option>
                                    @foreach(\App\Models\TramXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->get() as $tram)
                                    <option value="{{ $tram->ma_tram_xe }}" {{ old('ma_tram_di') == $tram->ma_tram_xe ? 'selected' : '' }}>
                                        {{ $tram->ten_tram }} - {{ $tram->tinh_thanh }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('ma_tram_di')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ma_tram_den">Trạm đến <span class="text-danger">*</span></label>
                                <select class="form-control @error('ma_tram_den') is-invalid @enderror"
                                    id="ma_tram_den" name="ma_tram_den">
                                    <option value="">-- Chọn trạm đến --</option>
                                    @foreach(\App\Models\TramXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->get() as $tram)
                                    <option value="{{ $tram->ma_tram_xe }}" {{ old('ma_tram_den') == $tram->ma_tram_xe ? 'selected' : '' }}>
                                        {{ $tram->ten_tram }} - {{ $tram->tinh_thanh }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('ma_tram_den')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tram_trung_gian">Trạm trung gian (tùy chọn)</label>
                                <select class="form-control select2" id="tram_trung_gian" name="tram_trung_gian[]" multiple>
                                    @foreach(\App\Models\TramXe::where('ma_nha_xe', $bus_company->ma_nha_xe)->get() as $tram)
                                    <option value="{{ $tram->ma_tram_xe }}">
                                        {{ $tram->ten_tram }} - {{ $tram->tinh_thanh }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Chọn nhiều trạm trung gian (nếu có). Giữ Ctrl để chọn nhiều.</small>
                                @error('tram_trung_gian')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ngay_di">Ngày đi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('ngay_di') is-invalid @enderror"
                                    id="ngay_di" name="ngay_di" value="{{ old('ngay_di', date('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" required>
                                @error('ngay_di')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gio_di">Giờ đi <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('gio_di') is-invalid @enderror"
                                    id="gio_di" name="gio_di" value="{{ old('gio_di') }}" required>
                                @error('gio_di')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loai_xe">Loại xe</label>
                                <select class="form-control @error('loai_xe') is-invalid @enderror"
                                    id="loai_xe" name="loai_xe">
                                    <option value="">-- Chọn loại xe --</option>
                                    <option value="Giường nằm" {{ old('loai_xe') == 'Giường nằm' ? 'selected' : '' }}>Giường nằm</option>
                                    <option value="Ghế ngồi" {{ old('loai_xe') == 'Ghế ngồi' ? 'selected' : '' }}>Ghế ngồi</option>
                                    <option value="Limousine" {{ old('loai_xe') == 'Limousine' ? 'selected' : '' }}>Limousine</option>
                                </select>
                                @error('loai_xe')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="loai_chuyen">Loại chuyến <span class="text-danger">*</span></label>
                                <select class="form-control @error('loai_chuyen') is-invalid @enderror"
                                    id="loai_chuyen" name="loai_chuyen" required>
                                    <option value="Một chiều" {{ old('loai_chuyen') == 'Một chiều' ? 'selected' : '' }}>Một chiều</option>
                                    <option value="Khứ hồi" {{ old('loai_chuyen') == 'Khứ hồi' ? 'selected' : '' }}>Khứ hồi</option>
                                </select>
                                @error('loai_chuyen')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gia_ve">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('gia_ve') is-invalid @enderror"
                                    id="gia_ve" name="gia_ve" placeholder="VD: 150000"
                                    value="{{ old('gia_ve') }}" min="0" step="1000" required>
                                @error('gia_ve')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="so_cho">Tổng số chỗ ngồi <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('so_cho') is-invalid @enderror"
                                    id="so_cho" name="so_cho" placeholder="VD: 40"
                                    value="{{ old('so_cho') }}" min="1" required>
                                @error('so_cho')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="so_ve">Số vé đã bán</label>
                                <input type="number" class="form-control @error('so_ve') is-invalid @enderror"
                                    id="so_ve" name="so_ve" placeholder="VD: 0"
                                    value="{{ old('so_ve', 0) }}" min="0">
                                <small class="form-text text-muted">Để trống hoặc nhập 0 nếu chưa bán vé nào</small>
                                @error('so_ve')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tai_xe_id">Tài xế</label>
                                <select class="form-control @error('tai_xe_id') is-invalid @enderror"
                                    id="tai_xe_id" name="tai_xe_id">
                                    <option value="">-- Chọn tài xế --</option>
                                    @foreach(\App\Models\NhanVien::where('ma_nha_xe', $bus_company->ma_nha_xe)->where('chuc_vu', 'Tài xế')->get() as $taixe)
                                    <option value="{{ $taixe->ma_nv }}"
                                        data-name="{{ $taixe->ten_nv }}"
                                        data-phone="{{ $taixe->so_dien_thoai }}"
                                        {{ old('tai_xe_id') == $taixe->ma_nv ? 'selected' : '' }}>
                                        {{ $taixe->ten_nv }} - {{ $taixe->so_dien_thoai }}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="ten_tai_xe" id="ten_tai_xe" value="{{ old('ten_tai_xe') }}">
                                <input type="hidden" name="sdt_tai_xe" id="sdt_tai_xe" value="{{ old('sdt_tai_xe') }}">
                                @error('tai_xe_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Chọn tài xế từ danh sách nhân viên</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gio_den">Giờ đến (dự kiến)</label>
                                <input type="time" class="form-control @error('gio_den') is-invalid @enderror"
                                    id="gio_den" name="gio_den" value="{{ old('gio_den') }}">
                                @error('gio_den')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Thời gian đến dự kiến</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Lưu chuyến xe
                    </button>
                    <a href="{{ route('bus-owner.trips.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endpush

@push('scripts')
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
@endpush