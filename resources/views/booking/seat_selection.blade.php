@extends('app')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            max-width: 1100px !important;
            margin: 0 auto !important;
            padding: 15px !important;
        }

        .header-info {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%) !important;
            color: white !important;
            padding: 15px !important;
            border-radius: 12px !important;
            margin-bottom: 15px !important;
            text-align: center !important;
        }

        .route-info {
            font-size: 1.3rem !important;
            font-weight: 600 !important;
            margin-bottom: 6px !important;
        }

        .date-info {
            font-size: 0.9rem !important;
            opacity: 0.9 !important;
        }

        .main-content {
            display: grid !important;
            grid-template-columns: 1fr 320px !important;
            gap: 15px !important;
        }

        .seat-selection {
            background: white !important;
            border-radius: 12px !important;
            padding: 15px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        .seat-legend {
            display: flex !important;
            justify-content: space-between !important;
            margin-bottom: 15px !important;
            font-size: 0.85rem !important;
        }

        .legend-item {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        .legend-color {
            width: 14px !important;
            height: 14px !important;
            border-radius: 3px !important;
        }

        .legend-sold {
            background: #D5D9DD;
        }

        .legend-available {
            background: #DEF3FF;
        }

        .legend-selected {
            background: #FDEDE8;
        }

        .bus-layout {
            display: flex !important;
            gap: 25px !important;
            justify-content: center !important;
        }

        .floor {
            text-align: center !important;
        }

        .floor-title {
            background: #f8f9fa !important;
            padding: 8px !important;
            margin-bottom: 12px !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            color: #666 !important;
            font-size: 0.85rem !important;
        }

        .seats-grid {
            display: grid !important;
            gap: 6px !important;
        }

        .seat-row {
            display: flex !important;
            justify-content: center !important;
            gap: 6px !important;
        }

        .seat {
            width: 38px !important;
            height: 38px !important;
            border: 2px solid !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 9px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            position: relative !important;
        }

        .seat-available {
            background: #DEF3FF !important;
            border-color: #96C5E7 !important;
            color: #339AF4 !important;
        }

        .seat-available:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 12px rgba(51, 154, 244, 0.3) !important;
        }

        .seat-selected {
            background: #FDEDE8 !important;
            border-color: #F8BEAB !important;
            color: #EF5222 !important;
        }

        .seat-sold {
            background: #D5D9DD !important;
            border-color: #C0C6CC !important;
            color: #A2ABB3 !important;
            cursor: not-allowed !important;
        }

        .seat-empty {
            width: 38px !important;
            height: 38px !important;
        }

        .sidebar {
            display: flex !important;
            flex-direction: column !important;
            gap: 15px !important;
        }

        .info-card {
            background: white !important;
            border-radius: 12px !important;
            padding: 15px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        .card-title {
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: #333 !important;
            margin-bottom: 12px !important;
            padding-bottom: 8px !important;
            border-bottom: 2px solid #f0f0f0 !important;
        }

        .info-row {
            display: flex !important;
            justify-content: space-between !important;
            margin-bottom: 8px !important;
            font-size: 0.85rem !important;
        }

        .info-label {
            color: #666 !important;
        }

        .info-value {
            font-weight: 500 !important;
            color: #333 !important;
        }

        .price-total {
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%) !important;
            color: white !important;
            text-align: center !important;
            padding: 12px !important;
            border-radius: 8px !important;
            margin: 12px 0 !important;
        }

        .price-label {
            font-size: 0.85rem !important;
            opacity: 0.9 !important;
        }

        .price-amount {
            font-size: 1.3rem !important;
            font-weight: 700 !important;
        }

        .booking-btn {
            width: 100% !important;
            background: linear-gradient(135deg, #FF7B39 0%, #FF5722 100%) !important;
            color: white !important;
            border: none !important;
            padding: 12px !important;
            border-radius: 20px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
        }

        .booking-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(255, 87, 34, 0.3) !important;
        }

        .booking-btn:disabled {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }

        .back-btn {
            background: #6c757d !important;
            color: white !important;
            border: none !important;
            padding: 8px 16px !important;
            border-radius: 18px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            margin-bottom: 15px !important;
            font-size: 0.9rem !important;
        }

        .back-btn:hover {
            background: #5a6268 !important;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .bus-layout {
                flex-direction: column;
                gap: 20px;
            }

            .container {
                padding: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <button class="back-btn" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Quay lại
        </button>

        <div class="header-info">
            <div class="route-info">
                {{ $trip->tramDi->ten_tram }} → {{ $trip->tramDen->ten_tram }}
            </div>
            <div class="date-info">
                {{ \Carbon\Carbon::parse($trip->ngay_di)->locale('vi')->isoFormat('dddd, DD/MM/YYYY') }} -
                {{ date('H:i', strtotime($trip->gio_di)) }}
            </div>
        </div>

        <div class="main-content">
            <div class="seat-selection">
                <h3 style="text-align: center; margin-bottom: 15px; color: #333; font-size: 1.1rem;">
                    <i class="fas fa-chair"></i> Chọn ghế
                </h3>

                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="legend-color legend-sold"></div>
                        <span>Đã bán</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-available"></div>
                        <span>Còn trống</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-selected"></div>
                        <span>Đang chọn</span>
                    </div>
                </div>

                <div class="bus-layout">
                    <!-- Tầng dưới -->
                    <div class="floor">
                        <div class="floor-title">Tầng dưới</div>
                        <div class="seats-grid">
                            <div class="seat-row">
                                <div class="seat {{ in_array('A01', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A01">A01</div>
                                <div class="seat-empty"></div>
                                <div class="seat-empty"></div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A02', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A02">A02</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('A03', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A03">A03</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A04', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A04">A04</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A05', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A05">A05</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('A06', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A06">A06</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A07', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A07">A07</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A08', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A08">A08</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('A09', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A09">A09</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A10', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A10">A10</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A11', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A11">A11</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('A12', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A12">A12</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A13', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A13">A13</div>
                                <div class="seat-empty"></div>
                                <div class="seat-empty"></div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('A15', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A15">A15</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A16', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A16">A16</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('A17', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="A17">A17</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tầng trên -->
                    <div class="floor">
                        <div class="floor-title">Tầng trên</div>
                        <div class="seats-grid">
                            <div class="seat-row">
                                <div class="seat {{ in_array('B01', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B01">B01</div>
                                <div class="seat-empty"></div>
                                <div class="seat-empty"></div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B02', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B02">B02</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('B03', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B03">B03</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B04', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B04">B04</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B05', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B05">B05</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('B06', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B06">B06</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B07', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B07">B07</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B08', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B08">B08</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('B09', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B09">B09</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B10', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B10">B10</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B11', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B11">B11</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('B12', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B12">B12</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B13', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B13">B13</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B14', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B14">B14</div>
                            </div>
                            <div class="seat-row">
                                <div class="seat {{ in_array('B15', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B15">B15</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B16', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B16">B16</div>
                                <div class="seat-empty"></div>
                                <div class="seat {{ in_array('B17', $bookedSeats) ? 'seat-sold' : 'seat-available' }}"
                                    data-seat="B17">B17</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <!-- Thông tin lượt đi -->
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-route"></i> Thông tin lượt đi
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tuyến xe:</span>
                        <span class="info-value">{{ $trip->tramDi->ten_tram }} - {{ $trip->tramDen->ten_tram }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Thời gian xuất bến:</span>
                        <span
                            class="info-value">{{ date('H:i d/m/Y', strtotime($trip->ngay_di . ' ' . $trip->gio_di)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số lượng ghế:</span>
                        <span class="info-value">{{ $bookingInfo['seat_count'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Điểm trả khách:</span>
                        <span class="info-value">{{ $trip->tramDen->ten_tram }}</span>
                    </div>
                </div>

                <!-- Chi tiết giá -->
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-receipt"></i> Chi tiết giá
                    </div>
                    <div class="info-row">
                        <span class="info-label">Giá vé lượt đi:</span>
                        <span class="info-value" id="unit-price">{{ number_format($trip->gia_ve, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phí thanh toán:</span>
                        <span class="info-value">0đ</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tổng tiền:</span>
                        <span class="info-value" id="total-price">{{ number_format($trip->gia_ve, 0, ',', '.') }}đ</span>
                    </div>

                    <div class="price-total">
                        <div class="price-label">Tổng tiền</div>
                        <div class="price-amount" id="final-total">
                            {{ number_format($trip->gia_ve, 0, ',', '.') }}đ
                        </div>
                    </div>
                </div>

                <!-- Thông tin hành khách -->
                <div class="info-card">
                    <div class="card-title">
                        <i class="fas fa-info-circle"></i> Thông tin hành khách
                    </div>
                    <div class="info-row">
                        <span class="info-label">Họ tên:</span>
                        <span class="info-value">{{ $bookingInfo['passenger_name'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số điện thoại:</span>
                        <span class="info-value">{{ $bookingInfo['passenger_phone'] }}</span>
                    </div>
                    @if(!empty($bookingInfo['passenger_email']))
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $bookingInfo['passenger_email'] }}</span>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('booking.complete') }}" id="booking-form">
                    @csrf
                    <input type="hidden" name="selected_seats" id="selected-seats-input">
                    <button type="button" class="booking-btn" id="complete-booking-btn" disabled>
                        <i class="fas fa-credit-card"></i> Thanh toán
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = [];
        const maxSeats = {{ $bookingInfo['seat_count'] }};
        const unitPrice = {{ $trip->gia_ve }};

        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý click ghế
            document.querySelectorAll('.seat-available').forEach(seat => {
                seat.addEventListener('click', function () {
                    const seatId = this.dataset.seat;

                    if (this.classList.contains('seat-selected')) {
                        // Bỏ chọn ghế
                        this.classList.remove('seat-selected');
                        this.classList.add('seat-available');
                        selectedSeats = selectedSeats.filter(s => s !== seatId);
                    } else if (selectedSeats.length < maxSeats) {
                        // Chọn ghế
                        this.classList.remove('seat-available');
                        this.classList.add('seat-selected');
                        selectedSeats.push(seatId);
                    } else {
                        alert(`Bạn chỉ có thể chọn tối đa ${maxSeats} ghế!`);
                    }

                    updateBookingInfo();
                });
            });

            function updateBookingInfo() {
                const totalPrice = selectedSeats.length * unitPrice;

                document.getElementById('total-price').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
                document.getElementById('final-total').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
                document.getElementById('selected-seats-input').value = JSON.stringify(selectedSeats);

                const completeBtn = document.getElementById('complete-booking-btn');
                if (selectedSeats.length > 0) {
                    completeBtn.disabled = false;
                    completeBtn.innerHTML =
                        `<i class="fas fa-credit-card"></i> Thanh toán (${selectedSeats.join(', ')})`;
                } else {
                    completeBtn.disabled = true;
                    completeBtn.innerHTML = '<i class="fas fa-credit-card"></i> Thanh toán';
                }
            }

            // Xử lý submit form
            document.getElementById('complete-booking-btn').addEventListener('click', function () {
                if (selectedSeats.length === 0) {
                    alert('Vui lòng chọn ghế!');
                    return;
                }

                if (confirm(`Xác nhận đặt vé cho ghế ${selectedSeats.join(', ')}?`)) {
                    document.getElementById('booking-form').submit();
                }
            });
        });
    </script>
@endsection