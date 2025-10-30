/* LichTrinh.js - Trip filter handling */

// No need for initial data as we load from server via AJAX
const state = {
    currentPage: 1,
    selectedRowIndex: null
};
//     { tuyen: "Hà Nội ⇌ Huế", loaiXe: "Giường", quangDuong: "690km", thoiGian: "13 giờ 30 phút", giaVe: "320,000đ" },
//     { tuyen: "Hà Nội ⇌ Hải Phòng", loaiXe: "Ghế ngồi", quangDuong: "120km", thoiGian: "2 giờ", giaVe: "80,000đ" },
//     { tuyen: "Hà Nội ⇌ Vinh", loaiXe: "Limousine", quangDuong: "310km", thoiGian: "6 giờ", giaVe: "200,000đ" },
//     { tuyen: "Hà Nội ⇌ Thanh Hóa", loaiXe: "Giường", quangDuong: "150km", thoiGian: "3 giờ", giaVe: "120,000đ" },
//     { tuyen: "Hà Nội ⇌ Ninh Bình", loaiXe: "Ghế ngồi", quangDuong: "90km", thoiGian: "2 giờ", giaVe: "70,000đ" },
//     { tuyen: "Hà Nội ⇌ Quảng Ninh", loaiXe: "Limousine", quangDuong: "180km", thoiGian: "3 giờ 30 phút", giaVe: "150,000đ" },

//     { tuyen: "Hải Phòng ⇌ Đà Nẵng", loaiXe: "Giường", quangDuong: "900km", thoiGian: "17 giờ", giaVe: "420,000đ" },
//     { tuyen: "Hải Phòng ⇌ Huế", loaiXe: "Giường", quangDuong: "860km", thoiGian: "16 giờ", giaVe: "400,000đ" },

//     { tuyen: "Đà Nẵng ⇌ TP.Hồ Chí Minh", loaiXe: "Limousine", quangDuong: "960km", thoiGian: "18 giờ", giaVe: "550,000đ" },
//     { tuyen: "Đà Nẵng ⇌ Nha Trang", loaiXe: "Giường", quangDuong: "520km", thoiGian: "9 giờ", giaVe: "300,000đ" },
//     { tuyen: "Đà Nẵng ⇌ Quy Nhơn", loaiXe: "Giường", quangDuong: "300km", thoiGian: "6 giờ", giaVe: "180,000đ" },
//     { tuyen: "Đà Nẵng ⇌ Huế", loaiXe: "Limousine", quangDuong: "100km", thoiGian: "2 giờ", giaVe: "120,000đ" },

//     { tuyen: "Huế ⇌ Nha Trang", loaiXe: "Giường", quangDuong: "450km", thoiGian: "9 giờ", giaVe: "280,000đ" },
//     { tuyen: "Huế ⇌ TP.Hồ Chí Minh", loaiXe: "Limousine", quangDuong: "950km", thoiGian: "18 giờ", giaVe: "520,000đ" },

//     { tuyen: "Quảng Ninh ⇌ Lạng Sơn", loaiXe: "Ghế ngồi", quangDuong: "150km", thoiGian: "3 giờ", giaVe: "90,000đ" },

//     { tuyen: "Vinh ⇌ Thanh Hóa", loaiXe: "Ghế ngồi", quangDuong: "160km", thoiGian: "3 giờ", giaVe: "110,000đ" },
//     { tuyen: "Vinh ⇌ Hà Tĩnh", loaiXe: "Limousine", quangDuong: "120km", thoiGian: "2 giờ 30 phút", giaVe: "140,000đ" },

//     { tuyen: "Quy Nhơn ⇌ Nha Trang", loaiXe: "Giường", quangDuong: "220km", thoiGian: "5 giờ", giaVe: "150,000đ" },
//     { tuyen: "Nha Trang ⇌ Phan Thiết", loaiXe: "Giường", quangDuong: "220km", thoiGian: "5 giờ", giaVe: "160,000đ" },

