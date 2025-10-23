<!-- Password Change -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Đổi mật khẩu</h3>
    </div>
    <div class="card-body">
        @if(session('password_success'))
            <div class="alert alert-success">
                {{ session('password_success') }}
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
        <form method="POST" action="{{ url('/password') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="8">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
        </form>
    </div>
</div>
