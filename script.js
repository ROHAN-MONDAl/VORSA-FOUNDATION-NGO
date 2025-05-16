// Initialize the datepicker on the DOB input
const dobInput = document.getElementById("dob");
const datepicker = new Datepicker(dobInput, {
  format: "dd-mm-yyyy",
  autohide: true,
});

// State to District Mapping
const stateDistricts = {
  "West Bengal": [
    "Alipurduar", "Bankura", "Birbhum", "Cooch Behar", "Dakshin Dinajpur", "Darjeeling",
    "Hooghly", "Howrah", "Jalpaiguri", "Jhargram", "Kalimpong", "Kolkata", "Malda",
    "Murshidabad", "Nadia", "North 24 Parganas", "Paschim Bardhaman", "Paschim Medinipur",
    "Purba Bardhaman", "Purba Medinipur", "Purulia", "South 24 Parganas", "Uttar Dinajpur"
  ],
  "Maharashtra": [
    "Mumbai", "Pune", "Nagpur", "Thane", "Nashik", "Aurangabad", "Solapur", "Kolhapur"
  ],
  "Gujarat": [
    "Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Gandhinagar", "Jamnagar"
  ],
  "Tamil Nadu": [
    "Chennai", "Coimbatore", "Madurai", "Salem", "Tiruchirappalli", "Vellore"
  ],
  "Uttar Pradesh": [
    "Lucknow", "Kanpur", "Varanasi", "Agra", "Noida", "Ghaziabad", "Meerut", "Prayagraj"
  ],
  "Bihar": [
    "Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Purnia", "Darbhanga"
  ]
  // Add more states and districts as needed
};

// Load states on page load
window.onload = function () {
  const stateSelect = document.getElementById("state");
  Object.keys(stateDistricts).forEach(state => {
    const option = document.createElement("option");
    option.value = state;
    option.textContent = state;
    stateSelect.appendChild(option);
  });
};

// Load districts based on selected state
function loadDistricts() {
  const state = document.getElementById("state").value;
  const districtSelect = document.getElementById("district");
  districtSelect.innerHTML = '<option value="" disabled selected>Select your district</option>';

  if (stateDistricts[state]) {
    stateDistricts[state].forEach(district => {
      const option = document.createElement("option");
      option.value = district;
      option.textContent = district;
      districtSelect.appendChild(option);
    });
  }
}


// Form validation

