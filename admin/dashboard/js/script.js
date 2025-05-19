// Main script
$(document).ready(function () {
    const $sidebar = $('#sidebar-wrapper');

    // Sidebar toggle for both desktop and mobile
    $('#sidebar-toggle-btn, #hamburger-btn').on('click', function () {
        $sidebar.toggleClass('closed');
    });

    // Close sidebar (used on mobile)
    $('#sidebar-close-btn').on('click', function () {
        $sidebar.addClass('closed');
    });

    // Highlight active menu item in sidebar
    $('.sidebar-nav').on('click', '.nav-link', function () {
        $('.sidebar-nav .nav-link').removeClass('active');
        $(this).addClass('active');
    });

    // Keep sidebar closed by default on load
    $sidebar.addClass('closed');

    // Handle Approve/Reject button clicks using event delegation (in case buttons are loaded dynamically)
    $(document).on('click', '.approve-btn, .reject-btn', function (e) {
        e.preventDefault(); // Stop link or button default behavior

        const $button = $(this);
        const id = $button.data('id');
        const action = $button.hasClass('approve-btn') ? 'approve' : 'reject';

        $.ajax({
            url: 'handle_action.php',
            method: 'POST',
            data: { id: id, action: action },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Remove the row on success
                    const $row = $button.closest('tr');
                    $row.fadeOut(500, function () {
                        $row.remove();

                        const $tbody = $('table.table tbody');
                        if ($tbody.children('tr').length === 0) {
                            $tbody.append(`
                                <tr>
                                    <td colspan="13" class="text-center text-muted">No data found</td>
                                </tr>
                            `);
                        }
                    });
                } else {
                    alert('Action failed: ' + response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                console.error('Server Response:', jqXHR.responseText);
                alert('An error occurred while processing the action.');
            }
        });
    });
});
