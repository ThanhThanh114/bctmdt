<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Không tìm thấy vé - Theo dõi chuyến</title>
    <link rel="stylesheet" href="{{ asset('assets/css/BookingHistory.css') }}">
    <style>
        body { font-family: Poppins, sans-serif; padding: 40px; }
        .card { max-width: 720px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 8px; }
        .similar { margin-top: 16px; }
        .similar code { background:#f7f7f7; padding:6px 8px; border-radius:4px; display:inline-block; margin:4px }
        .verify-form { margin-top: 18px }
        .btn { background:#1976d2; color:#fff; padding:8px 12px; border-radius:4px; text-decoration:none }
    </style>
</head>
<body>
    <div class="card">
        <h2>Không tìm thấy vé {{ $maVe }}</h2>
        <p>Chúng tôi không tìm thấy vé với mã trên. Bạn có thể:</p>
        <ul>
            <li>Kiểm tra lại mã vé (không có khoảng trắng) hoặc</li>
            <li>Sử dụng form xác minh với số điện thoại hoặc email để tìm vé.</li>
        </ul>

        <div class="verify-form">
            <form method="POST" action="{{ route('booking.track.verify') }}">
                @csrf
                <input type="hidden" name="ma_ve" value="{{ $maVe }}">
                <div>
                    <label>Số điện thoại</label><br>
                    <input name="phone" type="text" value="" placeholder="0901234567">
                </div>
                <div style="margin-top:8px">
                    <label>Email</label><br>
                    <input name="email" type="email" value="" placeholder="you@example.com">
                </div>
                <div style="margin-top:12px">
                    <button class="btn" type="submit">Xác minh và theo dõi</button>
                </div>
            </form>
        </div>

        @if(!empty($similarCodes))
            <div class="similar">
                <h4>Mã vé tương tự</h4>
                @foreach($similarCodes as $code)
                    <a href="{{ url('/booking-history/track/' . $code) }}"><code>{{ $code }}</code></a>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