$(document).ready(function () {

  // Wrap inputs/selects inside a div for positioning icons
  $(".volunteer_form input, .volunteer_form select").each(function () {
    if (!$(this).parent().hasClass('input-group-validation')) {
      $(this).wrap('<div class="input-group-validation"></div>');
    }
  });

  function showValid(el) {
    el.removeClass('invalid').addClass('valid');
    el.css('border-color', 'green');
    // Remove previous icons
    el.siblings('.valid-feedback-icon, .invalid-feedback-icon').remove();
    // Add green check icon
    el.after('<span class="valid-feedback-icon">&#10004;</span>');
  }

  function showInvalid(el) {
    el.removeClass('valid').addClass('invalid');
    el.css('border-color', 'red');
    // Remove previous icons
    el.siblings('.valid-feedback-icon, .invalid-feedback-icon').remove();
    // Add red cross icon
    el.after('<span class="invalid-feedback-icon">&#10006;</span>');
  }

  // Validate Name - non empty, only letters and spaces allowed
  function validateName() {
    let name = $("#name").val().trim();
    let regex = /^[a-zA-Z\s]+$/;
    if (name !== "" && regex.test(name)) {
      showValid($("#name"));
      return true;
    } else {
      showInvalid($("#name"));
      return false;
    }
  }

  // Validate DOB - format DD-MM-YYYY and >= 18 years old
  function validateDOB() {
    let dob = $("#dob").val().trim();
    let regex = /^(\d{2})-(\d{2})-(\d{4})$/;
    if (!regex.test(dob)) {
      showInvalid($("#dob"));
      return false;
    }
    let parts = dob.match(regex);
    let day = parseInt(parts[1], 10);
    let month = parseInt(parts[2], 10) - 1; // months 0-11 in JS
    let year = parseInt(parts[3], 10);

    let birthDate = new Date(year, month, day);
    if (birthDate.getDate() !== day || birthDate.getMonth() !== month || birthDate.getFullYear() !== year) {
      showInvalid($("#dob"));
      return false; // invalid date
    }

    let today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }

    if (age >= 18) {
      showValid($("#dob"));
      return true;
    } else {
      showInvalid($("#dob"));
      return false;
    }
  }

  // Validate Mobile - 10 digits only
  function validateMobile() {
    let mob = $("#mob").val().trim();
    let regex = /^[0-9]{10}$/;
    if (regex.test(mob)) {
      showValid($("#mob"));
      return true;
    } else {
      showInvalid($("#mob"));
      return false;
    }
  }

  // Validate Email
  function validateEmail() {
    let email = $("#mail").val().trim();
    // Basic email regex
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (regex.test(email)) {
      showValid($("#mail"));
      return true;
    } else {
      showInvalid($("#mail"));
      return false;
    }
  }

  // Validate State - must be selected
  function validateState() {
    let state = $("#state").val();
    if (state) {
      showValid($("#state"));
      return true;
    } else {
      showInvalid($("#state"));
      return false;
    }
  }

  // Validate District - must be selected
  function validateDistrict() {
    let district = $("#district").val();
    if (district) {
      showValid($("#district"));
      return true;
    } else {
      showInvalid($("#district"));
      return false;
    }
  }

  // Validate Village - non-empty
  function validateVillage() {
    let village = $("#village").val().trim();
    if (village !== "") {
      showValid($("#village"));
      return true;
    } else {
      showInvalid($("#village"));
      return false;
    }
  }

  // Validate Block - non-empty
  function validateBlock() {
    let block = $("#block").val().trim();
    if (block !== "") {
      showValid($("#block"));
      return true;
    } else {
      showInvalid($("#block"));
      return false;
    }
  }

  // Validate Pin - exactly 6 digits
  function validatePin() {
    let pin = $("#pin").val().trim();
    let regex = /^[0-9]{6}$/;
    if (regex.test(pin)) {
      showValid($("#pin"));
      return true;
    } else {
      showInvalid($("#pin"));
      return false;
    }
  }

  // Validate Blood Group - must be selected
  function validateBlood() {
    let blood = $("#blood").val();
    if (blood) {
      showValid($("#blood"));
      return true;
    } else {
      showInvalid($("#blood"));
      return false;
    }
  }

  // Validate Volunteer Checkbox
  function validateCheckbox() {
    if ($("#volunteer").is(":checked")) {
      // Remove red border if any
      $("#volunteer").removeClass('invalid').addClass('valid');
      return true;
    } else {
      $("#volunteer").removeClass('valid').addClass('invalid');
      return false;
    }
  }

  // Run validation on input/change events
  $("#name").on("input", validateName);
  $("#dob").on("input", validateDOB);
  $("#mob").on("input", validateMobile);
  $("#mail").on("input", validateEmail);
  $("#state").on("change", validateState);
  $("#district").on("change", validateDistrict);
  $("#village").on("input", validateVillage);
  $("#block").on("input", validateBlock);
  $("#pin").on("input", validatePin);
  $("#blood").on("change", validateBlood);
  $("#volunteer").on("change", validateCheckbox);

  // On form submit, validate all and block submit if invalid
  $(".volunteer_form").on("submit", function (e) {
    let valid =
      validateName() &
      validateDOB() &
      validateMobile() &
      validateEmail() &
      validateState() &
      validateDistrict() &
      validateVillage() &
      validateBlock() &
      validatePin() &
      validateBlood() &
      validateCheckbox();

    if (!valid) {
      e.preventDefault(); // stop form submit
      alert("Please enter correct infornation before submitting.");
    }
  });

  document.querySelector(".volunteer_form").addEventListener("submit", function (e) {
    const captchaResponse = grecaptcha.getResponse();
    if (captchaResponse.length === 0) {
      alert("Please check the reCAPTCHA before submitting.");
      e.preventDefault(); // Stop form submission
    }
  });

});

// Pop up message box
$(document).ready(function () {
  const params = new URLSearchParams(window.location.search);
  if (params.get("otp") === "1") {
    $("#otpPromptModal").fadeIn();
  }

  $("#submitOtpBtn").click(function () {
    const otp = $("#customOtpInput").val().trim();
    if (otp === "") {
      alert("Please enter the OTP.");
      return;
    }
    window.location.href = "verify.php?code=" + encodeURIComponent(otp);
  });

  $("#cancelOtpBtn").click(function () {
    alert("OTP verification is required to complete registration.");
    $("#otpPromptModal").fadeOut();
  });

  // Close modal when clicking outside the modal content
  $(window).click(function (event) {
    if ($(event.target).is("#otpPromptModal")) {
      $("#otpPromptModal").fadeOut();
    }
  });
});