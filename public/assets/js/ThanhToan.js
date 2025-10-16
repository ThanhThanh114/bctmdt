/**
 * assets/js/ThanhToan.js
 * Thanh Toán - QR Payment Page (FUTA Demo)
 *
 * Tính năng:
 * - Countdown giữ chỗ (persist trong sessionStorage)
 * - Zoom modal cho QR + download
 * - Copy tổng tiền (Clipboard API + fallback)
 * - Áp dụng mã giảm giá demo (client-side)
 * - Kiểm tra thanh toán (simulate + polling / retry)
 * - Hủy giao dịch
 * - Modal management (accessible) + focus trap
 * - Toast queue (ép hiện thông báo gọn gàng)
 * - Event logging (console / hook)
 *
 * Lưu ý: tích hợp backend thực tế (API kiểm tra, webhook thanh toán) khi đưa vào production.
 */

/* =========================================================
   Utility helpers
   ========================================================= */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from((ctx || document).querySelectorAll(sel));

const clamp = (v, a, b) => Math.min(b, Math.max(a, v));

function safeParseInt(v, fallback = 0) {
  const n = parseInt(v, 10);
  return Number.isFinite(n) ? n : fallback;
}

function formatMoneyVND(raw) {
  // raw: "290.000" or numeric 290000 -> return "290.000đ"
  if (typeof raw === 'number') {
    return raw.toLocaleString('vi-VN') + 'đ';
  }
  const s = String(raw).replace(/\D+/g, '');
  if (!s) return '0đ';
  const n = parseInt(s, 10);
  return n.toLocaleString('vi-VN') + 'đ';
}

/* =========================================================
   Config & DOM refs
   ========================================================= */
const CONFIG = {
  storageKey: 'futa_hold_' + (location.pathname || 'paydemo'),
  countdownSelector: '#countdown',
  defaultHoldSeconds: window._serverHoldSeconds || 20 * 60,
  checkPaymentEndpoint: null, // set API endpoint if available
  maxPollAttempts: 8,
  pollIntervalMs: 2000
};

const refs = {
  countdown: $(CONFIG.countdownSelector),
  totalAmount: $('#totalAmount'),
  rightTotal: $('#rightTotal'),
  copyBtn: $('#copyAmountBtn'),
  downloadQrBtn: $('#downloadQrBtn'),
  qrImg: $('#qrImage'),
  zoomQrBtn: $('#zoomQrBtn'),
  qrZoomModal: $('#qrZoomModal'),
  qrZoomImg: $('#qrZoomImg'),
  pageOverlay: $('#pageOverlay'),
  helpBtn: $('#helpBtn'),
  helpModal: $('#helpModal'),
  modalCloses: $$('.modal-close'),
  checkPaymentBtn: $('#checkPaymentBtn'),
  cancelBtn: $('#cancelBtn'),
  applyCouponBtn: $('#applyCouponBtn'),
  couponInput: $('#couponInput'),
  couponMsg: $('#couponMsg'),
  openFaq: $('#openFaq'),
  contactSupportBtn: $('#contactSupportBtn'),
};

/* =========================================================
   Small event logger (for debug/analytics hook)
   ========================================================= */
function logEvent(name, data = {}) {
  // Minimal: console log. Hook here to send to analytics if needed.
  try {
    console.info('[FUTA:event]', name, data);
    // Example hook: sendBeacon / fetch to analytics endpoint
    // navigator.sendBeacon('/analytics', JSON.stringify({event: name, data}));
  } catch (e) { /* ignore */ }
}

/* =========================================================
   Toast queue system (single container)
   ========================================================= */
