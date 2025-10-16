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
        message.style.opacity = '0';
        setTimeout(() => {
            message.remove();
        }, 300);
    }, 5000);
});
