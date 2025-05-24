
$(document).ready(function () {
    const $sidebar = $('#sidebar-wrapper');
    const $loadingScreen = $('#loading-screen');  // Cache the loading screen div

    // --- Sidebar toggle handlers ---
    $('#sidebar-toggle-btn, #hamburger-btn').on('click', function () {
        $sidebar.toggleClass('closed');
    });

    $('#sidebar-close-btn').on('click', function () {
        $sidebar.addClass('closed');
    });

    // Highlight active sidebar link
    $('.sidebar-nav').on('click', '.nav-link', function () {
        $('.sidebar-nav .nav-link').removeClass('active');
        $(this).addClass('active');
    });

    // Initially close sidebar
    $sidebar.addClass('closed');

    // --- Loader functions ---
    function showLoading() {
        $loadingScreen.fadeIn(200); // Show the loading overlay with fade-in
    }

    function hideLoading() {
        $loadingScreen.fadeOut(200); // Hide the loading overlay with fade-out
    }

    // --- AJAX Approve/Reject Handler ---
    $(document).on('click', '.approve-btn, .reject-btn', function (e) {
        e.preventDefault(); // Prevent default form or link action

        const $button = $(this);
        const id = $button.data('id'); // ID of the registration row
        const action = $button.hasClass('approve-btn') ? 'approve' : 'reject'; // Determine action type

        // Confirm only for reject
        if (action === 'reject') {
            if (!confirm('Are you sure to reject?')) {
                return; // Cancel clicked, stop here
            }
        }

        showLoading(); // Show loader before AJAX call

        $.ajax({
            url: 'handle_action.php',
            method: 'POST',
            data: { id: id, action: action },
            success: function (response) {
                hideLoading(); // Hide loader once response received

                if (response === 'approved' || response === 'rejected') {
                    // Remove table row after success
                    const $row = $button.closest('tr');
                    $row.fadeOut(300, () => {
                        $row.remove();

                        // If table is empty, show fallback message
                        const $tbody = $('table.table tbody');
                        if ($tbody.children('tr').length === 0) {
                            $tbody.append('<tr><td colspan="13" class="text-center text-muted">No data found</td></tr>');
                        }
                    });
                } else if (response === 'duplicate') {
                    alert('Duplicate volunteer entry detected. Redirecting to dashboard.');
                    window.location.href = 'dashboard.php';
                } else {
                    alert('Error: ' + response);
                }
            },
            error: function () {
                hideLoading();
                alert('Server error occurred. Please try again.');
            }
        });
    });




    // --- Hide loader on initial page load (if it was accidentally shown) ---
    hideLoading();
});



// --- Volunteers search bar ---
document.getElementById('volunteerSearch').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    const table = document.querySelector('#volunteers table tbody');
    const rows = table.getElementsByTagName('tr');

    for (let row of rows) {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    }
});



