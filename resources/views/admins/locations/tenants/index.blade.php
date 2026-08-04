<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment - Tenants Directory</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Primary Admin Stylesheet -->
    <link rel="stylesheet" href="{{ asset('dashboard/admins/style.css') }}">

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- DataTables CSS & Responsive Plugin CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

</head>

<body>
    <div class="dashboard-layout" id="dashboardLayout">
        <!-- Sidebar Backdrop Overlay (Mobile only) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Left Sidebar Component -->
        @include('admins.components.left_sidebar')

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Topbar Component -->
            @include('admins.components.topbar')

            <!-- Main Content panel -->
            <main class="content-panel">
                <!-- Page Banner & Header -->
                <div class="page-header-row">
                    <div>
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                            Tenants Management
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                            @if($selectedLocation)
                                Showing tenants registered under <strong>{{ $selectedLocation->location_name }}</strong>.
                            @else
                                Manage and add tenants across all registered apartment locations.
                            @endif
                        </p>
                    </div>
                    <div>
                        <button type="button" class="btn-primary-action" id="openAddTenantBtn" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            Add Tenant
                        </button>
                    </div>
                </div>

                <!-- Error Messages Box (If form validation fails) -->
                @if ($errors->any())
                    <div style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px;">
                        <strong style="font-size: 0.9rem;">Please check the form errors:</strong>
                        <ul style="margin-top: 6px; margin-left: 20px; font-size: 0.85rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Data Table Glass Card -->
                <div class="glass-card section-card tenants-card-container">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                        <h3 class="card-title-main" style="font-size: 1.15rem; font-weight: 800;">
                            Tenants Directory
                        </h3>
                        <span style="font-size: 0.82rem; color: var(--text-light); font-weight: 600;">
                            Total: {{ $tenants->count() }} Tenant(s)
                        </span>
                    </div>

                    <table id="tenantsTable" class="display custom-table responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tenant Name</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th>Room</th>
                                <th>Monthly Rental</th>
                                <th>Start Date</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenants as $tenant)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div class="tenant-avatar-badge">
                                                {{ strtoupper(substr($tenant->fullname, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong style="font-size: 0.92rem; color: #0f172a;">{{ $tenant->fullname }}</strong>
                                                <div style="font-size: 0.76rem; color: #64748b;">ID: #{{ $tenant->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-family: monospace; font-weight: 600; color: #334155;">
                                            {{ $tenant->phone_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="location-pill">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            {{ $tenant->location->location_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="room-pill">
                                            {{ $tenant->rentInformation->room ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--primary); font-size: 0.95rem;">
                                            ₱{{ number_format($tenant->rentInformation->monthly_rental ?? 0, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.85rem; font-weight: 600; color: #475569;">
                                            {{ $tenant->rentInformation->start_date ? \Carbon\Carbon::parse($tenant->rentInformation->start_date)->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.82rem; color: #64748b;">
                                            {{ $tenant->created_at ? $tenant->created_at->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- ADD TENANT MODAL -->
    <div class="modal-overlay @if($errors->any()) active @endif" id="addTenantModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Add New Tenant</h3>
                <button type="button" class="modal-close-btn" id="closeAddTenantBtn">&times;</button>
            </div>
            <form action="{{ route('admin.tenants.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                        <h4 style="font-size: 0.88rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">
                            1. Personal Details
                        </h4>
                    </div>

                    <div class="form-grid">
                        <!-- Full Name -->
                        <div class="form-group-full">
                            <label class="form-label">Full Name <span class="req">*</span></label>
                            <input type="text" name="fullname" class="form-input-custom" value="{{ old('fullname') }}" required placeholder="e.g. Juan Dela Cruz">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="form-label">Phone Number <span class="req">*</span></label>
                            <input type="text" name="phone_number" class="form-input-custom" value="{{ old('phone_number') }}" required placeholder="e.g. 09123456789">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="form-label">Password <span class="req">*</span></label>
                            <input type="password" name="password" class="form-input-custom" required placeholder="Minimum 6 characters">
                        </div>

                        <!-- Location ID -->
                        <div class="form-group-full">
                            <label class="form-label">Assigned Location <span class="req">*</span></label>
                            <select name="location_id" class="form-input-custom" required>
                                <option value="" disabled {{ old('location_id', $locationId) ? '' : 'selected' }}>Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id', $locationId) == $location->id ? 'selected' : '' }}>
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="margin-top: 24px; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                        <h4 style="font-size: 0.88rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">
                            2. Rent Information
                        </h4>
                    </div>

                    <div class="form-grid">
                        <!-- Room -->
                        <div class="form-group-full">
                            <label class="form-label">Room / Unit No. <span class="req">*</span></label>
                            <input type="text" name="room" class="form-input-custom" value="{{ old('room') }}" required placeholder="e.g. Room 101 or Unit 3B">
                        </div>

                        <!-- Monthly Rental -->
                        <div>
                            <label class="form-label">Monthly Rental (₱) <span class="req">*</span></label>
                            <input type="number" step="0.01" min="0" name="monthly_rental" class="form-input-custom" value="{{ old('monthly_rental') }}" required placeholder="e.g. 8000.00">
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="form-label">Start Date <span class="req">*</span></label>
                            <input type="date" name="start_date" class="form-input-custom" value="{{ old('start_date', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelAddTenantBtn">Cancel</button>
                    <button type="submit" class="btn-primary-action">+ Save Tenant</button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS & Responsive Plugin JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DataTables with Responsive Extension & Horizontal Scroll
            $('#tenantsTable').DataTable({
                responsive: true,
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "Search Tenant:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ tenants",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "→",
                        previous: "←"
                    }
                }
            });

            // Modal Controls
            const modalOverlay = document.getElementById('addTenantModal');
            const openModalBtn = document.getElementById('openAddTenantBtn');
            const closeModalBtn = document.getElementById('closeAddTenantBtn');
            const cancelModalBtn = document.getElementById('cancelAddTenantBtn');

            function openModal() {
                modalOverlay.classList.add('active');
            }

            function closeModal() {
                modalOverlay.classList.remove('active');
            }

            if (openModalBtn) openModalBtn.addEventListener('click', openModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside modal container
            modalOverlay.addEventListener('click', function (e) {
                if (e.target === modalOverlay) {
                    closeModal();
                }
            });

            // Left Sidebar Dropdowns & Mobile Navigation
            const menuToggleBtn = document.getElementById('menuToggleBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const dashboardLayout = document.getElementById('dashboardLayout');
            const locationsToggle = document.getElementById('locationsToggle');
            const locationsMenuDropdown = document.getElementById('locationsMenuDropdown');

            if (menuToggleBtn && dashboardLayout && sidebarOverlay) {
                menuToggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dashboardLayout.classList.toggle('sidebar-open');
                });
                sidebarOverlay.addEventListener('click', function () {
                    dashboardLayout.classList.remove('sidebar-open');
                });
            }

            if (locationsToggle && locationsMenuDropdown) {
                locationsToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    locationsMenuDropdown.classList.toggle('open');
                });
            }

            const locationSubToggles = document.querySelectorAll('.location-sub-toggle');
            locationSubToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const parentLi = this.closest('.location-sub-dropdown');
                    if (parentLi) {
                        parentLi.classList.toggle('open');
                    }
                });
            });

            // Topbar Dropdowns
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const userProfileBtn = document.getElementById('userProfileBtn');
            const userProfileDropdown = document.getElementById('userProfileDropdown');

            function toggleDropdown(btn, dropdown) {
                if (btn && dropdown) {
                    btn.addEventListener('click', function (e) {
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

            document.addEventListener('click', function (e) {
                if (notificationDropdown && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
                if (userProfileDropdown && !userProfileBtn.contains(e.target) && !userProfileDropdown.contains(e.target)) {
                    userProfileDropdown.classList.remove('show');
                }
            });

            // Notyf Toast Notifications
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