//     { tuyen: "Phan Thiết ⇌ TP.Hồ Chí Minh", loaiXe: "Ghế ngồi", quangDuong: "200km", thoiGian: "4 giờ", giaVe: "120,000đ" },
//     { tuyen: "Bảo Lộc ⇌ TP.Hồ Chí Minh", loaiXe: "Limousine", quangDuong: "200km", thoiGian: "5 giờ", giaVe: "220,000đ" },

//     { tuyen: "Đà Lạt ⇌ TP.Hồ Chí Minh", loaiXe: "Giường", quangDuong: "300km", thoiGian: "7 giờ", giaVe: "250,000đ" },
//     { tuyen: "Buôn Ma Thuột ⇌ TP.Hồ Chí Minh", loaiXe: "Limousine", quangDuong: "350km", thoiGian: "8 giờ", giaVe: "300,000đ" },

//     { tuyen: "Pleiku ⇌ TP.Hồ Chí Minh", loaiXe: "Giường", quangDuong: "520km", thoiGian: "11 giờ", giaVe: "380,000đ" },
//     { tuyen: "Kon Tum ⇌ Pleiku", loaiXe: "Ghế ngồi", quangDuong: "220km", thoiGian: "5 giờ", giaVe: "140,000đ" },

//     { tuyen: "Gia Lai ⇌ Đà Nẵng", loaiXe: "Limousine", quangDuong: "360km", thoiGian: "8 giờ", giaVe: "300,000đ" },

//     { tuyen: "Cần Thơ ⇌ TP.Hồ Chí Minh", loaiXe: "Giường", quangDuong: "170km", thoiGian: "4 giờ", giaVe: "140,000đ" },
//     { tuyen: "Cần Thơ ⇌ Rạch Giá", loaiXe: "Ghế ngồi", quangDuong: "220km", thoiGian: "5 giờ", giaVe: "120,000đ" },

//     { tuyen: "Rạch Giá ⇌ Hà Tiên", loaiXe: "Giường", quangDuong: "135km", thoiGian: "3 giờ", giaVe: "90,000đ" },
//     { tuyen: "Hà Tiên ⇌ TP.Hồ Chí Minh", loaiXe: "Limousine", quangDuong: "360km", thoiGian: "8 giờ", giaVe: "320,000đ" },

//     { tuyen: "Bến Tre ⇌ TP.Hồ Chí Minh", loaiXe: "Giường", quangDuong: "85km", thoiGian: "2 giờ 30 phút", giaVe: "90,000đ" },
//     { tuyen: "Trà Vinh ⇌ TP.Hồ Chí Minh", loaiXe: "Ghế ngồi", quangDuong: "200km", thoiGian: "5 giờ", giaVe: "130,000đ" },

//     { tuyen: "Vũng Tàu ⇌ TP.Hồ Chí Minh", loaiXe: "Ghế ngồi", quangDuong: "120km", thoiGian: "2 giờ 30 phút", giaVe: "100,000đ" },
//     { tuyen: "Long Khánh ⇌ TP.Hồ Chí Minh", loaiXe: "Limousine", quangDuong: "110km", thoiGian: "2 giờ", giaVe: "150,000đ" },

//     { tuyen: "Biên Hòa ⇌ Vũng Tàu", loaiXe: "Ghế ngồi", quangDuong: "70km", thoiGian: "1 giờ 30 phút", giaVe: "70,000đ" },
//     { tuyen: "Bạc Liêu ⇌ TP.Hồ Chí Minh", loaiXe: "Giường", quangDuong: "270km", thoiGian: "6 giờ", giaVe: "200,000đ" },

//     { tuyen: "Cà Mau ⇌ Bạc Liêu", loaiXe: "Giường", quangDuong: "140km", thoiGian: "3 giờ", giaVe: "90,000đ" },
//     { tuyen: "Sóc Trăng ⇌ TP.Hồ Chí Minh", loaiXe: "Ghế ngồi", quangDuong: "230km", thoiGian: "5 giờ 30 phút", giaVe: "140,000đ" },

//     { tuyen: "Hưng Yên ⇌ Hà Nội", loaiXe: "Ghế ngồi", quangDuong: "55km", thoiGian: "1 giờ 10 phút", giaVe: "60,000đ" },
//     { tuyen: "Phú Thọ ⇌ Hà Nội", loaiXe: "Ghế ngồi", quangDuong: "80km", thoiGian: "1 giờ 40 phút", giaVe: "70,000đ" },

