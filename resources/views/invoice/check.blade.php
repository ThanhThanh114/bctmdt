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
                @php
                    $invoices = $invoice_data['invoices'];
                    $firstInvoice = $invoices[0];
                    $totalTickets = count($invoices);
                    $discounts = $invoice_data['discounts'] ?? [];
                @endphp

                <header class="invoice-header">
                    <div class="company">
                        <div class="company-logo">
                            <div class="logo-mark">F</div>
                            <div>
                                <h2>{{ htmlspecialchars($firstInvoice['bus_company_name']) }}</h2>
                                <div class="muted">Địa chỉ: {{ htmlspecialchars($firstInvoice['bus_company_address']) }}
                                </div>
                                <div class="muted">Hotline: {{ htmlspecialchars($firstInvoice['bus_company_phone']) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-meta">
                        <div class="meta-row">
                            <label>Mã hóa đơn</label>
                            <div class="meta-value">{{ htmlspecialchars($firstInvoice['invoice_number']) }}</div>
                        </div>
                        <div class="meta-row">
                            <label>Ngày</label>
                            <div class="meta-value">
                                {{ \Carbon\Carbon::parse($firstInvoice['invoice_date'])->format('d/m/Y') }}</div>
                        </div>
                        <div class="meta-row">
                            <label>Trạng thái</label>
                            <div
                                class="meta-value tag {{ $firstInvoice['invoice_status'] == 'Đã thanh toán' ? 'success' : ($firstInvoice['invoice_status'] == 'Đã hủy' ? 'danger' : 'warning') }}">
                                {{ htmlspecialchars($firstInvoice['invoice_status']) }}
                            </div>
                        </div>
                    </div>
                </header>

                <section class="invoice-info">
                    <div class="col">
                        <h3>Thông tin hành khách</h3>
                        <div class="info-row"><strong>Họ tên:</strong> {{ htmlspecialchars($firstInvoice['cust_name']) }}
                        </div>
                        <div class="info-row"><strong>SĐT:</strong> {{ htmlspecialchars($firstInvoice['cust_phone']) }}
                        </div>
                        <div class="info-row"><strong>Email:</strong> {{ htmlspecialchars($firstInvoice['cust_email']) }}
                        </div>
                    </div>

                    <div class="col">
                        <h3>Thông tin chuyến</h3>
                        <div class="info-row"><strong>Tuyến:</strong>
                            {{ htmlspecialchars($firstInvoice['departure_station'] . " (" . $firstInvoice['departure_province'] . ") → " . $firstInvoice['arrival_station'] . " (" . $firstInvoice['arrival_province'] . ")") }}
                        </div>
                        <div class="info-row"><strong>Ngày đi:</strong>
                            {{ \Carbon\Carbon::parse($firstInvoice['trip_date'])->format('d/m/Y') }}</div>
                        <div class="info-row"><strong>Giờ:</strong>
                            {{ \Carbon\Carbon::parse($firstInvoice['trip_time'])->format('H:i') }}</div>
                        <div class="info-row"><strong>Số ghế:</strong>
                            <span style="color: #FF6F3C; font-weight: bold;">
                                {{ implode(', ', array_column($invoices, 'seat_number')) }}
                            </span>
                            ({{ $totalTickets }} ghế)
                        </div>
                        <div class="info-row"><strong>Loại xe:</strong> {{ htmlspecialchars($firstInvoice['bus_type']) }}
                        </div>
                    </div>

                    <div class="col">
                        <h3>Thanh toán</h3>
                        <div class="info-row"><strong>Phương thức:</strong> Chuyển khoản</div>
                        <div class="info-row"><strong>Mã giao dịch:</strong>
                            #{{ htmlspecialchars($firstInvoice['invoice_number']) }}</div>
                        <div class="info-row"><strong>Số vé:</strong> {{ $totalTickets }}</div>
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
                                $base_price = (float) $firstInvoice['ticket_price'];
                                $total_base = 0;
                                $total_discount = 0;
                            @endphp

                            @foreach($invoices as $index => $invoice)
                                @php
                                    $ticket_price = (float) $invoice['ticket_price'];
                                    $discount_amount = 0;

                                    // Kiểm tra giảm giá cho vé này
                                    if (isset($discounts[$invoice['dat_ve_id']])) {
                                        $discount = $discounts[$invoice['dat_ve_id']];
                                        $discount_percent = (float) $discount['giam_gia'];
                                        $discount_amount = $ticket_price * ($discount_percent / 100);
                                    }

                                    $final_price = $ticket_price - $discount_amount;
                                    $total_base += $ticket_price;
                                    $total_discount += $discount_amount;
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        Vé xe khách - Ghế <strong>{{ htmlspecialchars($invoice['seat_number']) }}</strong> -
                                        {{ htmlspecialchars($invoice['bus_type']) }}
                                        @if($discount_amount > 0)
                                            <br><small style="color: #28a745;">✓ Đã áp dụng mã giảm giá</small>
                                        @endif
                                    </td>
                                    <td>1</td>
                                    <td class="text-right">{{ number_format($ticket_price, 0, ',', '.') }}₫</td>
                                    <td class="text-right">{{ number_format($final_price, 0, ',', '.') }}₫</td>
                                </tr>
                            @endforeach
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
                        @php
                            $final_total = $total_base - $total_discount;
                        @endphp

                        <div class="tot-row">
                            <span>Tạm tính ({{ $totalTickets }} vé)</span>
                            <span>{{ number_format($total_base, 0, ',', '.') }}₫</span>
                        </div>

                        @if ($total_discount > 0)
                            <div class="tot-row" style="color: #28a745;">
                                <span>Giảm giá</span>
                                <span>-{{ number_format($total_discount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif

                        <div class="tot-row total">
                            <span>Tổng cộng</span>
                            <span>{{ number_format($final_total, 0, ',', '.') }}₫</span>
                        </div>
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