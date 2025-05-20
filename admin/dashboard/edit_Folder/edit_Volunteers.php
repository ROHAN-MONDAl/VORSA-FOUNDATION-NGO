<?php
session_start();
// Include database connection and functions
include('../../../server.php');
// Check if admin session exists
if (!isset($_SESSION['admin'])) {
    // If no session but "remember me" cookie exists, create session from cookie
    if (isset($_COOKIE['admin_remember'])) {
        $_SESSION['admin'] = $_COOKIE['admin_remember'];
    } else {
        // Neither session nor cookie present, redirect to login
        header("Location: ../dashboard.php");
        exit;
    }
}

if (!isset($_GET['registration_id'])) {
    die("Registration ID missing");
}

$registration_id = $_GET['registration_id'];

$query = "SELECT * FROM volunteers WHERE registration_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $registration_id); // 's' because registration_id is string
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("No volunteer found for ID: " . htmlspecialchars($registration_id));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Business Dashboard</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../../../style.css">
</head>

<body>
    <div id="wrapper" class="d-flex" style="height:100vh;">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <button id="sidebar-toggle-btn" class="d-none d-lg-flex" aria-label="Toggle sidebar">
                <i class="ri-menu-2-line" style="font-size:1.5rem;"></i>
            </button>
            <button id="sidebar-close-btn" class="d-lg-none" aria-label="Close sidebar">
                <i class="ri-close-line" style="font-size:1.5rem;"></i>
            </button>
            <div class="sidebar-header">
                <img src="../../images/logo.png" class="logo" alt="Logo" />
                <div class="brand-name">Vorsa Foundation</div>
            </div>
            <nav class="sidebar-nav mt-4">
                <a class="nav-link " href="../dashboard.php" data-menu="dashboard"><i class="ri-dashboard-line"></i> Dashboard</a>
                <a class="nav-link active" href="../volunteers.php" data-menu="projects"><i class="ri-team-line"></i> Volunteers </a>
            </nav>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Header/Navbar -->
            <nav class="navbar navbar-expand-lg" id="header-navbar">
                <button class="hamburger" id="hamburger-btn">
                    <i class="ri-menu-2-line" style="font-size: 1.5rem; color: #16a34a;"></i>
                </button>
                <a class="navbar-brand mx-auto" href="../dashboard.php">
                    <img src="../../images/logo.png" class="navbar-logo" alt="Logo" />
                    Vorsa Foundation
                </a>
                <div class="dropdown">
                    <button class="btn btn-link p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../../images/logo.png" class="profile-avatar" alt="Profile" />
                    </button>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="margin-left:2%;">
                            <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="../settings.php"><i class="ri-settings-3-line me-2"></i>Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../logout.php"><i class="ri-logout-box-r-line me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="dashboard-card">
                    <div class="dashboard-title p-2 text-center">Update Volunteers</div>
                    <!-- <div class="dashboard-desc mb-4 p-2 text-center">Manage your NGO activities, donations, volunteers, and more.</div> -->
                    <div class="tab-content" id="dashboardTabsContent">

                        <!-- Volunteers Tab -->
                        <div id="volunteers" role="tabpanel">
                            <div class="table-responsive mt-4">

                                <div class="tab-pane" id="volunteer-form" role="tabpanel">
                                    <!-- Your Form -->
                                    <div class="container my-4">
                                        <div class="form-container">
                                            <?php
                                            if (!empty($_SESSION['success_message'])) {
                                                echo '<div class="alert alert-success text-center py-1 px-2 small" style="font-size: 0.875rem; margin: 10px auto; max-width: 300px; border-radius: 4px;">'
                                                    . $_SESSION['success_message'] . '</div>';
                                                unset($_SESSION['success_message']);
                                            }

                                            if (!empty($_SESSION['error_message'])) {
                                                echo '<div class="alert alert-danger text-center py-1 px-2 small" style="font-size: 0.875rem; margin: 10px auto; max-width: 300px; border-radius: 4px;">'
                                                    . $_SESSION['error_message'] . '</div>';
                                                unset($_SESSION['error_message']);
                                            }
                                            ?>

                                            <form action="update_volunteer.php" method="POST" class="modern-form mt-4 volunteer_form">





                                                <div class="mb-3">
                                                    <label class="form-label fw-bold h5">Registration ID</label>
                                                    <h3><?= htmlspecialchars($registration_id) ?></h3>
                                                    <input type="hidden" name="registration_id" value="<?= htmlspecialchars($registration_id) ?>">
                                                </div>


                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Date of Birth</label>
                                                        <input type="text" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($data['dob']) ?>" placeholder="DD-MM-YYYY" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Mobile Number</label>
                                                        <input type="text" class="form-control" id="mob" name="mobile" value="<?= htmlspecialchars($data['mobile']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="mail" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">State</label>
                                                        <select class="form-select" id="state" name="state" onchange="loadDistricts()" required>
                                                            <option value="">Select your state</option>
                                                            <!-- Dynamically loaded options via JS -->
                                                            <option value="<?= htmlspecialchars($data['state']) ?>" selected><?= htmlspecialchars($data['state']) ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">District</label>
                                                        <select class="form-select" id="district" name="district" required>
                                                            <option value="<?= htmlspecialchars($data['district']) ?>" selected><?= htmlspecialchars($data['district']) ?></option>
                                                            <!-- Dynamically loaded options -->
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Village Name</label>
                                                        <input type="text" class="form-control" id="village" name="village" value="<?= htmlspecialchars($data['village']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Block No</label>
                                                        <input type="text" class="form-control" id="block" name="block" value="<?= htmlspecialchars($data['block']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Pin Code</label>
                                                        <input type="text" class="form-control" id="pin" name="pin" value="<?= htmlspecialchars($data['pin']) ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Blood Group</label>
                                                        <select class="form-select" id="blood" name="blood" required>
                                                            <option value="">Select blood group</option>
                                                            <?php
                                                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                                                            foreach ($bloodGroups as $group) {
                                                                $selected = ($data['blood_group'] === $group) ? 'selected' : '';
                                                                echo "<option value=\"$group\" $selected>$group</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="volunteer" name="confirm" style="border-color: green;" required />
                                                            <label class="form-check-label" for="volunteer">
                                                                Are you sure you want to save?
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-between mt-2">
                                                        <a href="../volunteers.php" class="btn btn-sm me-2" style="background-color: #ffc107; color: #212529; font-size: 0.875rem;">
                                                            &larr; Back
                                                        </a>
                                                        <button type="submit" class="btn btn-gradient btn-sm ms-2" style="font-size: 0.875rem;">
                                                            Submit
                                                        </button>
                                                    </div>


                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Main Content -->
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Include jQuery and Datepicker script -->
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css" rel="stylesheet" />

    <script src="../js/script.js"></script>
    <script src="../../../script.js"></script>
</body>

</html>