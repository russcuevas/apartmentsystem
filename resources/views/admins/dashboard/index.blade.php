<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment</title>

    <!-- Google Fonts: Plus Jakarta Sans for premium typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('dashboard/admins/style.css') }}">
</head>

<body>
    <div class="dashboard-layout" id="dashboardLayout">
        <!-- Sidebar Backdrop Overlay (Mobile only) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar Component -->
        @include('admins.components.left_sidebar')

        <!-- Main Layout (Topbar + Content) -->
        <div class="main-layout">
            <!-- Topbar Component -->
            @include('admins.components.topbar')

            <!-- Main Content panel -->
            <main class="content-panel">
                <!-- Welcome Banner -->
                <div style="margin-bottom: 32px;">
                    <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                        Welcome Back, Administrator</h2>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">Monitor tenant status,
                        track billing cycles, and review payments across all properties.</p>
                </div>

            </main>
        </div>
    </div>

    <!-- UI Dropdowns, Mobile Sidebar Responsive Controls JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdowns Setup
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const userProfileBtn = document.getElementById('userProfileBtn');
            const userProfileDropdown = document.getElementById('userProfileDropdown');
            const locationsToggle = document.getElementById('locationsToggle');
            const locationsMenuDropdown = document.getElementById('locationsMenuDropdown');

            // Responsive Sidebar Selectors
            const menuToggleBtn = document.getElementById('menuToggleBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const dashboardLayout = document.getElementById('dashboardLayout');

            // 1. Toggle Sidebar on Mobile
            if (menuToggleBtn && dashboardLayout && sidebarOverlay) {
                menuToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dashboardLayout.classList.toggle('sidebar-open');
                });

                sidebarOverlay.addEventListener('click', function() {
                    dashboardLayout.classList.remove('sidebar-open');
                });
            }

            // 2. Toggle Left Sidebar Dropdown Locations Menu
            if (locationsToggle && locationsMenuDropdown) {
                locationsToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    locationsMenuDropdown.classList.toggle('open');
                });
            }

            // Helper to toggle active visibility of target dropdowns
            function toggleDropdown(btn, dropdown) {
                if (btn && dropdown) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        // Close the other dropdown if open
                        if (dropdown === notificationDropdown && userProfileDropdown) {
                            userProfileDropdown.classList.remove('show');
                        } else if (dropdown === userProfileDropdown && notificationDropdown) {
                            notificationDropdown.classList.remove('show');
                        }
                        dropdown.classList.toggle('show');
                    });
                }
            }

            toggleDropdown(notificationBtn, notificationDropdown);
            toggleDropdown(userProfileBtn, userProfileDropdown);

            // 3. Close menus when clicking outside
            document.addEventListener('click', function(e) {
                if (notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown
                    .contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
                if (userProfileDropdown && !userProfileBtn.contains(e.target) && !userProfileDropdown
                    .contains(e.target)) {
                    userProfileDropdown.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>
