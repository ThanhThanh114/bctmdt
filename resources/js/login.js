function switchTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const formTitle = document.querySelector('.form-title');

    // Remove active class from all tabs
    tabButtons.forEach(btn => btn.classList.remove('active'));

    if (tab === 'login') {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        tabButtons[0].classList.add('active');
        formTitle.textContent = 'Đăng nhập tài khoản';
    } else {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        tabButtons[1].classList.add('active');
        formTitle.textContent = 'Tạo tài khoản';
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
