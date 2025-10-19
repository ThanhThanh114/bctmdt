@extends('layouts.header-search')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/DatVe.css') }}?v={{ time() }}">
@endpush
@section('content')
    <main class="new-background-color min-h-screen py-8">
        <div class="layout">

            <!-- Hiển thị thông tin chuyến xe đã chọn -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-ticket-alt orange mr-2"></i>
                    Thông tin chuyến xe
                </h2>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange mb-8">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Thông tin tuyến đường -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold orange">
                                        {{ \Carbon\Carbon::parse($trip->gio_di)->format('H:i:s') }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $trip->tramDi->ten_tram }}</div>
                                </div>

                                <div class="flex-1 mx-4">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-orange rounded-full"></div>
                                        <div class="flex-1 h-px bg-gray-300 mx-2 relative">
                                            <i
                                                class="fas fa-bus absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 orange bg-white px-1"></i>
                                        </div>
                                        <div class="w-3 h-3 bg-orange rounded-full"></div>
                                    </div>
                                    <div class="text-center text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($trip->ngay_di)->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div class="text-center">
                                    <div class="text-2xl font-bold orange">
                                        {{ \Carbon\Carbon::parse($trip->gio_den)->format('H:i:s') }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $trip->tramDen->ten_tram }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin xe -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">
                                    <i class="fas fa-building orange mr-2"></i>Nhà xe:
                                </span>
                                <span class="font-semibold">{{ $trip->nhaXe->ten_nha_xe }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">
                                    <i class="fas fa-bus orange mr-2"></i>Loại xe:
                                </span>
                                <span class="font-semibold">{{ $trip->loai_xe }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">
                                    <i class="fas fa-money-bill orange mr-2"></i>Giá vé:
                                </span>
                                <span class="font-bold text-xl orange">
                                    {{ number_format($trip->gia_ve, 0, ',', '.') }}đ
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">
                                    <i class="fas fa-chair orange mr-2"></i>Chỗ trống:
                                </span>
                                <span class="font-semibold text-green-600">
                                    {{ $trip->available_seats }} chỗ
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form đặt vé -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-user-plus orange mr-2"></i>
                        Thông tin đặt vé
                    </h3>

                    <form method="POST" id="bookingForm" action="{{ route('booking.store') }}">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Số lượng vé:
                            </label>
                            <select name="seat_count" id="seatCount"
                                class="w-full md:w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange">
                                <option value="1">1 vé</option>
                                <option value="2">2 vé</option>
                                <option value="3">3 vé</option>
                                <option value="4">4 vé</option>
                                <option value="5">5 vé</option>
                            </select>
                        </div>

                        <div class="passenger-form-section">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                Thông tin hành khách
                            </h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên:</label>
                                    <input type="text" name="passenger_name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange"
                                        placeholder="Nhập họ tên đầy đủ">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại:</label>
                                    <input type="tel" name="passenger_phone" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange"
                                        placeholder="Số điện thoại">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email (không bắt
                                        buộc):</label>
                                    <input type="email" name="passenger_email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange"
                                        placeholder="email@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 mt-8">
                            <button type="submit" name="book_ticket"
                                class="flex-1 bg-orange text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                                <i class="fas fa-credit-card mr-2"></i>
                                Đặt vé ngay
                            </button>
                            <button type="button" onclick="window.history.back()"
                                class="flex-1 bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Quay lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </main>

@endsection

@push('scripts')
    <script src="https://cdn.tailwindcss.com?v=1760337091"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'green': '#059669',
                        'orange': '#ea5a47',
                        'gray': '#6b7280'
                    }
                }
            }
        }
    </script>

    <!-- FUTA Chat Widget - Clean File Version -->
    <script src="{{ asset('assets/js/futa-chat.js') }}?v={{ time() }}"></script>

    <script>
        // Initialize the chat widget when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            console.log('🚀 Initializing FUTA Chat Widget...');

            // Create and initialize the widget
            if (typeof FUTAChatWidget !== 'undefined') {
                new FUTAChatWidget();
                console.log('✅ FUTA Chat Widget loaded successfully!');
            } else {
                console.error('❌ FUTAChatWidget class not found!');
            }
        });
    </script>

    <script>
        // Animation cho các card
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.trip-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endpush