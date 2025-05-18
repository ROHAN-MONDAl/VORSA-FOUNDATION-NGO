    $(function() {
        var $sidebar = $('#sidebar-wrapper');

        // Sidebar toggle for desktop
        $('#sidebar-toggle-btn').on('click', function() {
            $sidebar.toggleClass('closed');
        });

        // Hamburger for mobile
        $('#hamburger-btn').on('click', function() {
            $sidebar.toggleClass('closed');
        });

        // Sidebar close button for mobile
        $('#sidebar-close-btn').on('click', function() {
            $sidebar.addClass('closed');
        });

        // Sidebar menu active state
        $('.sidebar-nav .nav-link').on('click', function() {
            $('.sidebar-nav .nav-link').removeClass('active');
            $(this).addClass('active');
        });

        // Always keep sidebar closed on page load
        $sidebar.addClass('closed');

        // Remove auto sidebar open/close on resize
        // Sidebar state is now only controlled by user actions
        });
