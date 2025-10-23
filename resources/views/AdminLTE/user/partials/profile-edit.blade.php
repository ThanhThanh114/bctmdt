<!-- Profile Edit -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cập nhật thông tin cá nhân</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('user.dashboard.update-profile') }}">
            @csrf
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $user->fullname }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}">
            </div>
            <div class="form-group">
                <label for="date_of_birth">Ngày sinh</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '' }}">
            </div>
            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Chọn giới tính</option>
                    <option value="Nam" {{ $user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ $user->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
        </form>
    </div>
</div>