//     { tuyen: "Tam Kỳ ⇌ Đà Nẵng", loaiXe: "Ghế ngồi", quangDuong: "100km", thoiGian: "2 giờ", giaVe: "90,000đ" },
//     { tuyen: "Quảng Ngãi ⇌ Quy Nhơn", loaiXe: "Giường", quangDuong: "210km", thoiGian: "4 giờ 30 phút", giaVe: "160,000đ" },

//     { tuyen: "Mỹ Tho ⇌ TP.Hồ Chí Minh", loaiXe: "Ghế ngồi", quangDuong: "70km", thoiGian: "1 giờ 45 phút", giaVe: "70,000đ" },
//     { tuyen: "Bình Thuận ⇌ Nha Trang", loaiXe: "Giường", quangDuong: "180km", thoiGian: "4 giờ", giaVe: "140,000đ" },

//     { tuyen: "Phú Yên ⇌ Nha Trang", loaiXe: "Limousine", quangDuong: "180km", thoiGian: "4 giờ", giaVe: "220,000đ" },
//     { tuyen: "Hòa Bình ⇌ Hà Nội", loaiXe: "Ghế ngồi", quangDuong: "80km", thoiGian: "1 giờ 50 phút", giaVe: "70,000đ" },

//     { tuyen: "Sơn La ⇌ Hà Nội", loaiXe: "Giường", quangDuong: "300km", thoiGian: "7 giờ", giaVe: "210,000đ" },
//     { tuyen: "Yên Bái ⇌ Hà Nội", loaiXe: "Ghế ngồi", quangDuong: "180km", thoiGian: "4 giờ", giaVe: "120,000đ" },

//     { tuyen: "Lào Cai ⇌ Hà Nội", loaiXe: "Limousine", quangDuong: "320km", thoiGian: "7 giờ 30 phút", giaVe: "270,000đ" },
//     { tuyen: "Điện Biên ⇌ Sơn La", loaiXe: "Ghế ngồi", quangDuong: "200km", thoiGian: "5 giờ 30 phút", giaVe: "190,000đ" }
// ];

/* ==================== STATE ==================== */
let state = {
    data: lichTrinhData.slice(),
    filtered: lichTrinhData.slice(),
    currentPage: 1,
    perPage: 8,
    sortKey: null,
    sortDir: null, // 'asc' or 'desc'
    selectedRowIndex: null
};

/* ==================== HELPERS ==================== */
function q(selector, root = document) { return root.querySelector(selector); }
function qa(selector, root = document) { return Array.from(root.querySelectorAll(selector)); }

/* format number helper (not heavily used) */
function numberFromString(s) {
    return parseInt((s || "").replace(/[^\d]/g, '')) || 0;
}

