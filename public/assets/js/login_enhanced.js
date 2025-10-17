/* =================================================================
   ENHANCED LOGIN/REGISTER JAVASCRIPT
   Features: Toggle Password, Password Strength Meter, Live Validation
   ================================================================= */

/**
 * Toggle Password Visibility
 * @param {string} inputId - ID của input password
 * @param {HTMLElement} button - Button toggle
 */
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/**
 * Check Password Strength
 * @param {string} password - Mật khẩu cần kiểm tra
 */
function checkPasswordStrength(password) {
    const strengthFill = document.getElementById('passwordStrengthFill');
    const strengthText = document.getElementById('passwordStrengthText');

    if (!strengthFill || !strengthText) return;

    // Reset classes
    strengthFill.className = 'password-strength-fill';
    strengthText.className = 'password-strength-text';

    if (password.length === 0) {
        strengthText.textContent = 'Nhập mật khẩu để kiểm tra độ mạnh';
        return;
    }

    // Calculate strength score
    let score = 0;
    const checks = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };

    // Update requirement checkmarks
    updateRequirement('req-length', checks.length);
    updateRequirement('req-uppercase', checks.uppercase);
    updateRequirement('req-lowercase', checks.lowercase);
    updateRequirement('req-number', checks.number);
    updateRequirement('req-special', checks.special);

    // Count passed checks
    Object.values(checks).forEach(passed => {
        if (passed) score++;
    });

    // Add bonus for length
    if (password.length >= 12) score++;
    if (password.length >= 16) score++;

    // Determine strength level
    if (score <= 2) {
        strengthFill.classList.add('weak');
        strengthText.classList.add('weak');
        strengthText.textContent = '❌ Mật khẩu yếu - Không an toàn';
    } else if (score <= 4) {
        strengthFill.classList.add('medium');
        strengthText.classList.add('medium');
        strengthText.textContent = '⚠️ Mật khẩu trung bình - Nên cải thiện';
    } else {
        strengthFill.classList.add('strong');
        strengthText.classList.add('strong');
        strengthText.textContent = '✅ Mật khẩu mạnh - Rất an toàn';
    }
}

/**
 * Update Password Requirement Check
 * @param {string} elementId - ID của requirement element
 * @param {boolean} isValid - Có hợp lệ không
 */
function updateRequirement(elementId, isValid) {
    const element = document.getElementById(elementId);
    if (!element) return;

    if (isValid) {
        element.classList.add('valid');
    } else {
        element.classList.remove('valid');
    }
}

/**
 * Validate Email Format
 * @param {string} email - Email cần kiểm tra
 * @returns {boolean}
 */
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Validate Phone Number
 * @param {string} phone - Số điện thoại cần kiểm tra
 * @returns {boolean}
 */
function validatePhone(phone) {
    const regex = /^[0-9]{10,11}$/;
    return regex.test(phone);
}

/**
 * Validate Username
 * @param {string} username - Username cần kiểm tra
 * @returns {boolean}
 */
function validateUsername(username) {
    const regex = /^[a-zA-Z0-9_]+$/;
    return regex.test(username) && username.length >= 3;
}

/**
 * Show Input Validation Feedback
 * @param {HTMLElement} input - Input element
 * @param {boolean} isValid - Có hợp lệ không
 * @param {string} message - Thông báo (optional)
 */
function showValidationFeedback(input, isValid, message = '') {
    // Remove existing feedback
    const wrapper = input.closest('.input-wrapper');
    const existingFeedback = wrapper.querySelector('.invalid-feedback, .valid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }

    // Update input classes
    input.classList.remove('is-valid', 'is-invalid');

    if (isValid === null) return; // No validation

    if (isValid) {
        input.classList.add('is-valid');
        if (message) {
            const feedback = document.createElement('div');
            feedback.className = 'valid-feedback';
            feedback.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            wrapper.appendChild(feedback);
        }
    } else {
        input.classList.add('is-invalid');
        if (message) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            wrapper.appendChild(feedback);
        }
    }
}

