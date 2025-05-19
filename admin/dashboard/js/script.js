$(document).ready(function () {
    const $sidebar = $('#sidebar-wrapper');
    const $loadingScreen = $('#loading-screen');  // Cache loading screen div

    // Sidebar toggle
    $('#sidebar-toggle-btn, #hamburger-btn').on('click', function () {
        $sidebar.toggleClass('closed');
    });

    $('#sidebar-close-btn').on('click', function () {
        $sidebar.addClass('closed');
    });

    $('.sidebar-nav').on('click', '.nav-link', function () {
        $('.sidebar-nav .nav-link').removeClass('active');
        $(this).addClass('active');
    });

    $sidebar.addClass('closed');

    // Handle approve/reject button click with AJAX
    $(document).ready(function () {
        // Handle Approve/Reject button clicks using event delegation
        $(document).on('click', '.approve-btn, .reject-btn', function (e) {
            e.preventDefault();

            const $button = $(this);
            const id = $button.data('id');
            const action = $button.hasClass('approve-btn') ? 'approve' : 'reject';

            // Show loader (set display: flex)
            $('#loading-screen').css('display', 'flex');

            $.ajax({
                url: 'handle_action.php',
                method: 'POST',
                data: { id: id, action: action },
                success: function (response) {
                    // Hide loader
                    $('#loading-screen').css('display', 'none');

                    if (response === 'approved' || response === 'rejected') {
                        // Remove the corresponding row
                        const $row = $button.closest('tr');
                        $row.fadeOut(300, () => {
                            $row.remove();

                            const $tbody = $('table.table tbody');
                            if ($tbody.children('tr').length === 0) {
                                $tbody.append(`<tr><td colspan="13" class="text-center text-muted">No data found</td></tr>`);
                            }
                        });
                    } else {
                        alert('Error: ' + response);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Hide loader
                    $('#loading-screen').css('display', 'none');

                    console.error('Server error:', textStatus, errorThrown);
                    alert('Server error occurred. Please try again.');
                }
            });
        });
    });

});
