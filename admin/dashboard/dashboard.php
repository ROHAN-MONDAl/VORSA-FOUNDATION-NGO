<?php

 include('authentications/auth_check.php');


// Volunteers with certificate (approved)
$volunteerCount = $conn->query("SELECT COUNT(*) AS total FROM volunteers WHERE certificate_path IS NOT NULL")->fetch_assoc()['total'];

// Volunteers without certificate (pending)
$pendingCount = $conn->query("SELECT COUNT(*) AS total FROM volunteers WHERE certificate_path IS NULL")->fetch_assoc()['total'];

// Total certificates issued (same as volunteers with certificate)
$certCount = $volunteerCount;

// Total registration requests
$totalRequests = $conn->query("SELECT COUNT(*) AS total FROM registrations")->fetch_assoc()['total'];


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
                <a class="nav-link active" href="dashboard.php" data-menu="dashboard"><i class="ri-dashboard-line"></i> Dashboard</a>
                <a class="nav-link" href="volunteers.php" data-menu="projects"><i class="ri-team-line"></i> Volunteers </a>
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
                    <div class="dashboard-title p-2 text-center">Welcome to Dashboard</div>
                    <div class="dashboard-desc mb-4 p-2 text-center">Manage your NGO activities, donations, volunteers, and more.</div>
                    <!-- Tabs -->
                    <div class="dashboard-tabs-wrapper">
                        <ul class="nav nav-tabs dashboard-tabs" id="dashboardTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="form-tab" data-bs-toggle="tab" data-bs-target="#pending_requests" type="button" role="tab">Registration Requests</button>
                            </li>
                        </ul>
                    </div>


                    <div class="tab-content" id="dashboardTabsContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row mt-4">
                                <!-- Volunteers -->
                                <div class="col-md-4 mb-3">
                                    <div class="card custom-card h-100 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="ri-team-line mb-2"></i>
                                            <h4 class="card-title fw-bold"><?= $volunteerCount ?></h4>
                                            <p class="card-text">Volunteers</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Requests -->
                                <div class="col-md-4 mb-3">
                                    <div class="card custom-card h-100 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="ri-user-received-line mb-2"></i>
                                            <h4 class="card-title fw-bold"><?= $totalRequests ?></h4>
                                            <p class="card-text">Pending Requests</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificates -->
                                <div class="col-md-4 mb-3">
                                    <div class="card custom-card h-100 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="ri-medal-line mb-2"></i>
                                            <h4 class="card-title fw-bold"><?= $certCount ?></h4>
                                            <p class="card-text">Certificates Issued</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- pending_requests Tab -->
                    <div class="tab-pane fade" id="pending_requests" role="tabpane">
                        <div class="table-responsive-wrapper">
                            <div class="table-responsive mt-4">
                                <?php
                                // Fetch all registrations
                                $sql = "SELECT * FROM registrations ORDER BY id DESC";
                                $result = mysqli_query($conn, $sql);
                                ?>

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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sl = 1;
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td class="text-wrap"><?= $sl++ ?></td>
                                                    <td><?= htmlspecialchars($row['registration_id']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['name']) ?></td>
                                                    <td><?= htmlspecialchars($row['dob']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['mobile']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['email']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['state']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['district']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['village']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['block']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['pin']) ?></td>
                                                    <td class="text-wrap"><?= htmlspecialchars($row['blood_group']) ?></td>
                                                    <td>
                                                        <div class="d-grid gap-2 d-md-flex">
                                                            <button class="btn btn-success btn-sm approve-btn" data-id="<?= $row['id'] ?>">
                                                                <i class="ri-checkbox-circle-line me-1"></i> Approve
                                                            </button>
                                                            <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $row['id'] ?>">
                                                                <i class="ri-close-circle-line me-1"></i> Reject
                                                            </button>
                                                        </div>


                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="15" class="text-center text-muted">No data found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
        <!-- Loader (centered with spinner) -->
        <div id="loading-screen" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
">
            <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>






    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>