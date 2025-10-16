@extends('app')

@section('title', 'Hóa Đơn Vé - FUTA Bus Lines')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/HoaDon.css') }}">

<main class="hd-container">
    <div class="hd-inner">
        <div class="hd-top">
            <div class="brand">
                <div class="logo">F</div>
                <div>
                    <h1>Hóa Đơn Vé - FUTA</h1>
                    <p class="muted">Hóa đơn điện tử / Vé chuyến - Dữ liệu động</p>
                </div>
            </div>

            <div class="hd-actions">
                <button id="printBtn" class="btn btn-outline">
                    <i class="fa fa-print"></i> In hóa đơn
                </button>
                <button id="pdfBtn" class="btn btn-primary">
                    <i class="fa fa-file-pdf"></i> Tải PDF
                </button>
            </div>
        </div>

        <section id="invoiceCard" class="invoice-card dynamic-data" data-animate>
            <header class="invoice-header">
                <div class="company">
                    <div class="company-logo">
                        <div class="logo-mark">F</div>
                        <div>
                            <h2>{{ htmlspecialchars($invoice_data['bus_company_name']) }}</h2>
                            <div class="muted">Địa chỉ: {{ htmlspecialchars($invoice_data['bus_company_address']) }}</div>
                            <div class="muted">Hotline: {{ htmlspecialchars($invoice_data['bus_company_phone']) }}</div>
                        </div>
                    </div>
                </div>

                <div class="invoice-meta">
                    <div class="meta-row">
                        <label>Mã hóa đơn</label>
                        <div class="meta-value">{{ htmlspecialchars($invoice_data['invoice_number']) }}</div>
                    </div>
                    <div class="meta-row">
                        <label>Ngày</label>
                        <div class="meta-value">{{ \Carbon\Carbon::parse($invoice_data['invoice_date'])->format('d/m/Y') }}</div>
                    </div>
                    <div class="meta-row">
                        <label>Trạng thái</label>
                        <div class="meta-value tag {{ $invoice_data['invoice_status'] == 'Đã thanh toán' ? 'success' : ($invoice_data['invoice_status'] == 'Đã hủy' ? 'danger' : 'warning') }}">
                            {{ htmlspecialchars($invoice_data['invoice_status']) }}
                        </div>
                    </div>
                </div>
            </header>

            <section class="invoice-info">
                <div class="col">
                    <h3>Thông tin hành khách</h3>
                    <div class="info-row"><strong>Họ tên:</strong> {{ htmlspecialchars($invoice_data['cust_name']) }}</div>
                    <div class="info-row"><strong>SĐT:</strong> {{ htmlspecialchars($invoice_data['cust_phone']) }}</div>
                    <div class="info-row"><strong>Email:</strong> {{ htmlspecialchars($invoice_data['cust_email']) }}</div>
                </div>

                <div class="col">
                    <h3>Thông tin chuyến</h3>
                    <div class="info-row"><strong>Tuyến:</strong> {{ htmlspecialchars($invoice_data['departure_station'] . " (" . $invoice_data['departure_province'] . ") → " . $invoice_data['arrival_station'] . " (" . $invoice_data['arrival_province'] . ")") }}</div>
                    <div class="info-row"><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($invoice_data['trip_date'])->format('d/m/Y') }}</div>
                    <div class="info-row"><strong>Giờ:</strong> {{ \Carbon\Carbon::parse($invoice_data['trip_time'])->format('H:i') }}</div>
                    <div class="info-row"><strong>Ghế:</strong> {{ htmlspecialchars($invoice_data['seat_number']) }}</div>
                    <div class="info-row"><strong>Loại xe:</strong> {{ htmlspecialchars($invoice_data['bus_type']) }}</div>
                </div>

                <div class="col">
                    <h3>Thanh toán</h3>
                    <div class="info-row"><strong>Phương thức:</strong> Tiền mặt</div>
                    <div class="info-row"><strong>Mã giao dịch:</strong> #{{ htmlspecialchars($invoice_data['invoice_number']) }}</div>
                    <div class="info-row"><strong>Phí xử lý:</strong> 0₫</div>
                </div>
            </section>

            <section class="invoice-items">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mô tả</th>
                            <th>SL</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $base_price = (float) $invoice_data['ticket_price'];
                            $discount_percentage = (float) ($invoice_data['discount_percentage'] ?? 0);
                            $discount_amount = $base_price * ($discount_percentage / 100);
                            $total_amount = max(0, $base_price - $discount_amount);
                        @endphp
                        <tr>
                            <td>1</td>
                            <td>Vé xe khách - Hạng {{ htmlspecialchars($invoice_data['bus_type']) }}</td>
                            <td>1</td>
                            <td class="text-right">{{ number_format($base_price, 0, ',', '.') }}₫</td>
                            <td class="text-right">{{ number_format($total_amount, 0, ',', '.') }}₫</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="invoice-totals">
                <div class="totals-left">
                    <div class="qr-block">
                        <img src="{{ asset('assets/images/QR.png') }}" alt="QR Code hóa đơn" />
                        <div class="muted">Quét để kiểm tra</div>
                    </div>
                </div>

                <div class="totals-right">
                    <div class="tot-row"><span>Tạm tính</span><span>{{ number_format($base_price, 0, ',', '.') }}₫</span></div>
                    @if ($discount_amount > 0)
                    <div class="tot-row"><span>Giảm giá ({{ htmlspecialchars($discount_percentage) }}%)</span><span>-{{ number_format($discount_amount, 0, ',', '.') }}₫</span></div>
                    @endif
                    <div class="tot-row"><span>Thuế (VAT 10%)</span><span>{{ number_format($total_amount * 0.1, 0, ',', '.') }}₫</span></div>
                    <div class="tot-row total"><span>Tổng cộng</span><span>{{ number_format($total_amount + ($total_amount * 0.1), 0, ',', '.') }}₫</span></div>
                </div>
            </section>
        </section>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="{{ asset('assets/js/HoaDon.js') }}"></script>
@endsection
