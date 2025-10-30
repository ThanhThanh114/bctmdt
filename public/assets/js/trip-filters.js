/* LichTrinh.js - Trip filter handling */

function changeSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi sắp xếp
    fetchAndReplace(url.toString());
}

function changeBusType(busTypeValue) {
    const url = new URL(window.location);
    url.searchParams.set('bus_type', busTypeValue);
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi loại xe
    fetchAndReplace(url.toString());
}

function changeBusCompany(busCompanyValue) {
    const url = new URL(window.location);
    url.searchParams.set('bus_company', busCompanyValue);
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi nhà xe
    fetchAndReplace(url.toString());
}

function changeDepartureDate(dateValue) {
    const url = new URL(window.location);
    if (dateValue) {
        url.searchParams.set('departure_date', dateValue);
    } else {
        url.searchParams.delete('departure_date');
    }
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi ngày đi
    fetchAndReplace(url.toString());
}

function changeArrivalDate(dateValue) {
    const url = new URL(window.location);
    if (dateValue) {
        url.searchParams.set('arrival_date', dateValue);
    } else {
        url.searchParams.delete('arrival_date');
    }
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi ngày đến
    fetchAndReplace(url.toString());
}

function changeDriver(driverValue) {
    const url = new URL(window.location);
    url.searchParams.set('driver', driverValue);
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi tài xế
    fetchAndReplace(url.toString());
}

function changePriceRange(priceRangeValue) {
    const url = new URL(window.location);
    url.searchParams.set('price_range', priceRangeValue);
    url.searchParams.set('page', '1'); // Reset về trang đầu khi thay đổi khoảng giá
    fetchAndReplace(url.toString());
}

function resetFilters() {
    const url = new URL(window.location);
    // Giữ lại start, end, date từ search form
    const start = url.searchParams.get('start');
    const end = url.searchParams.get('end');
    const date = url.searchParams.get('date');

    // Clear tất cả params
    url.search = '';

    // Thêm lại search params
    if (start) url.searchParams.set('start', start);
    if (end) url.searchParams.set('end', end);
    if (date) url.searchParams.set('date', date);

    // Đặt lại các giá trị mặc định
    url.searchParams.set('sort', 'date_asc');
    url.searchParams.set('bus_type', 'all');
    url.searchParams.set('bus_company', 'all');
    url.searchParams.set('driver', 'all');
    url.searchParams.set('price_range', 'all');
    url.searchParams.set('page', '1');

    fetchAndReplace(url.toString());
}

// Helper: fetch the URL and replace the trips-content HTML
async function fetchAndReplace(url) {
    try {
        const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!resp.ok) {
            window.location.href = url; // fallback to full load on error
            return;
        }
        const text = await resp.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(text, 'text/html');
        const newContent = doc.querySelector('.trips-content');
        if (newContent) {
            const current = document.querySelector('.trips-content');
            current.innerHTML = newContent.innerHTML;
            // update pagination area too (in case it's outside .trips-content)
            const newPagination = doc.querySelector('#paginationWrapper');
            const currentPagination = document.querySelector('#paginationWrapper');
            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            }
            // Scroll to top of results
            window.scrollTo({ top: current.getBoundingClientRect().top + window.scrollY - 20, behavior: 'smooth' });

            // Update URL and params
            const cleanUrl = new URL(url);
            // Chỉ xóa các param mặc định
            const defaultParams = {
                'sort': 'date_asc',
                'bus_type': 'all',
                'bus_company': 'all',
                'driver': 'all',
                'price_range': 'all',
                'page': '1'
            };

            let hasNonDefaultParams = false;
            cleanUrl.searchParams.forEach((value, key) => {
                if (!defaultParams[key] || defaultParams[key] !== value) {
                    hasNonDefaultParams = true;
                }
            });

            // Nếu tất cả params đều là mặc định, xóa params khỏi URL
            if (!hasNonDefaultParams) {
                history.replaceState({}, document.title, cleanUrl.pathname);
            } else {
                history.replaceState({}, document.title, url);
            }
        } else {
            // If we couldn't find the fragment, fallback to full navigation
            window.location.href = url;
        }
    } catch (e) {
        console.warn('AJAX fetch failed, falling back to full navigation', e);
        window.location.href = url;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Remove / hide default query parameters from the address bar
    // without reloading the page
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    // Hide show_all=1 query param
    if (params.has('show_all')) {
        history.replaceState({}, document.title, url.pathname);
    }
});
