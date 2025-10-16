function switchTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const forgotForm = document.getElementById('forgotForm');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const formTitle = document.querySelector('.form-title');

    // Remove active class from all tabs
    tabButtons.forEach(btn => btn.classList.remove('active'));

    if (tab === 'login') {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        forgotForm.classList.add('hidden');
        tabButtons[0].classList.add('active');
        formTitle.textContent = 'Đăng nhập tài khoản';
    } else if (tab === 'register') {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        forgotForm.classList.add('hidden');
        tabButtons[1].classList.add('active');
        formTitle.textContent = 'Tạo tài khoản';
    } else if (tab === 'forgot') {
        loginForm.classList.add('hidden');
        registerForm.classList.add('hidden');
        forgotForm.classList.remove('hidden');
        tabButtons[2].classList.add('active');
        formTitle.textContent = 'Quên mật khẩu';
    }
}

// Auto-hide messages after 5 seconds
const messages = document.querySelectorAll('.message');
messages.forEach(message => {
    setTimeout(() => {
        message.style.transition = 'opacity 0.3s';
        message.style.opacity = '0';
        setTimeout(() => {
            message.remove();
        }, 300);
    }, 5000);
});

// Form validation
document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const username = this.querySelector('input[name="username"]').value;
            const fullname = this.querySelector('input[name="fullname"]').value;
            const phone = this.querySelector('input[name="phone"]').value;
            const email = this.querySelector('input[name="email"]').value;
            const password = this.querySelector('input[name="password"]').value;
            const passwordConfirm = this.querySelector('input[name="password_confirmation"]').value;

            let errors = [];

            // Username validation
            if (username.length < 3) {
                errors.push('Tên đăng nhập phải có ít nhất 3 ký tự');
            }
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                errors.push('Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới');
            }

            // Fullname validation
            if (fullname.length < 3) {
                errors.push('Họ và tên phải có ít nhất 3 ký tự');
            }

            // Phone validation
            if (!/^[0-9]{10,11}$/.test(phone)) {
                errors.push('Số điện thoại phải là 10-11 chữ số');
            }

            // Email validation
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors.push('Email không đúng định dạng');
            }

            // Password validation
            if (password.length < 6) {
                errors.push('Mật khẩu phải có ít nhất 6 ký tự');
            }

            // Password confirmation
            if (password !== passwordConfirm) {
                errors.push('Mật khẩu xác nhận không khớp');
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert('Vui lòng kiểm tra lại:\n\n' + errors.join('\n'));
                return false;
            }
        });
    }

    // Login form validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const identifier = this.querySelector('input[name="identifier"]').value;
            const password = this.querySelector('input[name="password"]').value;

            if (!identifier || !password) {
                e.preventDefault();
                alert('Vui lòng nhập đầy đủ thông tin đăng nhập');
                return false;
            }
        });
    }
});
