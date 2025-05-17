$(document).ready(function () {
  // Show/hide forms
  $('#show-forgot').click(function (e) {
    e.preventDefault();
    $('form').hide();
    $('#forgot-form').show();
  });

  $('#back-to-login-from-forgot').click(function (e) {
    e.preventDefault();
    $('form').hide();
    $('#login-form').show();
    grecaptcha.reset();
  });

  // Clear error spans
  function clearErrors(form) {
    form.find('.error').text('');
    form.find('.form-message').text('');
  }

  // Validate password complexity
  function validPassword(pw) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(pw);
  }

  // LOGIN FORM SUBMIT
  $('#login-form').submit(function (e) {
    e.preventDefault();
    clearErrors($(this));

    let user_id = $('#login-user-id').val().trim();
    let password = $('#login-password').val();
    let recaptchaResponse = grecaptcha.getResponse();

    if (!user_id) {
      $('#login-user-id-error').text('User ID is required.');
      return;
    }
    if (!password) {
      $('#login-password-error').text('Password is required.');
      return;
    }
    if (!recaptchaResponse) {
      $('#login-recaptcha-error').text('Please complete the CAPTCHA.');
      return;
    }

    $.post('auth.php?action=login', {
      user_id: user_id,
      password: password,
      remember_me: $('#login-remember').is(':checked') ? 1 : 0,
      recaptcha: recaptchaResponse
    }, function (res) {
      if (res.success) {
        $('#login-message').css('color', 'green').text(res.message);
        setTimeout(() => {
          window.location.reload(); // or redirect to dashboard
        }, 1000);
      } else {
        $('#login-message').css('color', 'red').text(res.message);
        grecaptcha.reset();
      }
    }, 'json');
  });

  // FORGOT FORM SUBMIT (Send OTP)
  $('#forgot-form').submit(function (e) {
    e.preventDefault();
    clearErrors($(this));

    let user_id = $('#forgot-user-id').val().trim();
    let email = $('#forgot-email').val().trim();

    if (!user_id) {
      $('#forgot-user-id-error').text('User ID is required.');
      return;
    }
    if (!email) {
      $('#forgot-email-error').text('Email is required.');
      return;
    }

    $.post('auth.php?action=send_otp', { user_id, email }, function (res) {
      if (res.success) {
        $('#forgot-message').css('color', 'green').text(res.message);
        $('form').hide();
        $('#otp-form').show();
      } else {
        $('#forgot-message').css('color', 'red').text(res.message);
      }
    }, 'json');
  });

  // OTP FORM SUBMIT
  $('#otp-form').submit(function (e) {
    e.preventDefault();
    clearErrors($(this));

    let otp = $('#otp-code').val().trim();
    if (!otp || otp.length !== 6) {
      $('#otp-error').text('Enter a valid 6-digit OTP.');
      return;
    }

    $.post('auth.php?action=verify_otp', { otp }, function (res) {
      if (res.success) {
        $('#otp-message').css('color', 'green').text(res.message);
        $('form').hide();
        $('#reset-form').show();
      } else {
        $('#otp-message').css('color', 'red').text(res.message);
      }
    }, 'json');
  });

  // RESET PASSWORD FORM SUBMIT
  $('#reset-form').submit(function (e) {
    e.preventDefault();
    clearErrors($(this));

    let password = $('#reset-password').val();
    if (!validPassword(password)) {
      $('#reset-password-error').text('Password must have uppercase, lowercase, number, special char, and min 8 chars.');
      return;
    }

    $.post('auth.php?action=reset_password', { password }, function (res) {
      if (res.success) {
        $('#reset-message').css('color', 'green').text(res.message);
        setTimeout(() => {
          $('form').hide();
          $('#login-form').show();
          grecaptcha.reset();
        }, 1500);
      } else {
        $('#reset-message').css('color', 'red').text(res.message);
      }
    }, 'json');
  });
});