const Toast = (function () {
  const containerId = 'futa-toast-container';
  let container = null;

  function ensureContainer() {
    if (container) return container;
    container = document.createElement('div');
    container.id = containerId;
    Object.assign(container.style, {
      position: 'fixed',
      right: '18px',
      top: '18px',
      zIndex: 99999,
      display: 'flex',
      flexDirection: 'column',
      gap: '10px',
      maxWidth: '320px'
    });
    document.body.appendChild(container);
    return container;
  }

  function makeToastNode(message = '', type = 'info') {
    const t = document.createElement('div');
    t.className = `futa-toast futa-toast-${type}`;
    t.setAttribute('role', 'status');
    t.setAttribute('aria-live', 'polite');
    t.innerHTML = `<div style="padding:10px 12px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.08); font-weight:600;">
      ${escapeHtml(message)}
    </div>`;
    // basic style per type
    const inner = t.firstChild;
    if (type === 'success') inner.style.background = '#ecfdf5', inner.style.color = '#065f46';
    else if (type === 'danger') inner.style.background = '#fff1f2', inner.style.color = '#7f1d1d';
    else if (type === 'muted') inner.style.background = '#f8fafc', inner.style.color = '#0f172a';
    else inner.style.background = '#eef2ff', inner.style.color = '#0f172a';
    return t;
  }

  function show(message, type = 'info', duration = 3000) {
    const c = ensureContainer();
    const node = makeToastNode(message, type);
    c.appendChild(node);
    // show animation
    node.style.transform = 'translateY(-6px)';
    node.style.opacity = '0';
    requestAnimationFrame(() => {
      node.style.transition = 'all 260ms ease';
      node.style.transform = 'translateY(0)';
      node.style.opacity = '1';
    });
    setTimeout(() => {
      node.style.opacity = '0';
      node.style.transform = 'translateY(-10px)';
      setTimeout(() => node.remove(), 300);
    }, duration);
  }

  return { show };
})();

/* =========================================================
   Escape HTML util
   ========================================================= */
