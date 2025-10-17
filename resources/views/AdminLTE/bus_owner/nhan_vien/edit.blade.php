@extends('layouts.admin')

@section('title', 'Chỉnh sửa nhân viên')

@section('page-title', 'Chỉnh sửa nhân viên')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bus-owner.nhan-vien.index') }}">Quản lý nhân viên</a></li>
<li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Chỉnh sửa thông tin nhân viên</h3>
            </div>

            <form action="{{ route('bus-owner.nhan-vien.update', $nhanVien->ma_nv) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Mã nhân viên</label>
                        <input type="text" class="form-control" value="#{{ $nhanVien->ma_nv }}" disabled>
                        <small class="form-text text-muted">Mã nhân viên không thể thay đổi</small>
                    </div>

                    <div class="form-group">
                        <label for="ten_nv"><i class="fas fa-user mr-1"></i>Tên nhân viên <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('ten_nv') is-invalid @enderror"
                            id="ten_nv"
                            name="ten_nv"
                            placeholder="VD: Nguyễn Văn A"
                            value="{{ old('ten_nv', $nhanVien->ten_nv) }}"
                            required
                            autofocus>
                        @error('ten_nv')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="chuc_vu"><i class="fas fa-briefcase mr-1"></i>Chức vụ <span class="text-danger">*</span></label>
                        <select class="form-control @error('chuc_vu') is-invalid @enderror"
                            id="chuc_vu"
                            name="chuc_vu"
                            required>
                            <option value="">-- Chọn chức vụ --</option>
                            <option value="Tài xế" {{ old('chuc_vu', $nhanVien->chuc_vu) == 'Tài xế' ? 'selected' : '' }}>Tài xế</option>
                            <option value="Phụ xe" {{ old('chuc_vu', $nhanVien->chuc_vu) == 'Phụ xe' ? 'selected' : '' }}>Phụ xe</option>
                            <option value="Quản lý" {{ old('chuc_vu', $nhanVien->chuc_vu) == 'Quản lý' ? 'selected' : '' }}>Quản lý</option>
                            <option value="Nhân viên kỹ thuật" {{ old('chuc_vu', $nhanVien->chuc_vu) == 'Nhân viên kỹ thuật' ? 'selected' : '' }}>Nhân viên kỹ thuật</option>
                            <option value="Nhân viên bán vé" {{ old('chuc_vu', $nhanVien->chuc_vu) == 'Nhân viên bán vé' ? 'selected' : '' }}>Nhân viên bán vé</option>
                            <option value="Khác" {{ old('chuc_vu', $nhanVien->chuc_vu) == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('chuc_vu')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="so_dien_thoai"><i class="fas fa-phone mr-1"></i>Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('so_dien_thoai') is-invalid @enderror"
                            id="so_dien_thoai"
                            name="so_dien_thoai"
                            placeholder="VD: 0912345678"
                            value="{{ old('so_dien_thoai', $nhanVien->so_dien_thoai) }}"
                            required
                            pattern="[0-9]{10,11}"
                            maxlength="11">
                        @error('so_dien_thoai')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Nhập 10-11 chữ số</small>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope mr-1"></i>Email <span class="text-danger">*</span></label>
                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="VD: nhanvien@example.com"
                            value="{{ old('email', $nhanVien->email) }}"
                            required>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="icon fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> Nhân viên thuộc nhà xe <strong>{{ $nhaXe->name }}</strong>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i>Cập nhật
                    </button>
                    <button type="button" class="btn btn-secondary" id="undoBtn">
                        <i class="fas fa-undo mr-2"></i>Hoàn tác
                    </button>
                    <a href="{{ route('bus-owner.nhan-vien.index') }}" class="btn btn-default">
                        <i class="fas fa-times mr-2"></i>Hủy bỏ
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
        // Store original values
        const originalValues = {
            ten_nv: '{{ $nhanVien->ten_nv }}',
            chuc_vu: '{{ $nhanVien->chuc_vu }}',
            so_dien_thoai: '{{ $nhanVien->so_dien_thoai }}',
            email: '{{ $nhanVien->email }}'
        };

        // Phone number validation
        $('#so_dien_thoai').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Undo button
        $('#undoBtn').on('click', function() {
            $('#ten_nv').val(originalValues.ten_nv);
            $('#chuc_vu').val(originalValues.chuc_vu);
            $('#so_dien_thoai').val(originalValues.so_dien_thoai);
            $('#email').val(originalValues.email);

            Swal.fire({
                icon: 'info',
                title: 'Đã hoàn tác',
                text: 'Các thay đổi đã được hoàn tác',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Track changes
        let hasChanges = false;
        $('input, select').on('change', function() {
            hasChanges = true;
        });

        // Warn before leaving if there are unsaved changes
        $(window).on('beforeunload', function() {
            if (hasChanges) {
                return 'Bạn có thay đổi chưa được lưu. Bạn có chắc muốn rời khỏi trang?';
            }
        });

        // Don't warn when submitting
        $('#editForm').on('submit', function() {
            hasChanges = false;
        });

        // Form validation before submit
        $('#editForm').on('submit', function(e) {
            let isValid = true;
            let errors = [];

            // Validate name
            const tenNv = $('#ten_nv').val().trim();
            if (tenNv.length < 3) {
                errors.push('Tên nhân viên phải có ít nhất 3 ký tự');
                isValid = false;
            }

            // Validate position
            const chucVu = $('#chuc_vu').val();
            if (!chucVu) {
                errors.push('Vui lòng chọn chức vụ');
                isValid = false;
            }

            // Validate phone
            const phone = $('#so_dien_thoai').val();
            if (phone.length < 10 || phone.length > 11) {
                errors.push('Số điện thoại phải có 10-11 chữ số');
                isValid = false;
            }

            // Validate email
            const email = $('#email').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push('Email không hợp lệ');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi nhập liệu',
                    html: '<ul class="text-left">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>',
                });
            }
        });
    });
</script>
@endpush