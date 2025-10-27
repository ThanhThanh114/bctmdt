@extends('layouts.admin')

@section('title', 'Thêm nhân viên mới')

@section('page-title', 'Thêm nhân viên mới')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('bus-owner.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bus-owner.nhan-vien.index') }}">Quản lý nhân viên</a></li>
<li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Thông tin nhân viên</h3>
            </div>

            <form action="{{ route('bus-owner.nhan-vien.store') }}" method="POST" id="createForm">
                @csrf
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
                        <label for="ten_nv"><i class="fas fa-user mr-1"></i>Tên nhân viên <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control @error('ten_nv') is-invalid @enderror"
                            id="ten_nv"
                            name="ten_nv"
                            placeholder="VD: Nguyễn Văn A"
                            value="{{ old('ten_nv') }}"
                            required
                            autofocus>
                        @error('ten_nv')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Nhập họ tên đầy đủ của nhân viên</small>
                    </div>

                    <div class="form-group">
                        <label for="chuc_vu"><i class="fas fa-briefcase mr-1"></i>Chức vụ <span class="text-danger">*</span></label>
                        <select class="form-control @error('chuc_vu') is-invalid @enderror"
                            id="chuc_vu"
                            name="chuc_vu"
                            required>
                            <option value="">-- Chọn chức vụ --</option>
                            <option value="Tài xế" {{ old('chuc_vu') == 'Tài xế' ? 'selected' : '' }}>Tài xế</option>
                            <option value="Phụ xe" {{ old('chuc_vu') == 'Phụ xe' ? 'selected' : '' }}>Phụ xe</option>
                            <option value="Quản lý" {{ old('chuc_vu') == 'Quản lý' ? 'selected' : '' }}>Quản lý</option>
                            <option value="Nhân viên kỹ thuật" {{ old('chuc_vu') == 'Nhân viên kỹ thuật' ? 'selected' : '' }}>Nhân viên kỹ thuật</option>
                            <option value="Nhân viên bán vé" {{ old('chuc_vu') == 'Nhân viên bán vé' ? 'selected' : '' }}>Nhân viên bán vé</option>
                            <option value="Khác" {{ old('chuc_vu') == 'Khác' ? 'selected' : '' }}>Khác</option>
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
                            value="{{ old('so_dien_thoai') }}"
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
                            value="{{ old('email') }}"
                            required>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Email dùng để liên hệ và đăng nhập (nếu có)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ngay_sinh"><i class="fas fa-birthday-cake mr-1"></i>Ngày sinh</label>
                                <input type="date"
                                    class="form-control @error('ngay_sinh') is-invalid @enderror"
                                    id="ngay_sinh"
                                    name="ngay_sinh"
                                    value="{{ old('ngay_sinh') }}"
                                    max="{{ date('Y-m-d') }}">
                                @error('ngay_sinh')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gioi_tinh"><i class="fas fa-venus-mars mr-1"></i>Giới tính</label>
                                <select class="form-control @error('gioi_tinh') is-invalid @enderror"
                                    id="gioi_tinh"
                                    name="gioi_tinh">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="Nam" {{ old('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gioi_tinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ old('gioi_tinh') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gioi_tinh')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cccd"><i class="fas fa-id-card mr-1"></i>CCCD/CMND</label>
                        <input type="text"
                            class="form-control @error('cccd') is-invalid @enderror"
                            id="cccd"
                            name="cccd"
                            placeholder="VD: 001234567890"
                            value="{{ old('cccd') }}"
                            pattern="[0-9]{9,12}"
                            maxlength="12">
                        @error('cccd')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Nhập 9-12 chữ số</small>
                    </div>

                    <div class="form-group">
                        <label for="dia_chi"><i class="fas fa-map-marker-alt mr-1"></i>Địa chỉ</label>
                        <textarea class="form-control @error('dia_chi') is-invalid @enderror"
                            id="dia_chi"
                            name="dia_chi"
                            rows="2"
                            placeholder="VD: 123 Đường ABC, Phường XYZ, Quận 1, TP.HCM">{{ old('dia_chi') }}</textarea>
                        @error('dia_chi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="card card-warning collapsed-card" id="accountCard">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-lock mr-2"></i>Tài khoản đăng nhập (Chỉ dành cho Quản lý)</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Lưu ý:</strong> Chỉ nhân viên có chức vụ <strong>"Quản lý"</strong> mới được tạo tài khoản đăng nhập với quyền Staff của nhà xe.
                            </div>

                            <div class="form-group">
                                <label for="password"><i class="fas fa-key mr-1"></i>Mật khẩu</label>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                                    minlength="6">
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation"><i class="fas fa-key mr-1"></i>Xác nhận mật khẩu</label>
                                <input type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="icon fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> Nhân viên sẽ được gán cho nhà xe <strong>{{ $nhaXe->ten_nha_xe }}</strong>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Lưu nhân viên
                    </button>
                    <a href="{{ route('bus-owner.nhan-vien.index') }}" class="btn btn-secondary">
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
        // Phone number validation
        $('#so_dien_thoai').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // CCCD validation
        $('#cccd').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Show/hide account section based on position
        $('#chuc_vu').on('change', function() {
            const chucVu = $(this).val();
            const $accountCard = $('#accountCard');
            
            if (chucVu === 'Quản lý') {
                if ($accountCard.hasClass('collapsed-card')) {
                    $accountCard.find('[data-card-widget="collapse"]').click();
                }
                $accountCard.removeClass('card-warning').addClass('card-success');
            } else {
                if (!$accountCard.hasClass('collapsed-card')) {
                    $accountCard.find('[data-card-widget="collapse"]').click();
                }
                $accountCard.removeClass('card-success').addClass('card-warning');
                // Clear password fields
                $('#password, #password_confirmation').val('');
            }
        });

        // Form validation before submit
        $('#createForm').on('submit', function(e) {
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

            // Validate CCCD if provided
            const cccd = $('#cccd').val();
            if (cccd && (cccd.length < 9 || cccd.length > 12)) {
                errors.push('CCCD phải có 9-12 chữ số');
                isValid = false;
            }

            // Validate password for Quản lý
            if (chucVu === 'Quản lý') {
                const password = $('#password').val();
                const passwordConfirm = $('#password_confirmation').val();
                
                if (password || passwordConfirm) {
                    if (password.length < 6) {
                        errors.push('Mật khẩu phải có ít nhất 6 ký tự');
                        isValid = false;
                    }
                    if (password !== passwordConfirm) {
                        errors.push('Xác nhận mật khẩu không khớp');
                        isValid = false;
                    }
                }
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

        // Show success message if redirected with success
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            timer: 3000
        });
        @endif
    });
</script>
@endpush