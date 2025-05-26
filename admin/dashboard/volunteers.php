<?php
include('authentications/auth_check.php');
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

                            <!-- Search bar -->
                            <div class="container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-lg-4"> <!-- 12 columns on mobile, 4/12 (≈33%) on large screens -->
                                        <form class="w-100">
                                            <div class="input-group input-group-md" style="border:1px solid #ced4da; border-radius:50rem; overflow:hidden;">
                                                <span class="input-group-text bg-white border-0">
                                                    <i class="ri-search-line"></i>
                                                </span>
                                                <input type="text" class="form-control form-control-md border-0 ps-2" id="volunteerSearch" placeholder="Search volunteers..." aria-label="Search volunteers">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive mt-4">
                                <?php
                                // Fetch all volunteers
                                $sql = "SELECT * FROM volunteers ORDER BY id DESC";
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
                                            <th>Certificate</th>
                                            <th>Edit</th>
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
                                                    <td class="text-wrap"><?= htmlspecialchars($row['registration_id']) ?></td>
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
                                                        <div class="action-buttons">
                                                            <!-- Download PDF button -->
                                                            <a href="generate_certificate.php?registration_id=<?= urlencode($row['registration_id']) ?>" target="_blank"
                                                                class="btn btn-primary btn-sm"
                                                                title="Download Certificate PDF">
                                                                <i class="ri-download-line"></i> Download PDF
                                                            </a>

                                                            <!-- WhatsApp Share button -->
                                                            <!-- <?php
                                                                    $pdfUrl = urlencode('https://yourdomain.com/certificates/' . $row['registration_id'] . '.pdf');
                                                                    $whatsappMessage = urlencode("Check out my certificate: $pdfUrl");
                                                                    $whatsappShareUrl = "https://api.whatsapp.com/send?text=$whatsappMessage";
                                                                    ?>
                                                            <a href="<?= $whatsappShareUrl ?>" target="_blank"
                                                                class="btn btn-success btn-sm"
                                                                title="Share on WhatsApp">
                                                                <i class="ri-whatsapp-line"></i> Share on WhatsApp
                                                            </a> -->
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='edit_Folder/edit_Volunteers.php?registration_id=<?= $row['registration_id'] ?>'">
                                                            <i class="ri-file-edit-line"></i> Edit
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this volunteer?')) { window.location.href='edit_Folder/delete_volunteer.php?registration_id=<?= urlencode($row['registration_id']) ?>'; }">
                                                            <i class="ri-delete-bin-line"></i> Delete
                                                        </button>


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
            <!-- End Main Content -->
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>