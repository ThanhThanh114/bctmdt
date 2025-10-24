@if(config('app.debug'))
<!-- Debug info -->
<div class="alert alert-info">
    <strong>Debug Info:</strong>
    <ul class="mb-0">
        <li>Bookings 7 days: {{ count($daily_revenue_7 ?? []) }} days, Total:
            {{ number_format(array_sum($daily_revenue_7 ?? [])) }} VNĐ
        </li>
        <li>Bookings 30 days: {{ count($daily_revenue_30 ?? []) }} days, Total:
            {{ number_format(array_sum($daily_revenue_30 ?? [])) }} VNĐ
        </li>
        <li>Monthly 12: {{ count($monthly_revenue_12 ?? []) }} months, Total:
            {{ number_format(array_sum($monthly_revenue_12 ?? [])) }} VNĐ
        </li>
        <li>Yearly: {{ count($yearly_revenue ?? []) }} years, Total:
            {{ number_format(array_sum($yearly_revenue ?? [])) }} VNĐ
        </li>
    </ul>
</div>
@endif
