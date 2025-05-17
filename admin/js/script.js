$(document).ready(function () {
  // Helper: clear errors and reset input classes inside a form
  function clearErrors(form) {
    form.find('.error').text('');
    form.find('input').removeClass('valid invalid');
  }

  // Function to validate inputs on the fly for green tick and red border
  function validateInput(input, errorSpan, validateFn, errorMsg) {
    const val = input.val().trim();
    if (!validateFn(val)) {
      errorSpan.text(errorMsg);
      input.removeClass('valid').addClass('invalid');
      return false;
    } else {
      errorSpan.text('');
      input.removeClass('invalid').addClass('valid');
      return true;
    }
  }

  // Login form validation on submit
  $('#loginForm').on('submit', function (e) {
    let isValid = true;
    clearErrors($(this));

    // Validate User ID
    isValid &= validateInput(
      $('#user_id'),
      $('#user_id_error'),
      val => val !== '',
      'User ID is required'
    );

    // Validate Password
    isValid &= validateInput(
      $('#password'),
      $('#password_error'),
      val => val !== '',
      'Password is required'
    );

    if (!isValid) e.preventDefault();
  });

  // Real-time validation for login inputs on input event
  $('#user_id').on('input', function () {
    validateInput($(this), $('#user_id_error'), val => val !== '', 'User ID is required');
  });
  $('#password').on('input', function () {
    validateInput($(this), $('#password_error'), val => val !== '', 'Password is required');
  });

  // Forgot password form validation on submit
  $('#forgotForm').on('submit', function (e) {
    let isValid = true;
    clearErrors($(this));

    const emailInput = $('#forgot_email');
    const emailError = $('#forgot_email_error');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    isValid &= validateInput(
      emailInput,
      emailError,
      val => val !== '' && emailRegex.test(val),
      'Please enter a valid email'
    );

    if (!isValid) e.preventDefault();
  });

  // Real-time validation for forgot password email input
  $('#forgot_email').on('input', function () {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    validateInput($(this), $('#forgot_email_error'), val => val !== '' && emailRegex.test(val), 'Please enter a valid email');
  });

  // Modal show/hide logic
  $('#forgotModal').hide();

  $('#forgotLink').click(function (e) {
    e.preventDefault();
    $('#forgotModal').fadeIn();
  });

  $('#forgotModal').click(function (e) {
    if ($(e.target).is('#forgotModal')) {
      $(this).fadeOut();
      clearErrors($('#forgotForm'));
      $('#forgotForm')[0].reset();
    }
  });

  $('.close-btn').click(function () {
    $('#forgotModal').fadeOut();
    clearErrors($('#forgotForm'));
    $('#forgotForm')[0].reset();
  });
});
