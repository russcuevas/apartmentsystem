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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Select2 Custom Modal Overrides */
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #f8fafc !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            line-height: 28px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        .select2-dropdown {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            z-index: 10000 !important;
        }
    </style>
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
                        @if ($selectedLocation)
                            Monthly statement overview for <strong>{{ $selectedLocation->location_name }}</strong>
                            location (Year <strong>{{ $selectedYear }}</strong>).
                        @else
                            Monthly statement overview across all apartment locations for Year
                            <strong>{{ $selectedYear }}</strong>.
                        @endif
                    </p>
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

                <!-- Controls & Filters Bar (Year Dropdown) -->
                <form action="{{ route('admin.billings.index') }}" method="GET" id="billingsFilterForm">
                    @if ($selectedLocationId)
                        <input type="hidden" name="location_id" value="{{ $selectedLocationId }}">
                    @endif
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
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <button type="button" class="btn-primary-action" id="openAddBillingBtn"
                                style="display: inline-flex; align-items: center; gap: 8px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15">
                                    </path>
                                </svg>
                                Add Billing
                            </button>
                        </div>
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

    <!-- MODAL 0: ADD RENT BILLING MODAL -->
    <div class="modal-overlay @if ($errors->any()) active @endif" id="addBillingModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Add Rent Billing</h3>
                <button type="button" class="modal-close-btn" id="closeAddBillingBtn">&times;</button>
            </div>
            <form action="{{ route('admin.billings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Always submit status as Unpaid -->
                <input type="hidden" name="status" value="Unpaid">

                <div class="modal-body">
                    <div class="form-grid">
                        <!-- Select Tenant (Select2) -->
                        <div class="form-group-full">
                            <label class="form-label">Select Tenant Full Name <span class="req">*</span></label>
                            <select name="tenant_id" id="tenantSelect" class="form-input-custom select2-dropdown"
                                required style="width: 100%;">
                                <option value="" disabled selected>-- Select Tenant --</option>
                                @foreach ($allTenants as $t)
                                    <option value="{{ $t->id }}"
                                        data-rent="{{ $t->rentInformation->monthly_rental ?? 0 }}"
                                        data-startdate="{{ !empty($t->rentInformation->start_date) ? \Carbon\Carbon::parse($t->rentInformation->start_date)->format('M d, Y') : '' }}">
                                        {{ $t->fullname }} - {{ $t->location->location_name ?? 'N/A' }}
                                        ({{ $t->rentInformation->room ?? 'No Room' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Billing Month -->
                        <div>
                            <label class="form-label">Billing Month <span class="req">*</span></label>
                            <select name="billing_month" class="form-input-custom" required>
                                @foreach ($allMonths as $m)
                                    <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="tenantStartDateInfo"
                                style="display: none; margin-top: 8px; padding: 8px 12px; border-radius: 8px; background-color: #fef3c7; border: 1px solid #fde68a; color: #92400e; font-size: 0.82rem; font-weight: 700;">
                                📅 Lease Start Date: <span id="tenantStartDateVal"
                                    style="color: #78350f; text-decoration: underline;">-</span>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="form-label">Due Date <span class="req">*</span></label>
                            <input type="date" name="due_date" class="form-input-custom"
                                value="{{ old('due_date', date('Y-m-15')) }}" required>
                        </div>

                        <!-- Rent Amount (Auto-filled on tenant selection) -->
                        <div class="form-group-full">
                            <label class="form-label">Rent Amount (₱) <span class="req">*</span></label>
                            <input type="number" step="0.01" min="0" name="rent_amount"
                                id="rentAmountInput" class="form-input-custom" value="{{ old('rent_amount') }}"
                                placeholder="0.00" required readonly style="background-color: gainsboro !important ">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelAddBillingBtn">Cancel</button>
                    <button type="submit" class="btn-primary-action">+ Save Billing</button>
                </div>
            </form>
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
                                <th>Outstanding Balance</th>
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
                        <span class="breakdown-label">Monthly Rent:</span>
                        <span class="breakdown-value" id="breakdownRentRow">₱0.00</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Electricity Bill:</span>
                        <span class="breakdown-value" id="breakdownElecRow">₱0.00</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Water Bill:</span>
                        <span class="breakdown-value" id="breakdownWaterRow">₱0.00</span>
                    </div>
                    <div class="breakdown-row" style="border-top: 2px solid #e2e8f0; margin-top: 8px; padding-top: 10px;">
                        <span class="breakdown-label" style="font-weight: 800; color: #0f172a;">Total Amount Billed:</span>
                        <span class="breakdown-value" style="color: var(--primary); font-size: 1rem;" id="breakdownTotalAmount">₱0.00</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label" style="font-weight: 800; color: #0f172a;">Total Outstanding Balance:</span>
                        <span class="breakdown-value" style="font-size: 1rem;" id="breakdownBalance">₱0.00</span>
                    </div>
                    <div class="breakdown-row">
                        <span class="breakdown-label">Overall Payment Status:</span>
                        <span id="breakdownStatusPill">-</span>
                    </div>
                </div>

                <div style="margin-top: 18px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px; text-transform: uppercase;">
                            Electricity Bill Document:
                        </label>
                        <div id="elecProofContainer" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px; text-align: center; color: #64748b; font-size: 0.82rem;">
                            No document attached.
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px; text-transform: uppercase;">
                            Water Bill Document:
                        </label>
                        <div id="waterProofContainer" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px; text-align: center; color: #64748b; font-size: 0.82rem;">
                            No document attached.
                        </div>
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

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables JS & Responsive Plugin JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 dropdown
            $('.select2-dropdown').select2({
                dropdownParent: $('#addBillingModal'),
                placeholder: "-- Select Tenant --",
                width: '100%'
            });

            // Auto-fill Rent Amount & Display Lease Start Date when selecting a tenant
            $('#tenantSelect').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const rent = selectedOption.data('rent');
                const startDate = selectedOption.data('startdate');

                if (rent !== undefined && rent !== null) {
                    $('#rentAmountInput').val(parseFloat(rent).toFixed(2));
                }

                if (startDate && startDate !== '') {
                    $('#tenantStartDateVal').text(startDate);
                    $('#tenantStartDateInfo').slideDown(150);
                } else {
                    $('#tenantStartDateInfo').slideUp(150);
                }
            });

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
            const addBillingModal = document.getElementById('addBillingModal');
            const openAddBillingBtn = document.getElementById('openAddBillingBtn');
            const closeAddBillingBtn = document.getElementById('closeAddBillingBtn');
            const cancelAddBillingBtn = document.getElementById('cancelAddBillingBtn');

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

            if (openAddBillingBtn) openAddBillingBtn.addEventListener('click', () => openModal(addBillingModal));
            if (closeAddBillingBtn) closeAddBillingBtn.addEventListener('click', () => closeModal(addBillingModal));
            if (cancelAddBillingBtn) cancelAddBillingBtn.addEventListener('click', () => closeModal(
                addBillingModal));

            if (closeMonthTenantsBtn) closeMonthTenantsBtn.addEventListener('click', () => closeModal(
                monthTenantsModal));
            if (closeMonthTenantsFooterBtn) closeMonthTenantsFooterBtn.addEventListener('click', () => closeModal(
                monthTenantsModal));

            if (closeBreakdownBtn) closeBreakdownBtn.addEventListener('click', () => closeModal(
                billingBreakdownModal));
            if (closeBreakdownFooterBtn) closeBreakdownFooterBtn.addEventListener('click', () => closeModal(
                billingBreakdownModal));

            if (addBillingModal) {
                addBillingModal.addEventListener('click', function(e) {
                    if (e.target === addBillingModal) closeModal(addBillingModal);
                });
            }

            if (monthTenantsModal) {
                monthTenantsModal.addEventListener('click', function(e) {
                    if (e.target === monthTenantsModal) closeModal(monthTenantsModal);
                });
            }

            if (billingBreakdownModal) {
                billingBreakdownModal.addEventListener('click', function(e) {
                    if (e.target === billingBreakdownModal) closeModal(billingBreakdownModal);
                });
            }

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
                        const rentBalance = parseFloat(b.rent_balance || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const elecAmount = parseFloat(b.elec_amount || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        const elecBalance = parseFloat(b.elec_balance || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const waterAmount = parseFloat(b.water_amount || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        const waterBalance = parseFloat(b.water_balance || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const totalAmount = parseFloat(b.total_amount || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        const totalBalance = parseFloat(b.total_balance || 0).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const status = b.status || 'Unpaid';
                        let statusClass = 'danger';
                        if (status === 'Paid') statusClass = 'success';
                        else if (status === 'Partial') statusClass = 'warning';

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
                                    <strong style="color: ${parseFloat(b.total_balance) > 0 ? '#ef4444' : '#166534'};">₱${totalBalance}</strong>
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
                                            data-hasrent="${b.has_rent ? 1 : 0}"
                                            data-rent="₱${rentAmount}"
                                            data-rentbal="₱${rentBalance}"
                                            data-rentstatus="${b.rent_status || 'Unpaid'}"
                                            data-haselec="${b.has_elec ? 1 : 0}"
                                            data-elec="₱${elecAmount}"
                                            data-elecbal="₱${elecBalance}"
                                            data-elecstatus="${b.elec_status || 'Unpaid'}"
                                            data-haswater="${b.has_water ? 1 : 0}"
                                            data-water="₱${waterAmount}"
                                            data-waterbal="₱${waterBalance}"
                                            data-waterstatus="${b.water_status || 'Unpaid'}"
                                            data-totalamount="₱${totalAmount}"
                                            data-balance="₱${totalBalance}"
                                            data-status="${status}"
                                            data-statusclass="${statusClass}"
                                            data-elecproof="${b.elec_proof || ''}"
                                            data-waterproof="${b.water_proof || ''}">
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

                const hasRent = parseInt($(this).data('hasrent')) === 1;
                const rent = $(this).data('rent');
                const rentBal = $(this).data('rentbal');
                const rentStatus = $(this).data('rentstatus');

                const hasElec = parseInt($(this).data('haselec')) === 1;
                const elec = $(this).data('elec');
                const elecBal = $(this).data('elecbal');
                const elecStatus = $(this).data('elecstatus');

                const hasWater = parseInt($(this).data('haswater')) === 1;
                const water = $(this).data('water');
                const waterBal = $(this).data('waterbal');
                const waterStatus = $(this).data('waterstatus');

                const totalAmount = $(this).data('totalamount');
                const balance = $(this).data('balance');
                const status = $(this).data('status');
                const statusClass = $(this).data('statusclass');
                const elecProof = $(this).data('elecproof');
                const waterProof = $(this).data('waterproof');

                $('#breakdownTenantName').text(tenantName);
                $('#breakdownLocation').text(location);
                $('#breakdownRoom').text(room);
                $('#breakdownMonth').text(month);

                if (hasRent && rentStatus !== 'Pending') {
                    $('#breakdownRentRow').html(`${rent} <span style="font-size: 0.75rem; color: #64748b; margin-left: 6px;">(Bal: ${rentBal} - <strong>${rentStatus}</strong>)</span>`);
                } else {
                    $('#breakdownRentRow').html('<span style="color: #64748b; font-style: italic; font-weight: 600; font-size: 0.85rem;">No upload by the tenant</span>');
                }

                if (hasElec && elecStatus !== 'Pending') {
                    $('#breakdownElecRow').html(`${elec} <span style="font-size: 0.75rem; color: #64748b; margin-left: 6px;">(Bal: ${elecBal} - <strong>${elecStatus}</strong>)</span>`);
                } else {
                    $('#breakdownElecRow').html('<span style="color: #64748b; font-style: italic; font-weight: 600; font-size: 0.85rem;">No upload by the tenant</span>');
                }

                if (hasWater && waterStatus !== 'Pending') {
                    $('#breakdownWaterRow').html(`${water} <span style="font-size: 0.75rem; color: #64748b; margin-left: 6px;">(Bal: ${waterBal} - <strong>${waterStatus}</strong>)</span>`);
                } else {
                    $('#breakdownWaterRow').html('<span style="color: #64748b; font-style: italic; font-weight: 600; font-size: 0.85rem;">No upload by the tenant</span>');
                }

                $('#breakdownTotalAmount').text(totalAmount);
                $('#breakdownBalance').text(balance).css('color', balance !== '₱0.00' ? '#ef4444' : '#166534');
                $('#breakdownStatusPill').attr('class', `status-pill ${statusClass}`).text(status);

                if (hasElec && elecStatus !== 'Pending' && elecProof && elecProof !== '') {
                    $('#elecProofContainer').html(`
                        <a href="${elecProof}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: underline;">
                            View Electricity Bill
                        </a>
                    `);
                } else {
                    $('#elecProofContainer').html('<span style="color: #64748b; font-style: italic;">No document attached</span>');
                }

                if (hasWater && waterStatus !== 'Pending' && waterProof && waterProof !== '') {
                    $('#waterProofContainer').html(`
                        <a href="${waterProof}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: underline;">
                            View Water Bill
                        </a>
                    `);
                } else {
                    $('#waterProofContainer').html('<span style="color: #64748b; font-style: italic;">No document attached</span>');
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

            // Topbar Dropdowns (User Profile & Notifications)
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
                if (notificationDropdown && notificationBtn && !notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
                if (userProfileDropdown && userProfileBtn && !userProfileBtn.contains(e.target) && !userProfileDropdown.contains(e.target)) {
                    userProfileDropdown.classList.remove('show');
                }
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
