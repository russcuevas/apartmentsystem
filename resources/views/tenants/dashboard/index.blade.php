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
    <link rel="stylesheet" href="{{ asset('dashboard/tenants/style.css') }}">

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 16px;
        }

        .detail-item {
            background-color: rgba(255, 255, 255, 0.7);
            border: 1px solid var(--border-color);
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: var(--transition);
        }

        .detail-item:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.08);
            transform: translateY(-2px);
        }

        .detail-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .detail-value {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .profile-header-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1.5px dashed rgba(13, 148, 136, 0.15);
            margin-bottom: 20px;
        }

        .profile-avatar-large {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.25);
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-layout" id="dashboardLayout">
        <!-- Sidebar Backdrop Overlay (Mobile only) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar Component -->
        @include('tenants.components.left_sidebar')

        <!-- Main Layout (Topbar + Content) -->
        <div class="main-layout">
            <!-- Topbar Component -->
            @include('tenants.components.topbar')

            <!-- Main Content panel -->
            <main class="content-panel">
                <!-- Welcome Banner -->
                <div style="margin-bottom: 28px;">
                    <h2 style="font-size: 1.65rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                        Welcome Back, {{ $tenant->fullname }}
                    </h2>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                        Here is your complete account profile and rental details for
                        <strong>{{ $tenant->location->location_name ?? 'LMS Apartment' }}</strong>.
                    </p>
                </div>

                <!-- Tenant Quick Summary Cards Grid -->
                <div class="kpi-grid">
                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Assigned Room</span>
                            <span class="kpi-value">{{ $tenant->rentInformation->room ?? 'N/A' }}</span>
                            <span class="kpi-trend positive">Room / Unit Number</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Key / Door Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Monthly Rental</span>
                            <span
                                class="kpi-value">₱{{ number_format($tenant->rentInformation->monthly_rental ?? 0, 2) }}</span>
                            <span class="kpi-trend positive">Fixed monthly rate</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Money Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Property Location</span>
                            <span class="kpi-value">{{ $tenant->location->location_name ?? 'N/A' }}</span>
                            <span class="kpi-trend positive">Apartment branch</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Map Pin Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Contract Start Date</span>
                            <span class="kpi-value">
                                {{ $tenant->rentInformation->start_date ? \Carbon\Carbon::parse($tenant->rentInformation->start_date)->format('M d, Y') : 'N/A' }}
                            </span>
                            <span class="kpi-trend neutral">Lease commencement</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Calendar Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- UI Dropdowns, Mobile Sidebar Controls JS -->
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

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notyf = new Notyf({
                duration: 4000,
                position: {
                    x: 'right',
                    y: 'top'
                },
                dismissible: true
            });

            @if (session('success'))
                notyf.success(@json(session('success')));
            @endif

            @if (session('error'))
                notyf.error(@json(session('error')));
            @endif
        });
    </script>
</body>

</html>
