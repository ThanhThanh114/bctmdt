<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        <i class="fas fa-ticket-alt orange mr-2"></i>
        Thông tin chuyến xe
    </h2>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange mb-8">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Thông tin chuyến xe -->
            <div class="flex justify-between mb-4">
                <div class="text-center">
                    <div class="text-2xl font-bold orange">{{ $params['gio_di'] ?: date('H:i', strtotime($selectedTrip->gio_di)) }}</div>
                    <div class="text-sm text-gray-600">{{ $selectedTrip->diem_di }}</div>
                </div>
                <div class="flex-1 mx-4 text-center">
                    <div class="text-xs text-gray-500 mt-1">{{ $params['ngay'] ?: date('d/m/Y', strtotime($selectedTrip->ngay_di)) }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold orange">{{ $params['gio_den'] ?? '19:30' }}</div>
                    <div class="text-sm text-gray-600">{{ $selectedTrip->diem_den }}</div>
                </div>
            </div>

            <!-- Thông tin xe -->
            <div>
                <div><strong>Nhà xe:</strong> {{ $params['nha_xe'] ?: $selectedTrip->ten_nha_xe }}</div>
                <div><strong>Loại xe:</strong> {{ $params['loai_xe'] ?: $selectedTrip->loai_xe }}</div>
                <div><strong>Giá vé:</strong> {{ number_format($params['gia_ve'] ?: $selectedTrip->gia_ve, 0, ',', '.') }}đ</div>
                <div><strong>Chỗ trống:</strong> {{ $params['so_cho_trong'] ?: $selectedTrip->available_seats }} chỗ</div>
            </div>
        </div>

        <!-- Form đặt vé -->
        <form method="POST" action="{{ route('booking.store') }}">
            @csrf
            <input type="hidden" name="trip_id" value="{{ $selectedTrip->id }}">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng vé:</label>
                <select name="seat_count" class="w-full md:w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange focus:border-orange">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} vé</option>
                    @endfor
                </select>
            </div>

            <div class="passenger-form-section">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Thông tin hành khách</h4>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên:</label>
                        <input type="text" name="passenger_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Nhập họ tên đầy đủ">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại:</label>
                        <input type="tel" name="passenger_phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Số điện thoại">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email (không bắt buộc):</label>
                        <input type="email" name="passenger_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="email@example.com">
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mt-8">
                <button type="submit" class="flex-1 bg-orange text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                    <i class="fas fa-credit-card mr-2"></i>Đặt vé ngay
                </button>
                <button type="button" onclick="window.history.back()" class="flex-1 bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </button>
            </div>
        </form>
    </div>
</div>
