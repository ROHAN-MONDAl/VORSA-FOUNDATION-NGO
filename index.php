<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>VORSA Volunteer Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Vanilla Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css" rel="stylesheet">
    <!-- Custom Css -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container py-5">
        <div class="form-container">

            <h3 class="text-center mt-4 mb-4 heading_form_name"><strong>Volunteer Registration Form</strong></h3>

            <form class="volunteer_form" method="POST" action="register.php">

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" placeholder="Enter your name" id="name" name="name"
                        required>
                </div>

                <!-- DOB -->
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth:</label>
                    <input type="text" class="form-control" id="dob" name="dob" placeholder="DD-MM-YYYY" required>
                </div>

                <!-- Mobile -->
                <div class="mb-3">
                    <label for="mob" class="form-label">Mobile Number:</label>
                    <input type="tel" class="form-control" placeholder="Enter your phone number" id="mob" name="mob"
                        pattern="[0-9]{10}" maxlength="10" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="mail" class="form-label">Email:</label>
                    <input type="email" class="form-control" placeholder="Enter your email address" id="mail"
                        name="mail" required>
                </div>

                <div class="row">
                    <!-- State Dropdown -->
                    <div class="col-md-6 mb-3">
                        <label for="state" class="form-label">State:</label>
                        <select id="state" class="form-select" name="state" onchange="loadDistricts()" required>
                            <option value="" disabled selected>Select your state</option>
                        </select>
                    </div>

                    <!-- District Dropdown -->
                    <div class="col-md-6 mb-3">
                        <label for="district" class="form-label">District:</label>
                        <select id="district" class="form-select" name="district" required>
                            <option value="" disabled selected>Select your district</option>
                        </select>
                    </div>
                </div>

                <!-- Address Fields -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="village" class="form-label">Village name:</label>
                        <input type="text" class="form-control" placeholder="Enter your village name" id="village"
                            name="village" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="block" class="form-label">Block no:</label>
                        <input type="text" class="form-control" placeholder="Enter your block no" id="block"
                            name="block" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pin" class="form-label">Pin Code:</label>
                    <input type="text" class="form-control" placeholder="Enter your pin code ex: 713202" id="pin"
                        name="pin" pattern="[0-9]{6}" maxlength="6" required>
                </div>

                <!-- Blood Group -->
                <div class="mb-3">
                    <label for="blood" class="form-label">Blood Group:</label>
                    <select class="form-select" id="blood" name="blood" required>
                        <option value="" selected disabled>Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A−</option>
                        <option value="B+">B+</option>
                        <option value="B-">B−</option>
                        <option value="O+">O+</option>
                        <option value="O-">O−</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB−</option>
                    </select>
                </div>

                <!-- Checkbox -->
                <div class="form-check mb-3 text-center d-flex justify-content-center">
                    <input class="form-check-input custom-checkbox" type="checkbox" id="volunteer" name="volunteer"
                        required>
                    <label class="form-check-label" for="volunteer">
                        &nbsp;I want to work with vorsha foundation as a volunteer.
                    </label>
                </div>

                <div style="text-align: center;">
                    <div style="display: inline-block; transform: scale(0.85);">
                        <!-- reCAPTCHA widget -->
                        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="full-width-btn">Submit</button>
                </div>

            </form>


            <!-- OTP Modal -->
            <div id="otpPromptModal" class="otp-modal" style="display: none;">
                <div class="otp-modal-content">
                    <h5>OTP Verification</h5>
                    <p>Please check your email and enter the OTP below:</p>
                    <input type="number" id="customOtpInput" maxlength="6" placeholder="Enter OTP" class="otp-input">
                    <div class="otp-buttons">
                        <button type="button" id="submitOtpBtn" class="btn btn-primary">Submit</button>
                        <button type="button" id="cancelOtpBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Registration Successful pop up message -->
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success' && isset($_GET['id'])): ?>
                <div id="popupMsg">
                    <h4><strong>Registration Successful</strong></h4>
                    <p style="margin-top: 20px;">
                        Thank you for registering with <strong>Vorsha Foundation</strong>.
                        You will be notified once your application has been reviewed and approved by our team.
                        A confirmation email has been sent to your registered address.
                    </p>
                    <p>Your registration ID is:</p>
                    <div class="reg-id"><?php echo htmlspecialchars($_GET['id']); ?></div>
                    <br><br>
                    <button onclick="document.getElementById('popupMsg').style.display='none'">Close</button>
                </div>
            <?php endif; ?>



        </div>

    </div>

    <!-- Captcha code -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
    <!-- Moment.js for date manipulation -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>

    <!-- Custom js -->
    <script src="script.js"></script>
</body>

</html>