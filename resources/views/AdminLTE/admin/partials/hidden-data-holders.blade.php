<!-- hidden data holders for charts -->
<div id="monthlyBookingsData" data-json="{{ json_encode($monthly_bookings ?? []) }}" style="display:none;"></div>
<div id="dailyRevenue7Data" data-json="{{ json_encode($daily_revenue_7 ?? []) }}" style="display:none;"></div>
<div id="dailyRevenue30Data" data-json="{{ json_encode($daily_revenue_30 ?? []) }}" style="display:none;"></div>
<div id="monthlyRevenue12Data" data-json="{{ json_encode($monthly_revenue_12 ?? []) }}" style="display:none;"></div>
<div id="yearlyRevenueData" data-json="{{ json_encode($yearly_revenue ?? []) }}" style="display:none;"></div>
<div id="topRoutesData" data-json="{{ json_encode($top_routes ?? []) }}" style="display:none;"></div>
<div id="topCustomersData" data-json="{{ json_encode($top_customers ?? []) }}" style="display:none;"></div>
