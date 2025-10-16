<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <title>Quên mật khẩu</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px 25px;
            text-align: center;
            max-width: 400px;
            margin: 60px auto;
        }

        .card h2 {
            margin-bottom: 15px;
            color: #333;
            font-size: 22px;
        }

        .card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: 500;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            outline: none;
        }

        input[type="email"]:focus {
            border-color: #007bff;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Quên mật khẩu</h2>
        <p>Vui lòng nhập email bạn đã đăng ký để nhận mã xác nhận.</p>

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="message error">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="post" action="{{ route('password.email') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" placeholder="Nhập email của bạn" required>
            <button class="btn" type="submit">Gửi mã</button>
        </form>
        <p><a href="{{ url('/') }}">← Quay lại trang chủ</a></p>
    </div>
</body>

</html>
