@extends('app')
    <link rel="stylesheet" href="{{ asset('assets/css/Tracuu.css') }}">
@section('content')
<div class="tracuu-container">
    <h2 class="title">TRA CỨU THÔNG TIN ĐẶT VÉ</h2>

    <!-- Form -->
    <form method="POST" class="form-box">
        @csrf
        <div class="input-group">
            <i class="fa fa-phone"></i>
            <input type="text" name="phone" placeholder="Vui lòng nhập số điện thoại" required>
        </div>
        <div class="input-group">
            <i class="fa fa-ticket"></i>
            <input type="text" name="code" placeholder="Vui lòng nhập mã vé" required>
        </div>
        <button type="submit" class="search-btn">
            <i class="fa fa-search"></i> Tra cứu
        </button>
    </form>

    <!-- Kết quả -->
    <div class="result-box" style="display:block;">
        @if ($error)
            <p style="color:red; text-align:center;">{{ $error }}</p>
        @elseif ($result)
            <div class="result-card">
                <h3>Thông tin vé</h3>
                <p><strong>Khách hàng:</strong> {{ $result->fullname }}</p>
                <p><strong>SĐT:</strong> {{ $result->phone }}</p>
                <p><strong>Mã vé:</strong> {{ $result->ma_ve }}</p>
                <p><strong>Ghế:</strong> {{ $result->so_ghe }}</p>
                <p><strong>Xe:</strong> {{ $result->ten_xe }} ({{ $result->nha_xe_ten }})</p>
                <p><strong>Tuyến:</strong> {{ $result->diem_di_ten }} → {{ $result->diem_den_ten }}</p>
                <p><strong>Thời gian:</strong> {{ $result->gio_di }} {{ \Carbon\Carbon::parse($result->ngay_di)->format('d/m/Y') }}</p>
                <p><strong>Giá vé:</strong> {{ number_format($result->gia_ve, 0, ",", ".") }}đ</p>
                <p><strong>Trạng thái:</strong> {{ $result->trang_thai }}</p>
            </div>
        @endif
    </div>
</div>
    <script src="{{ asset('assets/js/Tracuu.js') }}"></script>
@endsection
