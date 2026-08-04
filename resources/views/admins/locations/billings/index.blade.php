<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment - Rent Billings Overview</title>

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
                <!-- Page Header -->
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                        Rent Billings Management
                    </h2>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                        Monthly statement overview across all apartment locations for Year <strong>{{ $selectedYear }}</strong>.
                    </p>
                </div>

                <!-- Controls & Filters Bar (Year Dropdown) -->
                <form action="{{ route('admin.billings.index') }}" method="GET" id="billingsFilterForm">
                    <div class="filter-controls-card">
                        <div class="filter-group">
                            <span class="filter-label">Select Year:</span>
                            <select name="year" class="filter-select"
                                onchange="document.getElementById('billingsFilterForm').submit()">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        Year {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Months Overview Glass Card -->
                <div class="glass-card section-card billings-card-container">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <h3 class="card-title-main" style="font-size: 1.15rem; font-weight: 800;">
                                Monthly Billings Directory ({{ $selectedYear }})
                            </h3>
                            <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">
                                Click <strong>View Tenants</strong> on any month to inspect tenant statement breakdowns
                                per room.
                            </p>
                        </div>
                        <span class="status-pill success">
                            Year {{ $selectedYear }} Active
                        </span>
                    </div>

                    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table id="monthsTable" class="display custom-table nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Billed Tenants</th>
                                    <th>Outstanding Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthsData as $data)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div
                                                    style="width: 34px; height: 34px; border-radius: 8px; background: var(--primary-light); color: var(--primary); font-weight: 800; font-size: 0.82rem; display: flex; align-items: center; justify-content: center;">
                                                    {{ strtoupper(substr($data['month'], 0, 3)) }}
                                                </div>
                                                <strong style="font-size: 0.95rem; color: #0f172a;">
                                                    {{ $data['month'] }} {{ $selectedYear }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="room-pill">
                                                {{ $data['total_tenants'] }} Tenant(s) Billed
                                            </span>
                                        </td>
                                        <td>
                                            <strong
                                                style="color: {{ $data['total_balance'] > 0 ? '#ef4444' : '#166534' }}; font-size: 0.95rem;">
                                                ₱{{ number_format($data['total_balance'], 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="status-pill {{ $data['status_class'] }}">
                                                {{ $data['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="action-btn-sm action-btn-success view-month-tenants-btn"
                                                data-month="{{ $data['month'] }}" data-year="{{ $selectedYear }}"
                                                data-tenants='@json($data['billings'])'>
                                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                View Tenants
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL 1: MONTH TENANTS LIST MODAL -->
    <div class="modal-overlay" id="monthTenantsModal">
        <div class="modal-container-xl">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="monthTenantsModalTitle">Tenants Billed</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        List of all tenants across different rooms for the selected billing month.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeMonthTenantsBtn">&times;</button>
            </div>
            <div class="modal-body">
                <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="monthTenantsTable" class="display custom-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tenant Name</th>
                                <th>Location</th>
                                <th>Room / Unit</th>
                                <th>Rent Amount</th>
                                <th>Balance</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="monthTenantsTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="closeMonthTenantsFooterBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: TENANT BILLING BREAKDOWN MODAL -->
    <div class="modal-overlay" id="billingBreakdownModal">
        <div class="modal-container-md">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Billing Breakdown</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;" id="breakdownSubtitle">
                        Detailed statement breakdown for tenant.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeBreakdownBtn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="breakdown-card">
                    <div class="breakdown-row">
                        <span class="breakdown-label">Tenant Name:</span>
                        <span class="breakdown-value" id="breakdownTenantName">-</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Assigned Location:</span>
                        <span class="breakdown-value" id="breakdownLocation">-</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Room / Unit:</span>
                        <span class="breakdown-value" id="breakdownRoom">-</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Billing Month:</span>
                        <span class="breakdown-value" id="breakdownMonth">-</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Due Date:</span>
                        <span class="breakdown-value" id="breakdownDueDate">-</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Monthly Rent Amount:</span>
                        <span class="breakdown-value" style="color: var(--primary);"
                            id="breakdownRentAmount">₱0.00</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Outstanding Balance:</span>
                        <span class="breakdown-value" id="breakdownBalance">₱0.00</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Payment Status:</span>
                        <span id="breakdownStatusPill">-</span>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <label
                        style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px; text-transform: uppercase;">
                        Proof of Billing / Receipt:
                    </label>
                    <div id="proofOfBillingContainer"
                        style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 16px; text-align: center; color: #64748b; font-size: 0.85rem;">
                        No receipt attached.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="closeBreakdownFooterBtn">Close</button>
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
            // 1. Initialize Main Months Table
            $('#monthsTable').DataTable({
                responsive: true,
                scrollX: true,
                autoWidth: false,
                bLengthChange: false,
                lengthChange: false,
                pageLength: 12,
                ordering: false,
                language: {
                    search: "Search Month:",
                    info: "Showing _START_ to _END_ of _TOTAL_ months",
                    paginate: {
                        next: "→",
                        previous: "←"
                    }
                }
            });

            let monthTenantsDataTable = null;

            // Modal Selectors
            const monthTenantsModal = document.getElementById('monthTenantsModal');
            const closeMonthTenantsBtn = document.getElementById('closeMonthTenantsBtn');
            const closeMonthTenantsFooterBtn = document.getElementById('closeMonthTenantsFooterBtn');

            const billingBreakdownModal = document.getElementById('billingBreakdownModal');
            const closeBreakdownBtn = document.getElementById('closeBreakdownBtn');
            const closeBreakdownFooterBtn = document.getElementById('closeBreakdownFooterBtn');

            function openModal(modal) {
                if (modal) modal.classList.add('active');
            }

            function closeModal(modal) {
                if (modal) modal.classList.remove('active');
            }

            if (closeMonthTenantsBtn) closeMonthTenantsBtn.addEventListener('click', () => closeModal(
                monthTenantsModal));
            if (closeMonthTenantsFooterBtn) closeMonthTenantsFooterBtn.addEventListener('click', () => closeModal(
                monthTenantsModal));

            if (closeBreakdownBtn) closeBreakdownBtn.addEventListener('click', () => closeModal(
                billingBreakdownModal));
            if (closeBreakdownFooterBtn) closeBreakdownFooterBtn.addEventListener('click', () => closeModal(
                billingBreakdownModal));

            monthTenantsModal.addEventListener('click', function(e) {
                if (e.target === monthTenantsModal) closeModal(monthTenantsModal);
            });

            billingBreakdownModal.addEventListener('click', function(e) {
                if (e.target === billingBreakdownModal) closeModal(billingBreakdownModal);
            });

            // 2. Click Event: View Month Tenants
            $(document).on('click', '.view-month-tenants-btn', function() {
                const month = $(this).data('month');
                const year = $(this).data('year');
                const billings = $(this).data('tenants') || [];

                $('#monthTenantsModalTitle').text(`Tenants Billed in ${month} ${year}`);

                // Destroy existing DataTable instance if initialized
                if ($.fn.DataTable.isDataTable('#monthTenantsTable')) {
                    $('#monthTenantsTable').DataTable().clear().destroy();
                }

                const tbody = $('#monthTenantsTableBody');
                tbody.empty();

                if (Array.isArray(billings) && billings.length > 0) {
                    billings.forEach(function(b) {
                        const tenantName = b.tenant ? b.tenant.fullname : 'N/A';
                        const initial = tenantName.charAt(0).toUpperCase();
                        const locationName = (b.tenant && b.tenant.location) ? b.tenant.location
                            .location_name : 'N/A';
                        const room = (b.tenant && b.tenant.rent_information) ? b.tenant
                            .rent_information.room : (b.room || 'N/A');
                        const rentAmount = parseFloat(b.rent_amount || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        const balance = parseFloat(b.balance || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        const dueDate = b.due_date ? b.due_date : 'N/A';
                        const status = b.status || 'Unpaid';
                        let statusClass = 'danger';
                        if (status === 'Paid') statusClass = 'success';
                        else if (status === 'Pending') statusClass = 'warning';

                        const rowHtml = `
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div class="tenant-avatar-badge">${initial}</div>
                                        <div>
                                            <strong style="font-size: 0.9rem; color: #0f172a;">${tenantName}</strong>
                                            <div style="font-size: 0.75rem; color: #64748b;">ID: #${b.tenant_id}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="location-pill">${locationName}</span>
                                </td>
                                <td>
                                    <span class="room-pill">${room}</span>
                                </td>
                                <td>
                                    <strong style="color: #0f172a;">₱${rentAmount}</strong>
                                </td>
                                <td>
                                    <strong style="color: ${parseFloat(b.balance) > 0 ? '#ef4444' : '#166534'};">₱${balance}</strong>
                                </td>
                                <td>
                                    <span style="font-size: 0.84rem; color: #475569;">${dueDate}</span>
                                </td>
                                <td>
                                    <span class="status-pill ${statusClass}">${status}</span>
                                </td>
                                <td>
                                    <button type="button" 
                                            class="action-btn-sm action-btn-info view-breakdown-btn"
                                            data-tenant="${tenantName}"
                                            data-location="${locationName}"
                                            data-room="${room}"
                                            data-month="${b.billing_month || month}"
                                            data-duedate="${dueDate}"
                                            data-rent="₱${rentAmount}"
                                            data-balance="₱${balance}"
                                            data-status="${status}"
                                            data-statusclass="${statusClass}"
                                            data-proof="${b.proof_of_billing || ''}">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        View Breakdown
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(rowHtml);
                    });
                }

                // Re-initialize DataTable on modal table cleanly
                monthTenantsDataTable = $('#monthTenantsTable').DataTable({
                    responsive: true,
                    scrollX: true,
                    autoWidth: false,
                    bLengthChange: false,
                    lengthChange: false,
                    pageLength: 5,
                    language: {
                        search: "Filter Tenants:",
                        emptyTable: `No tenant billings recorded for ${month} ${year}.`,
                        zeroRecords: `No tenant billings recorded for ${month} ${year}.`
                    }
                });

                openModal(monthTenantsModal);
            });

            // 3. Click Event: View Breakdown
            $(document).on('click', '.view-breakdown-btn', function() {
                const tenantName = $(this).data('tenant');
                const location = $(this).data('location');
                const room = $(this).data('room');
                const month = $(this).data('month');
                const dueDate = $(this).data('duedate');
                const rent = $(this).data('rent');
                const balance = $(this).data('balance');
                const status = $(this).data('status');
                const statusClass = $(this).data('statusclass');
                const proof = $(this).data('proof');

                $('#breakdownTenantName').text(tenantName);
                $('#breakdownLocation').text(location);
                $('#breakdownRoom').text(room);
                $('#breakdownMonth').text(month);
                $('#breakdownDueDate').text(dueDate);
                $('#breakdownRentAmount').text(rent);
                $('#breakdownBalance').text(balance).css('color', balance !== '₱0.00' ? '#ef4444' :
                    '#166534');
                $('#breakdownStatusPill').attr('class', `status-pill ${statusClass}`).text(status);

                if (proof && proof !== '') {
                    $('#proofOfBillingContainer').html(`
                        <a href="${proof}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: underline;">
                            View Receipt / Statement Document
                        </a>
                    `);
                } else {
                    $('#proofOfBillingContainer').html('No proof of billing uploaded.');
                }

                openModal(billingBreakdownModal);
            });

            // Left Sidebar Dropdowns & Mobile Navigation Controls
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

            // Notyf Toast Notifications
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
