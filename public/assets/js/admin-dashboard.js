$(document).ready(function() {
    console.log('Dashboard charts initializing...');

    // Helper to parse JSON data
    function parseChartData(elId) {
        var el = document.getElementById(elId);
        if (!el) {
            console.warn('Element not found:', elId);
            return {};
        }
        try {
            var data = JSON.parse(el.getAttribute('data-json') || '{}');
            console.log('Parsed data for', elId, ':', data);
            return data;
        } catch (e) {
            console.error('Error parsing data for', elId, ':', e);
            return {};
        }
    }

    // Debug: Log all chart data
    console.log('Monthly Bookings:', parseChartData('monthlyBookingsData'));
    console.log('Daily Revenue 7:', parseChartData('dailyRevenue7Data'));
    console.log('Daily Revenue 30:', parseChartData('dailyRevenue30Data'));
    console.log('Monthly Revenue 12:', parseChartData('monthlyRevenue12Data'));
    console.log('Yearly Revenue:', parseChartData('yearlyRevenueData'));

    // 7-day revenue chart
    var daily7 = parseChartData('dailyRevenue7Data');
    console.log('7-day data keys:', Object.keys(daily7).length);
    if (document.getElementById('chart7Day')) {
        if (Object.keys(daily7).length > 0) {
            console.log('Creating 7-day chart...');
            var labels7 = Object.keys(daily7);
            var data7 = labels7.map(function(k) {
                return daily7[k] || 0;
            });
            new Chart(document.getElementById('chart7Day').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels7,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data7,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 1200
                    }
                }
            });
        } else {
            console.warn('7-day chart: No data available');
        }
    } else {
        console.error('7-day chart: Canvas element not found');
    }

    // 30-day revenue chart
    var daily30 = parseChartData('dailyRevenue30Data');
    console.log('30-day data keys:', Object.keys(daily30).length);
    if (document.getElementById('chart30Day')) {
        if (Object.keys(daily30).length > 0) {
            console.log('Creating 30-day chart...');
            var labels30 = Object.keys(daily30);
            var data30 = labels30.map(function(k) {
                return daily30[k] || 0;
            });
            new Chart(document.getElementById('chart30Day').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels30,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data30,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 1200
                    }
                }
            });
        } else {
            console.warn('30-day chart: No data available');
        }
    } else {
        console.error('30-day chart: Canvas element not found');
    }

    // 12-month revenue chart
    var monthly12 = parseChartData('monthlyRevenue12Data');
    console.log('12-month data keys:', Object.keys(monthly12).length);
    if (document.getElementById('chart12Month')) {
        if (Object.keys(monthly12).length > 0) {
            console.log('Creating 12-month chart...');
            var labelsM = Object.keys(monthly12);
            var dataM = labelsM.map(function(k) {
                return monthly12[k] || 0;
            });
            new Chart(document.getElementById('chart12Month').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsM,
                    datasets: [{
                        label: 'Doanh thu theo tháng (VNĐ)',
                        data: dataM,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderColor: 'rgb(255, 159, 64)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 1200
                    }
                }
            });
        } else {
            console.warn('12-month chart: No data available');
        }
    } else {
        console.error('12-month chart: Canvas element not found');
    }

    // Yearly revenue pie
    var yearly = parseChartData('yearlyRevenueData');
    console.log('Yearly data keys:', Object.keys(yearly).length);
    if (document.getElementById('chartYearly')) {
        if (Object.keys(yearly).length > 0) {
            console.log('Creating yearly chart...');
            var labelsY = Object.keys(yearly);
            var dataY = labelsY.map(function(k) {
                return yearly[k] || 0;
            });
            new Chart(document.getElementById('chartYearly').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labelsY,
                    datasets: [{
                        data: dataY,
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff', '#ff9f40']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1500
                    }
                }
            });
        } else {
            console.warn('Yearly chart: No data available');
        }
    } else {
        console.error('Yearly chart: Canvas element not found');
    }
});