// ============ DOCUMENT READY ============
document.addEventListener('DOMContentLoaded', function () {

    // === LIVE VALIDATION FOR REGISTER FORM ===
    const regUsername = document.getElementById('regUsername');
    const regEmail = document.getElementById('regEmail');
    const regPhone = document.getElementById('regPhone');
    const regPassword = document.getElementById('regPassword');
    const regPasswordConfirm = document.getElementById('regPasswordConfirm');

    if (regUsername) {
        regUsername.addEventListener('blur', function () {
            const username = this.value.trim();
            if (username.length === 0) {
                showValidationFeedback(this, null);
            } else if (!validateUsername(username)) {
                showValidationFeedback(this, false, 'Tên đăng nhập chỉ gồm chữ, số, gạch dưới (tối thiểu 3 ký tự)');
            } else {
                showValidationFeedback(this, true, 'Tên đăng nhập hợp lệ');
            }
        });

        regUsername.addEventListener('input', function () {
            // Remove invalid characters while typing
            this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
        });
    }

    if (regEmail) {
        regEmail.addEventListener('blur', function () {
            const email = this.value.trim();
            if (email.length === 0) {
                showValidationFeedback(this, null);
            } else if (!validateEmail(email)) {
                showValidationFeedback(this, false, 'Email không đúng định dạng');
            } else {
                showValidationFeedback(this, true, 'Email hợp lệ');
            }
        });
    }

    if (regPhone) {
        regPhone.addEventListener('blur', function () {
            const phone = this.value.trim();
            if (phone.length === 0) {
                showValidationFeedback(this, null);
            } else if (!validatePhone(phone)) {
                showValidationFeedback(this, false, 'Số điện thoại phải là 10-11 chữ số');
            } else {
                showValidationFeedback(this, true, 'Số điện thoại hợp lệ');
            }
        });

        regPhone.addEventListener('input', function () {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    if (regPassword && regPasswordConfirm) {
        regPasswordConfirm.addEventListener('blur', function () {
            const password = regPassword.value;
            const confirm = this.value;

            if (confirm.length === 0) {
                showValidationFeedback(this, null);
            } else if (password !== confirm) {
                showValidationFeedback(this, false, 'Mật khẩu xác nhận không khớp');
            } else {
                showValidationFeedback(this, true, 'Mật khẩu khớp');
            }
        });
    }

    // === LOGIN FORM VALIDATION ===
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const identifier = this.querySelector('input[name="identifier"]').value.trim();
            const password = this.querySelector('input[name="password"]').value;

            if (identifier.length === 0) {
                e.preventDefault();
                alert('⚠️ Vui lòng nhập tên đăng nhập, email hoặc số điện thoại');
                return false;
            }

            if (password.length === 0) {
                e.preventDefault();
                alert('⚠️ Vui lòng nhập mật khẩu');
                return false;
            }
        });
    }

    // === REGISTER FORM VALIDATION ===
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const username = this.querySelector('input[name="username"]').value.trim();
            const fullname = this.querySelector('input[name="fullname"]').value.trim();
            const phone = this.querySelector('input[name="phone"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const password = this.querySelector('input[name="password"]').value;
            const passwordConfirm = this.querySelector('input[name="password_confirmation"]').value;

            let errors = [];

            // Validate all fields
            if (!validateUsername(username)) {
                errors.push('❌ Tên đăng nhập không hợp lệ (chỉ gồm chữ, số, gạch dưới, tối thiểu 3 ký tự)');
            }

            if (fullname.length < 3) {
                errors.push('❌ Họ và tên phải có ít nhất 3 ký tự');
            }

            if (!validatePhone(phone)) {
                errors.push('❌ Số điện thoại phải là 10-11 chữ số');
            }

            if (!validateEmail(email)) {
                errors.push('❌ Email không đúng định dạng');
            }

            if (password.length < 6) {
                errors.push('❌ Mật khẩu phải có ít nhất 6 ký tự');
            }

            if (password !== passwordConfirm) {
                errors.push('❌ Mật khẩu xác nhận không khớp');
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert('VUI LÒNG KIỂM TRA LẠI:\n\n' + errors.join('\n'));
                return false;
            }

            // Add loading state to submit button
            const submitBtn = this.querySelector('.submit-btn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    }

    // === AUTO-HIDE MESSAGES ===
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease';
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });
});
