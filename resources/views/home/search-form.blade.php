<div class="search-form-container">
    <div class="search-form-wrapper">
        <div class="trip-type-wrapper">
            <div class="trip-type-radio">
                <label class="radio-option {{ ($trip ?? '') == 'oneway' ? 'active' : '' }}">
                    <input type="radio" name="trip" value="oneway" {{ ($trip ?? '') != 'round' ? 'checked' : '' }}>
                    <span class="radio-text">Một chiều</span>
                </label>
                <label class="radio-option {{ ($trip ?? '') == 'round' ? 'active' : '' }}">
                    <input type="radio" name="trip" value="round">
                    <span class="radio-text">Khứ hồi</span>
                </label>
            </div>
            <span class="guide-link">
                <a href="{{ route('guide') }}" target="_blank">Hướng dẫn mua vé</a>
            </span>
        </div>

        <form class="search-form" method="GET" action="{{ route('trips.trips') }}">
            <div class="search-row">
                <div class="search-locations">
                    <div class="location-field">
                        <label>Điểm đi</label>
                        <div class="location-input">
                            <select name="start" required>
                                <option value="">Chọn điểm đi</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->ten_tram }}" {{ ($start ?? '') == $city->ten_tram ? 'selected' : '' }}>{{ $city->ten_tram }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                <div class="switch-location" onclick="swapLocations()" title="Đổi vị trí">
                    <div class="switch-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>

                <div class="location-field">
                    <label>Điểm đến</label>
                    <div class="location-input">
                        <select name="end" required>
                            <option value="">Chọn điểm đến</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->ten_tram }}" {{ ($end ?? '') == $city->ten_tram ? 'selected' : '' }}>{{ $city->ten_tram }}</option>
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
                            <input type="date" name="date" value="{{ $date ?? '' }}">
                        </div>
                    </div>
                <div class="ticket-field">
                    <label>Số vé</label>
                    <div class="ticket-input">
                        <select name="ticket">
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ ($ticket ?? 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
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