function escapeHtml(s) {
  return String(s || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

/* =========================================================
   Countdown timer with session persistence
   ========================================================= */
const HoldTimer = (function () {
  let endAt = null; // timestamp ms
  let intervalId = null;

  function loadOrInit() {
    try {
      const saved = sessionStorage.getItem(CONFIG.storageKey);
      if (saved) {
        const obj = JSON.parse(saved);
        if (obj && obj.endAt && obj.endAt > Date.now()) {
          endAt = obj.endAt;
        } else {
          endAt = Date.now() + CONFIG.defaultHoldSeconds * 1000;
          sessionStorage.setItem(CONFIG.storageKey, JSON.stringify({ endAt }));
        }
      } else {
        endAt = Date.now() + CONFIG.defaultHoldSeconds * 1000;
        sessionStorage.setItem(CONFIG.storageKey, JSON.stringify({ endAt }));
      }
    } catch (e) {
      endAt = Date.now() + CONFIG.defaultHoldSeconds * 1000;
    }
    logEvent('hold_timer_init', { endAt });
  }

  function remainingSeconds() {
    if (!endAt) return 0;
    return Math.max(0, Math.round((endAt - Date.now()) / 1000));
  }

  function render() {
    const el = refs.countdown;
    if (!el) return;
    const s = remainingSeconds();
    if (s <= 0) {
      el.textContent = 'Hết thời gian';
      el.classList.add('expired');
      onExpire();
    } else {
      const mm = String(Math.floor(s / 60)).padStart(2, '0');
      const ss = String(s % 60).padStart(2, '0');
      el.textContent = `${mm}:${ss}`;
    }
  }

  function onExpire() {
    // disable actions that require active hold
    if (refs.checkPaymentBtn) {
      refs.checkPaymentBtn.disabled = true;
      refs.checkPaymentBtn.textContent = 'Thời gian hết hạn';
    }
    Toast.show('⏰ Thời gian giữ chỗ đã hết. Vui lòng đặt lại vé.', 'danger', 5000);
    try { sessionStorage.removeItem(CONFIG.storageKey); } catch (e) { }
    logEvent('hold_timer_expired');
  }

  function start() {
    if (intervalId) return;
    render();
    intervalId = setInterval(() => {
      render();
      // persist occasionally
      try {
        if (Math.random() < 0.06) sessionStorage.setItem(CONFIG.storageKey, JSON.stringify({ endAt }));
      } catch (e) { /* ignore */ }
      if (remainingSeconds() <= 0) clearInterval(intervalId);
    }, 1000);
  }

  function clear() {
    try { sessionStorage.removeItem(CONFIG.storageKey); } catch (e) { }
    endAt = null;
    if (intervalId) clearInterval(intervalId);
  }

  loadOrInit();
  start();

  return { remainingSeconds, clear, endAtRef: () => endAt };
})();

/* =========================================================
   Modal & focus trap
   ========================================================= */
const ModalManager = (function () {
  const openModals = new Set();

  function open(modalEl) {
    if (!modalEl) return;
    modalEl.setAttribute('aria-hidden', 'false');
    modalEl.classList.add('active');
    if (refs.pageOverlay) {
      refs.pageOverlay.hidden = false;
      refs.pageOverlay.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
    openModals.add(modalEl);
    trapFocus(modalEl);
    logEvent('modal_open', { id: modalEl.id });
  }

  function close(modalEl) {
    if (!modalEl) return;
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.classList.remove('active');
    if (refs.pageOverlay) {
      refs.pageOverlay.hidden = true;
      refs.pageOverlay.classList.remove('active');
    }
    document.body.style.overflow = '';
    openModals.delete(modalEl);
    releaseFocus();
    logEvent('modal_close', { id: modalEl.id });
  }

  // basic focus trap (single modal)
  let lastFocused = null;
  function trapFocus(modalEl) {
    lastFocused = document.activeElement;
    const focusables = modalEl.querySelectorAll('a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])');
    if (focusables.length) focusables[0].focus();
    function handle(e) {
      if (e.key === 'Tab') {
        const nodes = Array.from(modalEl.querySelectorAll('a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'));
        if (!nodes.length) return;
        const idx = nodes.indexOf(document.activeElement);
        if (e.shiftKey) {
          if (idx === 0) { nodes[nodes.length - 1].focus(); e.preventDefault(); }
        } else {
          if (idx === nodes.length - 1) { nodes[0].focus(); e.preventDefault(); }
        }
      } else if (e.key === 'Escape') {
        close(modalEl);
      }
    }
    modalEl._trapHandler = handle;
    document.addEventListener('keydown', handle);
  }

  function releaseFocus() {
    // remove handlers from all modals
    openModals.forEach(mod => {
      if (mod._trapHandler) {
        document.removeEventListener('keydown', mod._trapHandler);
        delete mod._trapHandler;
      }
    });
    if (lastFocused && typeof lastFocused.focus === 'function') lastFocused.focus();
    lastFocused = null;
  }

  // overlay click closes open modal(s)
  if (refs.pageOverlay) {
    refs.pageOverlay.addEventListener('click', () => {
      Array.from(openModals).forEach(m => close(m));
    });
  }

  // attach close buttons
  refs.modalCloses.forEach(b => {
    b.addEventListener('click', (e) => {
      const modal = e.target.closest('.modal');
      close(modal);
    });
  });

  return { open, close };
})();

/* =========================================================
   QR Zoom & Download
   ========================================================= */
(function qrHandlers() {
  if (!refs.qrImg) return;
  // Zoom button
  if (refs.zoomQrBtn && refs.qrZoomModal) {
    refs.zoomQrBtn.addEventListener('click', () => {
      // set enlarged image src if modal image exists
      if (refs.qrZoomImg) refs.qrZoomImg.src = refs.qrImg.src;
      ModalManager.open(refs.qrZoomModal);
    });
  }

  // Download QR
  if (refs.downloadQrBtn) {
    refs.downloadQrBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const src = refs.qrImg?.src;
      if (!src) {
        Toast.show('Không tìm thấy mã QR để tải.', 'danger');
        return;
      }
      // Create link to download; handle cross-origin fallback by opening in new tab
      try {
        const link = document.createElement('a');
        link.href = src;
        link.download = 'qr.png';
        document.body.appendChild(link);
        link.click();
        link.remove();
        logEvent('qr_download', { src });
      } catch (err) {
        window.open(src, '_blank', 'noopener');
      }
    });
  }
})();

/* =========================================================
   Copy amount (Clipboard API with fallback)
   ========================================================= */
(function copyAmount() {
  if (!refs.copyBtn || !refs.totalAmount) return;
  refs.copyBtn.addEventListener('click', async () => {
    try {
      const raw = refs.totalAmount.textContent || refs.rightTotal.textContent || '';
      const currency = document.querySelector('.currency')?.textContent || 'đ';
      const text = raw.trim() + currency;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        await navigator.clipboard.writeText(text);
      } else {
        // fallback
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        ta.remove();
      }
      Toast.show('Đã sao chép: ' + text, 'success');
      logEvent('copy_amount', { amount: text });
    } catch (err) {
      Toast.show('Không thể sao chép vào clipboard.', 'danger');
    }
  });
})();

