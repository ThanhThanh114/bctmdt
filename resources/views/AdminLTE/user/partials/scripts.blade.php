@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Spending Chart
    var ctx = document.getElementById('spendingChart').getContext('2d');
    var spendingData = @json($monthly_spending);

    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
            datasets: [{
                data: [
                    spendingData[1] || 0,
                    spendingData[2] || 0,
                    spendingData[3] || 0,
                    spendingData[4] || 0,
                    spendingData[5] || 0,
                    spendingData[6] || 0,
                    spendingData[7] || 0,
                    spendingData[8] || 0,
                    spendingData[9] || 0,
                    spendingData[10] || 0,
                    spendingData[11] || 0,
                    spendingData[12] || 0
                ],
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545',
                    '#17a2b8', '#6610f2', '#fd7e14', '#20c997',
                    '#e83e8c', '#6f42c1', '#dee2e6', '#495057'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
