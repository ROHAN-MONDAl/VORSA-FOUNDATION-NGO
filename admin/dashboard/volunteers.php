<?php
session_start();

// Check if admin session exists
if (!isset($_SESSION['admin'])) {
    // If no session but "remember me" cookie exists, create session from cookie
    if (isset($_COOKIE['admin_remember'])) {
        $_SESSION['admin'] = $_COOKIE['admin_remember'];
    } else {
        // Neither session nor cookie present, redirect to login
        header("Location: ../index.php");
        exit;
    }
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

    <link rel="stylesheet" href="css/style.css">
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
                <img src="../images/logo.png" class="logo" alt="Logo" />
                <div class="brand-name">Vorsa Foundation</div>
            </div>
            <nav class="sidebar-nav mt-4">
                <a class="nav-link " href="dashboard.php" data-menu="dashboard"><i class="ri-dashboard-line"></i> Dashboard</a>
                <a class="nav-link active" href="volunteers.php" data-menu="projects"><i class="ri-team-line"></i> Volunteers </a>
            </nav>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Header/Navbar -->
            <nav class="navbar navbar-expand-lg" id="header-navbar">
                <button class="hamburger" id="hamburger-btn">
                    <i class="ri-menu-2-line" style="font-size: 1.5rem; color: #16a34a;"></i>
                </button>
                <a class="navbar-brand mx-auto" href="dashboard.php">
                    <img src="../images/logo.png" class="navbar-logo" alt="Logo" />
                    Vorsa Foundation
                </a>
                <div class="dropdown">
                    <button class="btn btn-link p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../images/logo.png" class="profile-avatar" alt="Profile" />
                    </button>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="margin-left:2%;">
                            <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="settings.php"><i class="ri-settings-3-line me-2"></i>Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class="ri-logout-box-r-line me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="dashboard-card">
                    <div class="dashboard-title p-2 text-center">Volunteers</div>
                    <!-- <div class="dashboard-desc mb-4 p-2 text-center">Manage your NGO activities, donations, volunteers, and more.</div> -->
                    <div class="tab-content" id="dashboardTabsContent">

                        <!-- Volunteers Tab -->
                        <div id="volunteers" role="tabpanel">
                            <div class="table-responsive mt-4">
                                <table class="table table-green align-middle">
                                    <thead>
                                        <tr>
                                           <th>Slno.</th>
                                            <th>Registration Id</th>
                                            <th>Name</th>
                                            <th>Dob</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Village</th>
                                            <th>Block</th>
                                            <th>Pin</th>
                                            <th>Blood Group</th>
                                            <th>Share</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td class="text-wrap">1</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-approve btn-sm"><i class="ri-share-line"></i> Share </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-danger btn-sm"><i class="ri-file-edit-line"></i> Edit</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
    <script src="js/script.js"></script>
</body>

</html>