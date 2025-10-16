<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Xác thực OTP</title>
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
        margin-bottom: 15px;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
        outline: none;
    }

    input[type="text"]:focus {
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
        transition: background 0.25s;
    }

    .btn:hover {
        background: #0056b3;
    }

    .error {
        color: red;
        margin-bottom: 10px;
        font-size: 14px;
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
        <h2>Xác thực OTP</h2>
        <p>Mã OTP đã gửi về email của bạn.</p>

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

        <form method="post" action="{{ route('password.verify-otp.post') }}">
            @csrf
            <input type="text" name="otp" placeholder="Nhập mã OTP" required maxlength="6">
            <button class="btn" type="submit">Xác nhận</button>
        </form>
        <p><a href="{{ route('password.request') }}">← Gửi lại mã OTP</a></p>
    </div>
</body>

</html>
