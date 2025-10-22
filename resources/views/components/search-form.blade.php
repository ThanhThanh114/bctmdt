@php
// Lấy danh sách tất cả trạm xe
$stations = \App\Models\TramXe::orderBy('ten_tram')->get();

// Lấy giá trị từ GET parameters
$start = request('start', '');
$end = request('end', '');
$date = request('date', date('Y-m-d'));
$ticket = request('ticket', 1);
$tripType = request('trip', 'oneway');

// Xác định action dựa trên trang hiện tại
$currentPage = basename(request()->path());
$action = '';
if ($currentPage == 'lichtrinh') {
$action = route('trips.trips');
} elseif ($currentPage == 'datve') {
$action = route('booking.booking');
} else {
$action = route('home');
}
@endphp


<div class="search-form-container">
    <div class="search-form-wrapper">
        <div class="trip-type-wrapper">
            <div class="trip-type-radio">
                <label class="radio-option {{ $tripType === 'oneway' ? 'active' : '' }}">
                    <input type="radio" name="trip-type" value="oneway" {{ $tripType === 'oneway' ? 'checked' : '' }}>
                    <span class="radio-text">Một chiều</span>
                </label>
                <label class="radio-option {{ $tripType === 'roundtrip' ? 'active' : '' }}">
                    <input type="radio" name="trip-type" value="roundtrip"
                        {{ $tripType === 'roundtrip' ? 'checked' : '' }}>
                    <span class="radio-text">Khứ hồi</span>
                </label>
            </div>
            <span class="guide-link">
                <a href="{{ route('guide') }}" target="_blank">Hướng dẫn mua vé</a>
            </span>
        </div>

        <form class="search-form" method="GET" action="{{ $action }}">
            <div class="search-row">
                <div class="search-locations">
                    <div class="location-field">
                        <label>Điểm đi</label>
                        <div class="location-input">
                            <select name="start" required>
                                <option value="">Chọn điểm đi</option>
                                @foreach($stations as $station)
                                <option value="{{ $station->ten_tram }}"
                                    {{ $start == $station->ten_tram ? 'selected' : '' }}>
                                    {{ $station->ten_tram }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="switch-location" onclick="switchLocations()">
                        <div class="switch-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>

                    <div class="location-field">
                        <label>Điểm đến</label>
                        <div class="location-input">
                            <select name="end" required>
                                <option value="">Chọn điểm đến</option>
                                @foreach($stations as $station)
                                <option value="{{ $station->ten_tram }}"
                                    {{ $end == $station->ten_tram ? 'selected' : '' }}>
                                    {{ $station->ten_tram }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="search-row-bottom">
                <div class="date-ticket-wrapper">
                    <div class="date-field">
                        <label>Ngày đi</label>
                        <div class="date-input">
                            <input type="date" name="date" value="{{ $date }}" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="ticket-field">
                        <label>Số vé</label>
                        <div class="ticket-input">
                            <select name="ticket">
                                <option value="1" {{ $ticket == 1 ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $ticket == 2 ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $ticket == 3 ? 'selected' : '' }}>3</option>
                                <option value="4" {{ $ticket == 4 ? 'selected' : '' }}>4</option>
                                <option value="5" {{ $ticket == 5 ? 'selected' : '' }}>5</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="search-button-wrapper">
                <button type="submit" class="search-button">Tìm chuyến xe</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchLocations() {
    const startSelect = document.querySelector('select[name="start"]');
    const endSelect = document.querySelector('select[name="end"]');

    const startValue = startSelect.value;
    const endValue = endSelect.value;

    startSelect.value = endValue;
    endSelect.value = startValue;
}

// Radio button functionality
document.querySelectorAll('input[name="trip-type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.radio-option').forEach(option => {
            option.classList.remove('active');
        });
        this.closest('.radio-option').classList.add('active');
    });
});
</script>