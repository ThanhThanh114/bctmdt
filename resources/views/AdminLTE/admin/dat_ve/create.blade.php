@extends('layouts.admin')

@section('title', 'Thêm đặt vé mới')
@section('page-title', 'Thêm đặt vé mới')
@section('breadcrumb', 'Thêm mới')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-plus-circle mr-2"></i>Thông tin đặt vé
                </h3>
            </div>
            <form action="{{ route('admin.datve.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">Khách hàng <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->fullname }} - {{ $user->email }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="chuyen_xe_id">Chuyến xe <span class="text-danger">*</span></label>
                                <select name="chuyen_xe_id" id="chuyen_xe_id" class="form-control select2 @error('chuyen_xe_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn chuyến xe --</option>
                                    @foreach($chuyenXes as $cx)
                                    <option value="{{ $cx->id }}"
                                        data-gia="{{ $cx->gia_ve }}"
                                        data-nhaxe="{{ $cx->nhaXe->ten_nha_xe ?? 'N/A' }}"
                                        {{ old('chuyen_xe_id') == $cx->id ? 'selected' : '' }}>
                                        {{ $cx->tramDi->ten_tram ?? 'N/A' }} → {{ $cx->tramDen->ten_tram ?? 'N/A' }}
                                        - {{ $cx->ngay_di ? \Carbon\Carbon::parse($cx->ngay_di)->format('d/m/Y') : 'N/A' }}
                                        - {{ number_format($cx->gia_ve, 0, ',', '.') }} VNĐ
                                    </option>
                                    @endforeach
                                </select>
                                @error('chuyen_xe_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted" id="trip-info"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="so_ghe">Số ghế <span class="text-danger">*</span></label>
                                <input type="text" name="so_ghe" id="so_ghe"
                                    class="form-control @error('so_ghe') is-invalid @enderror"
                                    value="{{ old('so_ghe') }}"
                                    placeholder="Ví dụ: A01,A02,B05"
                                    required>
                                <small class="form-text text-muted">
                                    Nhập các số ghế cách nhau bởi dấu phẩy (,)
                                </small>
                                @error('so_ghe')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trang_thai">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" id="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror" required>
                                    <option value="Đã đặt" {{ old('trang_thai') == 'Đã đặt' ? 'selected' : '' }}>
                                        🟡 Đã đặt
                                    </option>
                                    <option value="Đã thanh toán" {{ old('trang_thai') == 'Đã thanh toán' ? 'selected' : '' }}>
                                        🟢 Đã thanh toán
                                    </option>
                                    <option value="Đã hủy" {{ old('trang_thai') == 'Đã hủy' ? 'selected' : '' }}>
                                        🔴 Đã hủy
                                    </option>
                                </select>
                                @error('trang_thai')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Lưu đặt vé
                    </button>
                    <a href="{{ route('admin.datve.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Hướng dẫn
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Mã vé tự động:</strong><br>
                    Hệ thống sẽ tự động tạo mã vé theo định dạng: BK + Năm + Tháng + Ngày + Số thứ tự
                </div>

                <div class="callout callout-warning">
                    <h5><i class="fas fa-chair text-warning"></i> Nhập số ghế</h5>
                    <p class="mb-0">Các ghế cách nhau bởi dấu phẩy</p>
                    <small class="text-muted">Ví dụ: A01,A02,B10</small>
                </div>

                <div class="callout callout-success">
                    <h5><i class="fas fa-dollar-sign text-success"></i> Giá vé</h5>
                    <p class="mb-0">Giá vé được tính theo chuyến xe đã chọn nhân với số lượng ghế</p>
                </div>

                <div class="callout callout-primary">
                    <h5><i class="fas fa-calendar text-primary"></i> Ngày đặt</h5>
                    <p class="mb-0">Ngày đặt sẽ được lưu tự động là thời điểm hiện tại</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Hiển thị thông tin chuyến xe khi chọn
    document.addEventListener('DOMContentLoaded', function() {
        const chuyenXeSelect = document.getElementById('chuyen_xe_id');
        const tripInfo = document.getElementById('trip-info');

        chuyenXeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const gia = selectedOption.getAttribute('data-gia');
            const nhaxe = selectedOption.getAttribute('data-nhaxe');

            if (gia && this.value) {
                const giaFormatted = new Intl.NumberFormat('vi-VN').format(gia);
                tripInfo.innerHTML = `
                <i class="fas fa-bus text-primary"></i> Nhà xe: <strong>${nhaxe}</strong> | 
                <i class="fas fa-dollar-sign text-success"></i> Giá vé: <strong>${giaFormatted} VNĐ</strong>
            `;
            } else {
                tripInfo.innerHTML = '';
            }
        });
    });
</script>
@endsection