<?php
include('authentications/auth_check.php'); // DB connection + session

// Fetch current admin details using ID
$sql = "SELECT user_id, email, password FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_admin_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
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
                    <div class="dashboard-title p-2 text-center">
                        <h1>Settings</h1>
                    </div>
                    <div class="tab-content" id="dashboardTabsContent">
                        <h2>Update Admin Details</h2>

                        <!-- Notifications -->
                        <?php if (isset($_SESSION['admin_message'])): ?>
                            <div class="alert alert-<?= $_SESSION['admin_message_type'] ?? 'info' ?>" role="alert" style="margin-bottom:20px;">
                                <?= htmlspecialchars($_SESSION['admin_message']); ?>
                            </div>
                            <?php
                            // Clear message after showing
                            unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
                            ?>
                        <?php endif; ?>

                        <?php if ($data): ?>
                            <form action="edit_Folder/update_admin.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">User ID</label>
                                    <input type="text" class="form-control" name="user_id" value="<?= htmlspecialchars($data['user_id']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" name="password" value="" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Admin</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-danger">Admin record not found.</div>
                        <?php endif; ?>

                        <script>
                            document.getElementById('adminForm').addEventListener('submit', function(e) {
                                const password = document.querySelector('input[name="password"]').value;

                                if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[^a-zA-Z0-9]/.test(password)) {
                                    alert("Password must contain at least one uppercase letter, one lowercase letter, and one special character.");
                                    e.preventDefault();
                                }
                            });
                        </script>
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