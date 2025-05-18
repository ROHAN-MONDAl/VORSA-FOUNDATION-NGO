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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="style.css">
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
                <img src="https://img.icons8.com/color/96/000000/charity.png" class="logo" alt="Logo" />
                <div class="brand-name">Vorsa Foundation</div>
            </div>
            <nav class="sidebar-nav mt-4">
                <a class="nav-link active" href="#" data-menu="dashboard"><i class="ri-dashboard-line"></i> Dashboard</a>
                <a class="nav-link" href="#" data-menu="projects"><i class="ri-folder-line"></i> Projects</a>
                <a class="nav-link" href="#" data-menu="donations"><i class="ri-hand-heart-line"></i> Donations</a>
                <a class="nav-link" href="#" data-menu="volunteers"><i class="ri-team-line"></i> Volunteers</a>
                <a class="nav-link" href="#" data-menu="reports"><i class="ri-bar-chart-2-line"></i> Reports</a>
            </nav>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Header/Navbar -->
            <nav class="navbar navbar-expand-lg" id="header-navbar">
                <button class="hamburger" id="hamburger-btn">
                    <i class="ri-menu-2-line" style="font-size: 1.5rem; color: #16a34a;"></i>
                </button>
                <a class="navbar-brand mx-auto" href="#">
                    <img src="https://img.icons8.com/color/96/000000/charity.png" class="navbar-logo" alt="Logo" />
                    Vorsa Foundation
                </a>
                <div class="dropdown">
                    <button class="btn btn-link p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://img.icons8.com/color/96/000000/user-male-circle--v2.png" class="profile-avatar" alt="Profile" />
                    </button>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="margin-left:2%;">
                            <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="#"><i class="ri-settings-3-line me-2"></i>Settings</a></li>
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
                    <div class="dashboard-title p-2 text-center">Welcome to Dashboard</div>
                    <div class="dashboard-desc mb-4 p-2 text-center">Manage your NGO activities, donations, volunteers, and more.</div>
                    <!-- Tabs -->
                    <ul class="nav nav-tabs dashboard-tabs" id="dashboardTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="form-tab" data-bs-toggle="tab" data-bs-target="#form" type="button" role="tab">Add Project</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button" role="tab">Donations</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="dashboardTabsContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row mt-4">
                                <div class="col-md-4 mb-3">
                                    <div class="card text-center bg-success text-white h-100 shadow-sm">
                                        <div class="card-body">
                                            <i class="ri-hand-heart-line mb-2" style="font-size:2rem;"></i>
                                            <h4 class="card-title fw-bold">₹ 1,20,000</h4>
                                            <p class="card-text">Total Donations</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card text-center bg-primary text-white h-100 shadow-sm">
                                        <div class="card-body">
                                            <i class="ri-team-line mb-2" style="font-size:2rem;"></i>
                                            <h4 class="card-title fw-bold">58</h4>
                                            <p class="card-text">Volunteers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card text-center bg-warning text-dark h-100 shadow-sm">
                                        <div class="card-body">
                                            <i class="ri-folder-line mb-2" style="font-size:2rem;"></i>
                                            <h4 class="card-title fw-bold">12</h4>
                                            <p class="card-text">Projects</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Form Tab -->
                        <div class="tab-pane fade" id="form" role="tabpanel">
                            <form class="modern-form mt-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Project Name</label>
                                        <input type="text" class="form-control" placeholder="Enter project name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" required>
                                            <option value="">Select category</option>
                                            <option>Education</option>
                                            <option>Health</option>
                                            <option>Environment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Project description" required></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-gradient w-100 mt-2">Add Project</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Table Tab -->
                        <div class="tab-pane fade" id="table" role="tabpanel">
                            <div class="table-responsive mt-4">
                                <table class="table table-green align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Donor Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Rahul Sharma</td>
                                            <td>₹5,000</td>
                                            <td>2024-06-01</td>
                                            <td><span class="badge bg-success">Approved</span></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-approve btn-sm"><i class="ri-check-line"></i> Approve</button>
                                                    <button class="btn btn-reject btn-sm"><i class="ri-close-line"></i> Reject</button>
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
    <script src="script.js"></script>
</body>

</html>