/* ==================== RENDER TABLE ==================== */
function renderTable() {
    const tbody = q('#scheduleTable');
    tbody.innerHTML = '';

    const start = (state.currentPage - 1) * state.perPage;
    const end = start + state.perPage;
    const pageItems = state.filtered.slice(start, end);

    if (pageItems.length === 0) {
        tbody.innerHTML = `<tr class="fade-in"><td class="table-empty" colspan="6">Không tìm thấy chuyến xe nào</td></tr>`;
    } else {
        pageItems.forEach((item, idx) => {
            const globalIndex = start + idx;
            const tr = document.createElement('tr');
            tr.classList.add('fade-in');
            if (state.selectedRowIndex === globalIndex) tr.classList.add('active');

            tr.innerHTML = `
                <td class="col-tuyen">${escapeHtml(item.tuyen)}</td>
                <td class="col-loai">${escapeHtml(item.loaiXe)}</td>
                <td class="col-q">${escapeHtml(item.quangDuong)}</td>
                <td class="col-time">${escapeHtml(item.thoiGian)}</td>
                <td class="col-g">${escapeHtml(item.giaVe)}</td>
                <td><button class="btn-find" data-index="${globalIndex}">Tìm tuyến xe</button></td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Attach click events for "Tìm tuyến xe"
    qa('.btn-find', tbody).forEach(btn => {
        btn.addEventListener('click', (e) => {
            const idx = parseInt(e.currentTarget.dataset.index, 10);
            handleFindRouteClick(idx);
        });
    });

    renderPagination();
}

/* escape to avoid HTML injection from dataset (defensive) */
function escapeHtml(text) {
    if (text == null) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/* ==================== PAGINATION ==================== */
function renderPagination() {
    const wrapper = q('#paginationWrapper');
    wrapper.innerHTML = '';

    const total = state.filtered.length;
    const totalPages = Math.max(1, Math.ceil(total / state.perPage));
    const current = state.currentPage;

    const pagination = document.createElement('div');
    pagination.className = 'pagination';

    // Prev
    const prevBtn = document.createElement('button');
    prevBtn.className = 'page-btn';
    prevBtn.innerHTML = '<i class="fa fa-chevron-left"></i>';
    prevBtn.disabled = current === 1;
    prevBtn.addEventListener('click', () => {
        state.currentPage = Math.max(1, state.currentPage - 1);
        renderTable();
    });
    pagination.appendChild(prevBtn);

    // pages (show window)
    const maxButtons = 7;
    let start = Math.max(1, current - Math.floor(maxButtons / 2));
    let end = Math.min(totalPages, start + maxButtons - 1);
    if (end - start < maxButtons - 1) {
        start = Math.max(1, end - maxButtons + 1);
    }

    for (let p = start; p <= end; p++) {
        const btn = document.createElement('button');
        btn.className = 'page-btn' + (p === current ? ' active' : '');
        btn.textContent = p;
        btn.addEventListener('click', () => {
            state.currentPage = p;
            renderTable();
        });
        pagination.appendChild(btn);
    }

    // Next
    const nextBtn = document.createElement('button');
    nextBtn.className = 'page-btn';
    nextBtn.innerHTML = '<i class="fa fa-chevron-right"></i>';
    nextBtn.disabled = current === totalPages;
    nextBtn.addEventListener('click', () => {
        state.currentPage = Math.min(totalPages, state.currentPage + 1);
        renderTable();
    });
    pagination.appendChild(nextBtn);

    // Meta
    const meta = document.createElement('div');
    meta.className = 'page-meta';
    meta.textContent = `  ${total} kết quả • Trang ${current}/${totalPages}`;
    wrapper.appendChild(pagination);
    wrapper.appendChild(meta);
}

/* ==================== FILTER & SEARCH ==================== */
function applyFilters() {
    const from = (q('#fromInput').value || '').trim().toLowerCase();
    const to = (q('#toInput').value || '').trim().toLowerCase();
    const loaiXe = q('#filterLoaiXe').value || 'all';

    state.filtered = state.data.filter(item => {
        const text = item.tuyen.toLowerCase();
        const matchesFrom = from === '' || text.includes(from);
        const matchesTo = to === '' || text.includes(to);
        const matchesLoai = loaiXe === 'all' || (item.loaiXe && item.loaiXe.toLowerCase() === loaiXe.toLowerCase());
        return matchesFrom && matchesTo && matchesLoai;
    });

    // Reset to first page if current page out of range
    state.currentPage = 1;

    // Apply sorting if any
    if (state.sortKey) applySort(state.sortKey, state.sortDir, false);

    renderTable();
}

/* ==================== SWAP INPUTS ==================== */
function attachSwap() {
    q('#swapBtn').addEventListener('click', () => {
        const f = q('#fromInput'), t = q('#toInput');
        const tmp = f.value;
        f.value = t.value;
        t.value = tmp;
        applyFilters();
        // small animation
        q('#swapBtn').classList.add('rotate');
        setTimeout(() => q('#swapBtn').classList.remove('rotate'), 300);
    });
}

/* ==================== HANDLER "Tìm tuyến xe" ON ROW ==================== */
function handleFindRouteClick(globalIndex) {
    // Deselect old
    state.selectedRowIndex = globalIndex;
    renderTable();

    const item = state.data[globalIndex];
    if (!item) return;
    // Simple highlight then scroll into view
    const rowPosition = globalIndex - (state.currentPage - 1) * state.perPage;
    const tbody = q('#scheduleTable');
    const row = tbody.children[rowPosition];
    if (row) {
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        row.classList.add('active');
        // small UI dialog (browser-friendly)
        setTimeout(() => {
            alert(`Bạn chọn tuyến:\n${item.tuyen}\nLoại: ${item.loaiXe}\nThời gian: ${item.thoiGian}\nGiá: ${item.giaVe}`);
        }, 250);
    }
}

/* ==================== SORTING ==================== */
function applySort(key, dir, toggleUI = true) {
    state.sortKey = key;
    state.sortDir = dir;

    state.filtered.sort((a, b) => {
        let va = (a[key] || '').toString().toLowerCase();
        let vb = (b[key] || '').toString().toLowerCase();

        // If sorting quangDuong or giaVe try numeric extraction
        if (key === 'quangDuong' || key === 'giaVe') {
            va = numberFromString(va);
            vb = numberFromString(vb);
            return dir === 'asc' ? va - vb : vb - va;
        }

        if (va < vb) return dir === 'asc' ? -1 : 1;
        if (va > vb) return dir === 'asc' ? 1 : -1;
        return 0;
    });

    if (toggleUI) {
        // update header icons
        qa('.sortable').forEach(el => {
            el.classList.remove('asc', 'desc');
            const k = el.dataset.key;
            if (k === key) el.classList.add(dir === 'asc' ? 'asc' : 'desc');
        });
    }

    // Reset page
    state.currentPage = 1;
    renderTable();
}

/* ==================== INIT SORT UI ==================== */
function attachSortHeaders() {
    qa('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const key = th.dataset.key;
            let dir = 'asc';
            if (state.sortKey === key) {
                dir = state.sortDir === 'asc' ? 'desc' : 'asc';
            }
            applySort(key, dir, true);
        });
    });
}

/* ==================== PER PAGE / FILTER EVENTS ==================== */
function attachControls() {
    q('#filterLoaiXe').addEventListener('change', () => {
        applyFilters();
    });

    q('#perPage').addEventListener('change', (e) => {
        state.perPage = parseInt(e.target.value, 10) || 8;
        state.currentPage = 1;
        renderTable();
    });

    // Enter press on inputs triggers find
    [q('#fromInput'), q('#toInput')].forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        input.addEventListener('input', debounce(() => {
            // update live filter but don't overdo it
            // applyFilters();
        }, 450));
    });
}

/* ==================== FIND BTN main ==================== */
function attachFindButton() {
    q('#findBtn').addEventListener('click', () => {
        applyFilters();
        // small feedback
        q('#findBtn').animate([{ transform: 'translateY(0)' }, { transform: 'translateY(-3px)' }, { transform: 'translateY(0)' }], { duration: 260 });
    });
}

/* ==================== UTILS: debounce ==================== */
function debounce(fn, delay = 250) {
    let t;
    return function (...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), delay);
    };
}

/* ==================== INIT ==================== */
function init() {
    // initial filtered
    state.filtered = state.data.slice();

    // attach behaviors
    attachSwap();
    attachFindButton();
    attachControls();
    attachSortHeaders();

    // initial render
    renderTable();
}

// Filter functions for trips page
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

    fetchAndReplace(url.toString());
}

// Helper: fetch the URL and replace the trips-content HTML, then hide query params
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
            if (newPagination && currentPagination) currentPagination.innerHTML = newPagination.innerHTML;
            // Scroll to top of results
            window.scrollTo({ top: current.getBoundingClientRect().top + window.scrollY - 20, behavior: 'smooth' });
            // Hide query params from the address bar but keep the state
            history.replaceState({}, document.title, '/lichtrinh');
        } else {
            // If we couldn't find the fragment, fallback to full navigation
            window.location.href = url;
        }
    } catch (e) {
        console.warn('AJAX fetch failed, falling back to full navigation', e);
        window.location.href = url;
    }
}

/* ==================== START */
document.addEventListener('DOMContentLoaded', init);
