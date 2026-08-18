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
                        <h2
                            style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                            Tenants Management
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                            @if ($selectedLocation)
                                Showing tenants registered under
                                <strong>{{ $selectedLocation->location_name }}</strong>.
                            @else
                                Manage and add tenants across all registered apartment locations.
                            @endif
                        </p>
                    </div>
                    <div>
                        <button type="button" class="btn-primary-action" id="openAddTenantBtn"
                            style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            Add Tenant
                        </button>
                    </div>
                </div>

                <!-- Error Messages Box (If form validation fails) -->
                @if ($errors->any())
                    <div
                        style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px;">
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
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
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
                                <th>Base Rental</th>
                                <th>Start Date</th>
                                <th>Date Added</th>
                                <th>Action</th>
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
                                                <strong
                                                    style="font-size: 0.92rem; color: #0f172a;">{{ $tenant->fullname }}</strong>
                                                <div style="font-size: 0.76rem; color: #64748b;">ID:
                                                    #{{ $tenant->id }}</div>
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
                                            <svg width="12" height="12" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
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
                                    <td>
                                        <button type="button"
                                            class="action-btn-sm action-btn-info view-tenant-details-btn"
                                            data-tenant="{{ json_encode([
                                                'id' => $tenant->id,
                                                'fullname' => $tenant->fullname,
                                                'phone_number' => $tenant->phone_number,
                                                'location_name' => $tenant->location->location_name ?? 'N/A',
                                                'room' => $tenant->rentInformation->room ?? 'N/A',
                                                'monthly_rental' => number_format($tenant->rentInformation->monthly_rental ?? 0, 2),
                                                'start_date' => $tenant->rentInformation->start_date
                                                    ? \Carbon\Carbon::parse($tenant->rentInformation->start_date)->format('M d, Y')
                                                    : 'N/A',
                                                'total_balance' => number_format($tenant->total_outstanding_balance ?? 0, 2),
                                                'raw_total_balance' => (float) ($tenant->total_outstanding_balance ?? 0),
                                                'ledger' => $tenant->ledger_data ?? [],
                                            ]) }}">
                                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            View Details
                                        </button>
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
    <div class="modal-overlay @if ($errors->any()) active @endif" id="addTenantModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Add New Tenant</h3>
                <button type="button" class="modal-close-btn" id="closeAddTenantBtn">&times;</button>
            </div>
            <form action="{{ route('admin.tenants.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                        <h4
                            style="font-size: 0.88rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">
                            1. Personal Details
                        </h4>
                    </div>

                    <div class="form-grid">
                        <!-- Full Name -->
                        <div class="form-group-full">
                            <label class="form-label">Full Name <span class="req">*</span></label>
                            <input type="text" name="fullname" class="form-input-custom"
                                value="{{ old('fullname') }}" required placeholder="e.g. Juan Dela Cruz">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="form-label">Phone Number <span class="req">*</span></label>
                            <input type="text" name="phone_number" class="form-input-custom"
                                value="{{ old('phone_number') }}" required placeholder="e.g. 09123456789">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="form-label">Password <span class="req">*</span></label>
                            <input type="password" name="password" class="form-input-custom" required
                                placeholder="Minimum 6 characters">
                        </div>

                        <!-- Location ID -->
                        <div class="form-group-full">
                            <label class="form-label">Assigned Location <span class="req">*</span></label>
                            <select name="location_id" class="form-input-custom" required>
                                <option value="" disabled
                                    {{ old('location_id', $locationId) ? '' : 'selected' }}>Select Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('location_id', $locationId) == $location->id ? 'selected' : '' }}>
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div
                        style="margin-top: 24px; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                        <h4
                            style="font-size: 0.88rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">
                            2. Rent Information
                        </h4>
                    </div>

                    <div class="form-grid">
                        <!-- Room -->
                        <div class="form-group-full">
                            <label class="form-label">Room / Unit No. <span class="req">*</span></label>
                            <input type="text" name="room" class="form-input-custom"
                                value="{{ old('room') }}" required placeholder="e.g. Room 101 or Unit 3B">
                        </div>

                        <!-- Monthly Rental -->
                        <div>
                            <label class="form-label">Monthly Rental (₱) <span class="req">*</span></label>
                            <input type="number" step="0.01" min="0" name="monthly_rental"
                                class="form-input-custom" value="{{ old('monthly_rental') }}" required
                                placeholder="e.g. 8000.00">
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="form-label">Start Date <span class="req">*</span></label>
                            <input type="date" name="start_date" class="form-input-custom"
                                value="{{ old('start_date', date('Y-m-d')) }}" required>
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

    <!-- TENANT DETAILS & STATEMENT LEDGER MODAL -->
    <div class="modal-overlay" id="tenantDetailsModal">
        <div class="modal-container-xl" style="width: 95vw; max-width: 1400px;">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="tenantDetailsModalTitle">Tenant Overall Details & Statement Ledger
                    </h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        Overall monthly statements breakdown with cumulative carried-over outstanding balance.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeTenantDetailsBtn">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Tenant Summary Header Card -->
                <div
                    style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid #cbd5e1; border-radius: 12px; padding: 18px 24px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div id="modalTenantAvatar"
                            style="width: 48px; height: 48px; border-radius: 12px; background: var(--primary-light); color: var(--primary); font-weight: 800; font-size: 1.25rem; display: flex; align-items: center; justify-content: center;">
                            -
                        </div>
                        <div>
                            <h4 id="modalTenantName"
                                style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">-</h4>
                            <div
                                style="font-size: 0.82rem; color: #64748b; margin-top: 4px; display: flex; gap: 12px; flex-wrap: wrap;">
                                <span>📞 Phone: <strong id="modalTenantPhone"
                                        style="color: #334155;">-</strong></span>
                                <span>📍 Location: <strong id="modalTenantLocation"
                                        style="color: #334155;">-</strong></span>
                                <span>🚪 Room: <strong id="modalTenantRoom" style="color: #334155;">-</strong></span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                        <div
                            style="text-align: right; background: #ffffff; padding: 10px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <span
                                style="font-size: 0.72rem; color: #64748b; font-weight: 700; text-transform: uppercase; display: block;">Base
                                Monthly Rent</span>
                            <strong id="modalTenantRent"
                                style="font-size: 1rem; color: var(--primary);">₱0.00</strong>
                        </div>
                    </div>
                </div>

                <!-- Ledger Table -->
                <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="tenantLedgerTable" class="display custom-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Billing Month</th>
                                <th>Rent & Utilities Breakdown</th>
                                <th>Total Billed</th>
                                <th>Approved Payments</th>
                                <th>Carried Over Balance</th>
                                <th>Outstanding Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tenantLedgerTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <form id="moveOutForm" action="" method="POST" style="display: none;">
                        @csrf
                        <button type="submit" class="btn-danger-action"
                            style="background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; border: none; padding: 9px 18px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25); transition: all 0.2s;"
                            onclick="return confirm('Are you sure you want to mark this tenant as Moved Out? They will no longer be listed and cannot log in.')">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Move Out Tenant
                        </button>
                    </form>
                </div>
                <button type="button" class="btn-secondary" id="closeTenantDetailsFooterBtn">Close</button>
            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
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

            function openModal(targetModal) {
                const modalToOpen = (targetModal && targetModal.classList) ? targetModal : modalOverlay;
                if (modalToOpen) modalToOpen.classList.add('active');
            }

            function closeModal(targetModal) {
                const modalToClose = (targetModal && targetModal.classList) ? targetModal : modalOverlay;
                if (modalToClose) modalToClose.classList.remove('active');
            }

            if (openModalBtn) openModalBtn.addEventListener('click', openModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside modal container
            modalOverlay.addEventListener('click', function(e) {
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
                menuToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dashboardLayout.classList.toggle('sidebar-open');
                });
                sidebarOverlay.addEventListener('click', function() {
                    dashboardLayout.classList.remove('sidebar-open');
                });
            }

            if (locationsToggle && locationsMenuDropdown) {
                locationsToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    locationsMenuDropdown.classList.toggle('open');
                });
            }

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

            // Topbar Dropdowns
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const userProfileBtn = document.getElementById('userProfileBtn');
            const userProfileDropdown = document.getElementById('userProfileDropdown');

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

            // Tenant Details Modal Logic
            let tenantLedgerDataTable = null;

            const tenantDetailsModal = document.getElementById('tenantDetailsModal');
            const closeTenantDetailsBtn = document.getElementById('closeTenantDetailsBtn');
            const closeTenantDetailsFooterBtn = document.getElementById('closeTenantDetailsFooterBtn');

            if (closeTenantDetailsBtn) closeTenantDetailsBtn.addEventListener('click', () => closeModal(
                tenantDetailsModal));
            if (closeTenantDetailsFooterBtn) closeTenantDetailsFooterBtn.addEventListener('click', () => closeModal(
                tenantDetailsModal));

            if (tenantDetailsModal) {
                tenantDetailsModal.addEventListener('click', function(e) {
                    if (e.target === tenantDetailsModal) closeModal(tenantDetailsModal);
                });
            }

            $(document).on('click', '.view-tenant-details-btn', function() {
                const data = $(this).data('tenant');
                if (!data) return;

                const initial = data.fullname ? data.fullname.charAt(0).toUpperCase() : 'T';

                $('#modalTenantAvatar').text(initial);
                $('#modalTenantName').text(data.fullname);
                $('#modalTenantPhone').text(data.phone_number);
                $('#modalTenantLocation').text(data.location_name);
                $('#modalTenantRoom').text(data.room);
                $('#modalTenantRent').text(`₱${data.monthly_rental}`);

                const rawBal = typeof data.raw_total_balance !== 'undefined'
                    ? parseFloat(data.raw_total_balance)
                    : parseFloat(String(data.total_balance).replace(/,/g, '') || 0);

                if (rawBal <= 0 && data.id) {
                    $('#moveOutForm').attr('action', `/admin/tenants/${data.id}/move-out`).show();
                } else {
                    $('#moveOutForm').hide();
                }

                $('#tenantDetailsModalTitle').text(`Overall Statement Ledger - ${data.fullname}`);

                if ($.fn.DataTable.isDataTable('#tenantLedgerTable')) {
                    $('#tenantLedgerTable').DataTable().clear().destroy();
                }

                const tbody = $('#tenantLedgerTableBody');
                tbody.empty();

                const ledger = data.ledger || [];
                if (Array.isArray(ledger) && ledger.length > 0) {
                    ledger.forEach(function(row) {
                        const rentFormatted = parseFloat(row.rent_amount || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const elecFormatted = parseFloat(row.elec_amount || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const waterFormatted = parseFloat(row.water_amount || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const totalBilledFormatted = parseFloat(row.total_billed || 0)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const totalPaidFormatted = parseFloat(row.total_paid || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const prevBalFormatted = parseFloat(row.previous_balance || 0)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const cumBalFormatted = parseFloat(row.cumulative_balance || 0)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const prevRentFormatted = parseFloat(row.prev_rent_bal || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const prevElecFormatted = parseFloat(row.prev_elec_bal || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const prevWaterFormatted = parseFloat(row.prev_water_bal || 0)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                        let prevBalHtml =
                            `<strong style="color: ${parseFloat(row.previous_balance) > 0 ? '#b91c1c' : '#64748b'};">₱${prevBalFormatted}</strong>`;
                        if (parseFloat(row.previous_balance) > 0) {
                            prevBalHtml += `
                                <div style="font-size: 0.76rem; color: #475569; margin-top: 4px; line-height: 1.35; background: #fef2f2; padding: 4px 8px; border-radius: 6px; border: 1px solid #fecaca;">
                                    <div>🏠 Rent: ₱${prevRentFormatted}</div>
                                    <div>⚡ Elec: ₱${prevElecFormatted}</div>
                                    <div>💧 Water: ₱${prevWaterFormatted}</div>
                                </div>
                            `;
                        }

                        const cumRentFormatted = parseFloat(row.cum_rent_bal || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const cumElecFormatted = parseFloat(row.cum_elec_bal || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        const cumWaterFormatted = parseFloat(row.cum_water_bal || 0).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                        let cumBalHtml =
                            `<strong style="color: ${parseFloat(row.cumulative_balance) > 0 ? '#ef4444' : '#166534'}; font-size: 0.95rem;">₱${cumBalFormatted}</strong>`;
                        if (parseFloat(row.cumulative_balance) > 0) {
                            cumBalHtml += `
                                <div style="font-size: 0.76rem; color: #475569; margin-top: 4px; line-height: 1.35; background: #fff5f5; padding: 4px 8px; border-radius: 6px; border: 1px solid #fed7d7;">
                                    <div>🏠 Rent: ₱${cumRentFormatted}</div>
                                    <div>⚡ Elec: ₱${cumElecFormatted}</div>
                                    <div>💧 Water: ₱${cumWaterFormatted}</div>
                                </div>
                            `;
                        }

                        const rowHtml = `
                            <tr>
                                <td>
                                    <strong style="font-size: 0.92rem; color: #0f172a;">${row.month} ${row.year || ''}</strong>
                                </td>
                                <td>
                                    <div style="font-size: 0.82rem; line-height: 1.45;">
                                        <div><span style="color: #64748b; font-weight: 600;">Rent:</span> <strong style="color: #0f172a;">₱${rentFormatted}</strong></div>
                                        <div><span style="color: #0284c7; font-weight: 600;">⚡ Elec:</span> <strong style="color: #0f172a;">₱${elecFormatted}</strong></div>
                                        <div><span style="color: #0ea5e9; font-weight: 600;">💧 Water:</span> <strong style="color: #0f172a;">₱${waterFormatted}</strong></div>
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #0f172a;">₱${totalBilledFormatted}</strong>
                                </td>
                                <td>
                                    <strong style="color: #166534;">₱${totalPaidFormatted}</strong>
                                </td>
                                <td>
                                    ${prevBalHtml}
                                </td>
                                <td>
                                    ${cumBalHtml}
                                </td>
                                <td>
                                    <span class="status-pill ${row.status_class}">${row.status}</span>
                                </td>
                            </tr>
                        `;
                        tbody.append(rowHtml);
                    });
                }

                tenantLedgerDataTable = $('#tenantLedgerTable').DataTable({
                    responsive: true,
                    scrollX: true,
                    autoWidth: false,
                    bLengthChange: false,
                    lengthChange: false,
                    pageLength: 12,
                    ordering: false,
                    language: {
                        search: "Filter Ledger:",
                        emptyTable: "No billing records found for this tenant.",
                        zeroRecords: "No matching ledger records found."
                    }
                });

                openModal(tenantDetailsModal);
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
