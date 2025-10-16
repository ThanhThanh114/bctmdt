@if ($totalPages > 1)
    <div class="pagination">
        @for ($i = 1; $i <= $totalPages; $i++)
            <a href="{{ route('trips.trips', array_merge($params, ['page' => $i])) }}" class="page-btn {{ $i === $params['page'] ? 'active' : '' }}">{{ $i }}</a>
        @endfor
    </div>
@else
    <div class="no-results">
        <h3>Không tìm thấy chuyến xe phù hợp</h3>
        <p>Vui lòng thử lại với các tiêu chí tìm kiếm khác hoặc chọn ngày khác.</p>
    </div>
@endif
