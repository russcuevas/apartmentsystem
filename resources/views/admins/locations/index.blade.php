<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment - {{ $location->location_name }} Dashboard</title>

    <!-- Google Fonts: Plus Jakarta Sans for premium typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('dashboard/admins/style.css') }}">

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        .tab-btn-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .tab-anchor-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-muted);
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .tab-anchor-btn:hover,
        .tab-anchor-btn.active {
            background-color: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
        }

        .section-card {
            margin-bottom: 32px;
        }

        .receipt-thumbnail {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid var(--border-color);
            background-color: #f1f5f9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .action-btn-sm {
            padding: 5px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn-success {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .action-btn-success:hover {
            background-color: var(--primary);
            color: #ffffff;
        }

        .action-btn-danger {
            background-color: var(--error-bg);
            color: var(--error);
        }

        .action-btn-danger:hover {
            background-color: var(--error);
            color: #ffffff;
        }
    </style>
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
                <!-- Location Banner & Header -->
                <div style="margin-bottom: 28px;">
                    <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                        {{ $location->location_name }} Location Dashboard
                    </h2>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                        Manage Rent, Electricity, Water billings and verifications for <strong>{{ $location->location_name }}</strong>.
                    </p>
                </div>

                <!-- Location Overview KPI Grid -->
                <div class="kpi-grid">
                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Rent Billings</span>
                            <span class="kpi-value">₱24,500.00</span>
                            <span class="kpi-trend positive">Rent statement balance</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- House / Rent Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Electric Billings</span>
                            <span class="kpi-value">₱6,840.50</span>
                            <span class="kpi-trend positive">Electric utility balance</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Lightning / Electricity Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Water Billings</span>
                            <span class="kpi-value">₱2,120.00</span>
                            <span class="kpi-trend positive">Water utility balance</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Water Drop Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a8 8 0 11-14.856 0A8 8 0 0119.428 15.428zM12 3v9"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="glass-card kpi-card">
                        <div class="kpi-content">
                            <span class="kpi-title">Payments Pending</span>
                            <span class="kpi-value">2 Receipts</span>
                            <span class="kpi-trend neutral">Awaiting verification</span>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <!-- Credit Card Icon -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Location Navigation Anchor Buttons -->
                <div class="tab-btn-group">
                    <a href="#rent-billings" class="tab-anchor-btn active">
                        <!-- Rent Icon -->
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10"></path>
                        </svg>
                        Rent Billings
                    </a>
                    <a href="#electricity-billings" class="tab-anchor-btn">
                        <!-- Electricity Icon -->
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Electricity Billings
                    </a>
                    <a href="#water-billings" class="tab-anchor-btn">
                        <!-- Water Icon -->
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"></path>
                        </svg>
                        Water Billings
                    </a>
                    <a href="#tenant-payments" class="tab-anchor-btn">
                        <!-- Payments Icon -->
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Payments & Verification
                    </a>
                </div>

                <!-- 1. Rent Billings Section (tenant_billings_rent) -->
                <div class="glass-card section-card" id="rent-billings">
                    <div class="card-header-row">
                        <div>
                            <h3 class="card-title-main">Tenant Rent Billings</h3>
                            <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">Table reference: <code>tenant_billings_rent</code></p>
                        </div>
                        <button class="card-action-btn" type="button">+ Issue Rent Bill</button>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Billing Month</th>
                                    <th>Due Date</th>
                                    <th>Rent Amount</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="location-row-details">
                                            <div class="location-avatar">RV</div>
                                            <span>Russel Vincent</span>
                                        </div>
                                    </td>
                                    <td>Dec 2026</td>
                                    <td>2026-12-15</td>
                                    <td>₱8,000.00</td>
                                    <td>₱0.00</td>
                                    <td><span class="status-pill success">Paid</span></td>
                                    <td>
                                        <button class="action-btn-sm action-btn-success" type="button">Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="location-row-details">
                                            <div class="location-avatar">JD</div>
                                            <span>John Doe</span>
                                        </div>
                                    </td>
                                    <td>Dec 2026</td>
                                    <td>2026-12-20</td>
                                    <td>₱8,500.00</td>
                                    <td>₱8,500.00</td>
                                    <td><span class="status-pill warning">Pending</span></td>
                                    <td>
                                        <button class="action-btn-sm action-btn-success" type="button">Record Pay</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Electricity Billings Section (tenant_billings_electricity) -->
                <div class="glass-card section-card" id="electricity-billings">
                    <div class="card-header-row">
                        <div>
                            <h3 class="card-title-main">Tenant Electricity Billings</h3>
                            <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">Table reference: <code>tenant_billings_electricity</code></p>
                        </div>
                        <button class="card-action-btn" type="button">+ Upload Electric Bill</button>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Billing Month</th>
                                    <th>Due Date</th>
                                    <th>Electric Amount</th>
                                    <th>Balance</th>
                                    <th>Receipt / Statement</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="location-row-details">
                                            <div class="location-avatar">RV</div>
                                            <span>Russel Vincent</span>
                                        </div>
                                    </td>
                                    <td>Dec 2026</td>
                                    <td>2026-12-18</td>
                                    <td>₱2,450.50</td>
                                    <td>₱1,000.00</td>
                                    <td>
                                        <div class="receipt-thumbnail">
                                            <!-- Bill Icon -->
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td><span class="status-pill warning">Partial</span></td>
                                    <td>
                                        <button class="action-btn-sm action-btn-success" type="button">View Receipt</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Water Billings Section (tenant_billings_water) -->
                <div class="glass-card section-card" id="water-billings">
                    <div class="card-header-row">
                        <div>
                            <h3 class="card-title-main">Tenant Water Billings</h3>
                            <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">Table reference: <code>tenant_billings_water</code></p>
                        </div>
                        <button class="card-action-btn" type="button">+ Upload Water Bill</button>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Billing Month</th>
                                    <th>Due Date</th>
                                    <th>Water Amount</th>
                                    <th>Balance</th>
                                    <th>Receipt / Statement</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="location-row-details">
                                            <div class="location-avatar">RV</div>
                                            <span>Russel Vincent</span>
                                        </div>
                                    </td>
                                    <td>Dec 2026</td>
                                    <td>2026-12-18</td>
                                    <td>₱650.00</td>
                                    <td>₱0.00</td>
                                    <td>
                                        <div class="receipt-thumbnail">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td><span class="status-pill success">Paid</span></td>
                                    <td>
                                        <button class="action-btn-sm action-btn-success" type="button">View Receipt</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Tenant Payments & Verification Section (tenant_payments) -->
                <div class="glass-card section-card" id="tenant-payments">
                    <div class="card-header-row">
                        <div>
                            <h3 class="card-title-main">Tenant Payments Verification</h3>
                            <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">Table reference: <code>tenant_payments</code></p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Tenant & Month</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Proof</th>
                                    <th>Status</th>
                                    <th>Received By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="location-row-details">
                                            <div class="location-avatar">RV</div>
                                            <div>
                                                <strong>Russel Vincent</strong>
                                                <div style="font-size: 0.75rem; color: var(--text-light);">Dec 2026</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-pill success">Rent</span></td>
                                    <td><strong>₱8,000.00</strong></td>
                                    <td><span class="status-pill warning">ECASH</span></td>
                                    <td>
                                        <div class="receipt-thumbnail" title="Proof of Payment Uploaded">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td><span class="status-pill warning">Pending</span></td>
                                    <td>-</td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <button class="action-btn-sm action-btn-success" type="button">Accept</button>
                                            <button class="action-btn-sm action-btn-danger" type="button">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="location-row-details">
                                            <div class="location-avatar">RV</div>
                                            <div>
                                                <strong>Russel Vincent</strong>
                                                <div style="font-size: 0.75rem; color: var(--text-light);">Dec 2026</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-pill success">Electric</span></td>
                                    <td><strong>₱1,450.50</strong></td>
                                    <td><span class="status-pill success">CASH</span></td>
                                    <td>-</td>
                                    <td><span class="status-pill success">Accepted</span></td>
                                    <td>System Admin</td>
                                    <td>
                                        <span style="font-size: 0.78rem; color: var(--text-light);">Verified</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- UI Dropdowns, Mobile Sidebar Controls & Tab Switching JS -->
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

            // Toggle Sub-location Nested Leaf Menus
            const locationSubToggles = document.querySelectorAll('.location-sub-toggle');
            locationSubToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parentLi = this.closest('.location-sub-dropdown');
                    if (parentLi) {
                        parentLi.classList.toggle('open');
                    }
                });
            });

            // Helper to toggle active visibility of target dropdowns
            function toggleDropdown(btn, dropdown) {
                if (btn && dropdown) {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
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
                if (notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
                if (userProfileDropdown && !userProfileBtn.contains(e.target) && !userProfileDropdown.contains(e.target)) {
                    userProfileDropdown.classList.remove('show');
                }
            });

            // 4. Tab Anchor Active State Control
            const tabButtons = document.querySelectorAll('.tab-anchor-btn');
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notyf = new Notyf({
                duration: 4000,
                position: { x: 'right', y: 'top' },
                dismissible: true
            });

            @if(session('success'))
                notyf.success(@json(session('success')));
            @endif

            @if(session('error'))
                notyf.error(@json(session('error')));
            @endif
        });
    </script>
</body>

</html>