/* =========================================================
   Coupon apply (demo client-side rules)
   ========================================================= */
(function couponFeature() {
  if (!refs.applyCouponBtn || !refs.couponInput) return;
  refs.applyCouponBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const code = refs.couponInput.value.trim().toUpperCase();
    if (!code) {
      refs.couponMsg.textContent = 'Vui lòng nhập mã giảm giá.';
      refs.couponMsg.style.color = '#b91c1c';
      return;
    }
    // demo rules
    if (code === 'FUTA10') {
      refs.couponMsg.textContent = 'Áp dụng FUTA10: giảm 10%';
      refs.couponMsg.style.color = '#065f46';
      // update totals (demo)
      try {
        const raw = refs.totalAmount.textContent.replace(/\D/g, '');
        const n = parseInt(raw, 10) || 0;
        const newN = Math.round(n * 0.9);
        refs.totalAmount.textContent = newN.toLocaleString('vi-VN');
        refs.rightTotal.textContent = newN.toLocaleString('vi-VN') + 'đ';
      } catch (e) { /* ignore */ }
      Toast.show('Mã giảm giá áp dụng thành công.', 'success');
      logEvent('coupon_applied', { code });
    } else if (code === 'FREESHIP') {
      refs.couponMsg.textContent = 'Miễn phí thanh toán';
      refs.couponMsg.style.color = '#065f46';
      Toast.show('Đã áp dụng miễn phí thanh toán.', 'success');
    } else {
      refs.couponMsg.textContent = 'Mã không hợp lệ hoặc đã hết hạn.';
      refs.couponMsg.style.color = '#b91c1c';
      Toast.show('Mã giảm giá không hợp lệ.', 'danger');
      logEvent('coupon_invalid', { code });
    }
  });
})();

/* =========================================================
   Check payment (simulate / polling strategy)
   ========================================================= */
(function checkPayment() {
  if (!refs.checkPaymentBtn) return;

  async function pollCheckPayment(attempt = 0) {
    // For demo: random succeed after few attempts; in real app call backend endpoint
    logEvent('poll_attempt', { attempt });
    // If real endpoint provided, uncomment this block and return its result
    /*
    if (CONFIG.checkPaymentEndpoint) {
      try {
        const res = await fetch(CONFIG.checkPaymentEndpoint, { method: 'POST', body: JSON.stringify({orderId: orderId}) });
        const data = await res.json();
        return data; // expect { status: 'paid' | 'pending' | 'failed' }
      } catch(e) { return { status: 'error' }; }
    }
    */
    // Simulate network / payment result
    await new Promise(r => setTimeout(r, 800 + Math.random() * 600));
    const chance = Math.random();
    if (chance > 0.7) return { status: 'paid' };
    if (chance > 0.2) return { status: 'pending' };
    return { status: 'failed' };
  }

  refs.checkPaymentBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    if (refs.checkPaymentBtn.disabled) return;
    const originalLabel = refs.checkPaymentBtn.innerHTML;
    refs.checkPaymentBtn.disabled = true;
    refs.checkPaymentBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang kiểm tra...';
    Toast.show('Đang kiểm tra giao dịch...', 'info');

    let attempt = 0;
    let finalStatus = null;

    while (attempt < CONFIG.maxPollAttempts) {
      try {
        const res = await pollCheckPayment(attempt);
        attempt++;
        if (!res || !res.status) {
          // network-like error, retry
          logEvent('poll_result_unknown', { attempt, res });
          await new Promise(r => setTimeout(r, CONFIG.pollIntervalMs));
          continue;
        }
        if (res.status === 'paid') {
          finalStatus = 'paid';
          break;
        } else if (res.status === 'pending') {
          // keep polling
          Toast.show(`Giao dịch chưa thấy xác nhận (lần ${attempt}). Đang thử lại...`, 'muted', 1800);
          await new Promise(r => setTimeout(r, CONFIG.pollIntervalMs));
          continue;
        } else if (res.status === 'failed') {
          finalStatus = 'failed';
          break;
        } else {
          await new Promise(r => setTimeout(r, CONFIG.pollIntervalMs));
          continue;
        }
      } catch (err) {
        logEvent('poll_error', { attempt, err: String(err) });
        await new Promise(r => setTimeout(r, CONFIG.pollIntervalMs));
      }
    }

    // handle final status
    if (finalStatus === 'paid') {
      Toast.show('✅ Thanh toán đã được xác nhận. Vé sẽ được gửi tới email bạn.', 'success', 4000);
      logEvent('payment_confirmed', { attempts: attempt });
      // redirect to success page after short delay
      setTimeout(() => {
        try { window.location.href = '/checkout-success.php'; } catch (e) { /* ignore */ }
      }, 1200);
    } else {
      // failed or never confirmed
      Toast.show('❌ Chưa có xác nhận thanh toán. Vui lòng kiểm tra lại hoặc thử lại.', 'danger', 4500);
      refs.checkPaymentBtn.disabled = false;
      refs.checkPaymentBtn.innerHTML = originalLabel;
      logEvent('payment_unconfirmed', { attempts: attempt, finalStatus });
    }
  });

})();

