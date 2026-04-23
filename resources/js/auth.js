const validationRules = {
    name: (val) => val.trim() === "" ? 'Vui lòng nhập họ và tên của bạn.' : null,
    email: (val) => {
        if (val.trim() === "") return 'Vui lòng nhập địa chỉ Email.';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim())) return 'Email không đúng định dạng.';
        return null;
    },
    phone_number: (val) => {
        if (val.trim() !== "") {
            if (!/^(0|84)(3|5|7|8|9)([0-9]{8})$/.test(val.trim())) return 'Số điện thoại không hợp lệ.';
        }
        return null;
    },
    password: (val) => val.length < 8 ? 'Mật khẩu phải chứa ít nhất 8 ký tự.' : null,
    password_confirmation: (val, form) => {
        const pass = form.find('input[name="password"]').val();
        return val !== pass ? 'Mật khẩu xác nhận không trùng khớp.' : null;
    }
};

export function initAuthValidation() {
    const $loginForm = $('#login-form');
    const $registerForm = $('#register-form');

    if ($loginForm.length) setupRealtimeValidation($loginForm, 'login');
    if ($registerForm.length) setupRealtimeValidation($registerForm, 'register');

    handlePasswordVisibility();
}

function setupRealtimeValidation($form, type) {
    const $inputs = $form.find('input:not([type="checkbox"])');

    $inputs.on('blur', function() {
        validateField($(this), $form);
    });

    $inputs.on('input', function() {
        const $input = $(this);
        if ($input.val().trim() !== "") {
            clearFieldError($input);
        }
    });

    $form.on('submit', function(e) {
        let hasError = false;
        $inputs.each(function() {
            if (validateField($(this), $form)) hasError = true;
        });

        if (hasError) {
            e.preventDefault();
            scrollToFirstError();
        }
    });
}

function validateField($input, $form) {
    const name = $input.attr('name');
    const val = $input.val();
    const rule = validationRules[name];

    if (!rule) return false;

    const errorMessage = rule(val, $form);
    clearFieldError($input);

    if (errorMessage) {
        showError($input, errorMessage);
        return true;
    }
    return false;
}

function showError($input, message) {
    const $container = $input.closest('.field-inner');
    $container.addClass('has-error');

    const $errorSpan = $(`<span class="error-message">${message}</span>`);
    $container.after($errorSpan);
    $errorSpan.hide().fadeIn(300);
}

function clearFieldError($input) {
    const $container = $input.closest('.field-inner');
    $container.removeClass('has-error');
    $container.next('.error-message').remove();
}

function scrollToFirstError() {
    const $firstError = $('.has-error').first();
    if ($firstError.length) {
        $('html, body').animate({
            scrollTop: $firstError.offset().top - 150
        }, 500);
    }
}

function handlePasswordVisibility() {
    const $toggleIcon = $('#toggle-password-icon');
    const $passwordInput = $('#password-field');

    if ($toggleIcon.length === 0) return;

    $toggleIcon.on('click', function() {
        const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
        $passwordInput.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
        $(this).css('color', type === 'text' ? 'var(--main-color)' : '');
    });
}
