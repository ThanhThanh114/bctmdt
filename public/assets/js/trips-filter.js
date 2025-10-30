/* trips-filter.js - Trip filter handling */

// Helper function to build URL with filter params
function buildFilterURL(params = {}) {
    const url = new URL(window.location);

    // Update each provided parameter
    Object.entries(params).forEach(([key, value]) => {
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
    });

    // Always reset to page 1 when applying filters
    url.searchParams.set('page', '1');

    return url.toString();
}

// Filter functions
function changeSort(sortValue) {
    fetchAndReplace(buildFilterURL({ sort: sortValue }));
}

function changeBusType(busTypeValue) {
    fetchAndReplace(buildFilterURL({ bus_type: busTypeValue }));
}

function changeBusCompany(busCompanyValue) {
    fetchAndReplace(buildFilterURL({ bus_company: busCompanyValue }));
}

function changeDepartureDate(dateValue) {
    fetchAndReplace(buildFilterURL({ departure_date: dateValue }));
}

function changeArrivalDate(dateValue) {
    fetchAndReplace(buildFilterURL({ arrival_date: dateValue }));
}

function changeDriver(driverValue) {
    fetchAndReplace(buildFilterURL({ driver: driverValue }));
}

function changePriceRange(priceRangeValue) {
    fetchAndReplace(buildFilterURL({ price_range: priceRangeValue }));
}

function resetFilters() {
    const url = new URL(window.location);

    // Giữ lại các tham số tìm kiếm cơ bản
    const searchParams = {
        start: url.searchParams.get('start'),
        end: url.searchParams.get('end'),
        date: url.searchParams.get('date')
    };

    // Xóa tất cả params hiện tại
    url.search = '';

    // Thêm lại các tham số tìm kiếm cơ bản nếu có
    Object.entries(searchParams).forEach(([key, value]) => {
        if (value) {
            url.searchParams.set(key, value);
        }
    });

    // Đặt lại các giá trị mặc định của bộ lọc
    url.searchParams.set('sort', 'date_asc');
    url.searchParams.set('bus_type', 'all');
    url.searchParams.set('bus_company', 'all');
    url.searchParams.set('driver', 'all');
    url.searchParams.set('price_range', 'all');
    url.searchParams.set('page', '1');

    fetchAndReplace(url.toString());
}

// Helper: fetch URL và cập nhật nội dung trips
async function fetchAndReplace(url) {
    try {
        // Add AJAX header
        const resp = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!resp.ok) {
            throw new Error(`HTTP error! status: ${resp.status}`);
        }

        const text = await resp.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(text, 'text/html');

        // Cập nhật nội dung chính
        const newContent = doc.querySelector('.trips-content');
        if (newContent) {
            const current = document.querySelector('.trips-content');
            current.innerHTML = newContent.innerHTML;

            // Cập nhật phân trang nếu có
            const newPagination = doc.querySelector('#paginationWrapper');
            const currentPagination = document.querySelector('#paginationWrapper');
            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            }

            // Cuộn lên đầu kết quả
            window.scrollTo({
                top: current.getBoundingClientRect().top + window.scrollY - 20,
                behavior: 'smooth'
            });

            // Cập nhật URL với params mới
            history.replaceState({}, document.title, url);
        } else {
            // Fallback khi không tìm thấy nội dung
            window.location.href = url;
        }
    } catch (e) {
        console.error('AJAX fetch failed:', e);
        // Fallback to full page load
        window.location.href = url;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sự kiện click cho phân trang
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#paginationWrapper a, .pagination a, a.page-btn');
        if (link && link.tagName === 'A') {
            const href = link.getAttribute('href');
            if (href && href.indexOf('/lichtrinh') !== -1) {
                e.preventDefault();
                fetchAndReplace(href);
            }
        }
    });
});