/* =========================================================
   Cancel transaction
   ========================================================= */
(function cancelHandler() {
  if (!refs.cancelBtn) return;
  refs.cancelBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const ok = confirm('Bạn chắc chắn muốn HỦY giao dịch này?');
    if (!ok) return;
    // Clear hold state and navigate or notify
    HoldTimer.clear();
    Toast.show('Giao dịch đã hủy.', 'muted', 2200);
    logEvent('transaction_cancelled');
    setTimeout(() => {
      try { window.location.href = '/'; } catch (e) { /* ignore */ }
    }, 800);
  });
})();

/* =========================================================
   Help / FAQ modal & contact support
   ========================================================= */
(function helpFeature() {
  if (refs.openFaq) {
    refs.openFaq.addEventListener('click', (e) => {
      e.preventDefault();
      if (refs.helpModal) ModalManager.open(refs.helpModal);
    });
  }
  if (refs.contactSupportBtn) {
    refs.contactSupportBtn.addEventListener('click', () => {
      // example: open tel
      logEvent('contact_support');
      window.location.href = 'tel:19006067';
    });
  }
  if (refs.helpBtn && refs.helpModal) {
    refs.helpBtn.addEventListener('click', () => ModalManager.open(refs.helpModal));
  }
})();

/* =========================================================
   Accessibility: announce live regions if needed
   ========================================================= */
(function a11yAnnounce() {
  const liveId = 'futa-live-region';
  let live = document.getElementById(liveId);
  if (!live) {
    live = document.createElement('div');
    live.id = liveId;
    live.setAttribute('aria-live', 'polite');
    live.setAttribute('aria-atomic', 'true');
    live.style.position = 'absolute';
    live.style.left = '-9999px';
    live.style.width = '1px';
    live.style.height = '1px';
    document.body.appendChild(live);
  }

  // example usage: Toast.show also triggers visual, we can put short messages here if needed
  // function announce(msg) { live.textContent = msg; }
})();

/* =========================================================
   Init logs & bindings
   ========================================================= */
(function init() {
  // render initial totals with consistent formatting
  try {
    if (refs.totalAmount && refs.totalAmount.textContent.trim()) {
      refs.totalAmount.textContent = refs.totalAmount.textContent.trim().replace(/\D/g, '') ?
        (Number(refs.totalAmount.textContent.replace(/\D/g, '')).toLocaleString('vi-VN')) :
        refs.totalAmount.textContent.trim();
    }
  } catch (e) { /* ignore */ }

  // generic click logging for important controls
  ['copyAmountBtn', 'downloadQrBtn', 'checkPaymentBtn', 'cancelBtn', 'applyCouponBtn'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('click', () => logEvent('ui_click', { id }));
  });

  // safety: persist hold timer on unload
  window.addEventListener('beforeunload', () => {
    try {
      const endAt = HoldTimer.endAtRef ? HoldTimer.endAtRef() : null;
      if (endAt) sessionStorage.setItem(CONFIG.storageKey, JSON.stringify({ endAt }));
    } catch (e) { /* ignore */ }
  });

  logEvent('thanhtoan_js_init', { path: location.pathname });
})();

/* =========================================================
   End file
   ========================================================= */
