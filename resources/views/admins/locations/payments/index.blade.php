<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment - Payments Verification</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Admin Stylesheet -->
    <link rel="stylesheet" href="{{ asset('dashboard/admins/style.css') }}">

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- DataTables CSS & Responsive Plugin CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        .filter-controls-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: var(--shadow-sm);
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .filter-select {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1.5px solid var(--border-color);
            background: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            outline: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
        }

        .custom-table-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            box-shadow: var(--shadow-sm);
        }

        /* Modal Overlay & Container Styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease-in-out;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-container-xl {
            background: #ffffff;
            border-radius: 16px;
            width: 95vw;
            max-width: 1450px;
            max-height: 92vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            transform: translateY(20px);
            transition: transform 0.25s ease-in-out;
            overflow: hidden;
        }

        .modal-overlay.active .modal-container-xl {
            transform: translateY(0);
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }

        .modal-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            line-height: 1;
            padding: 4px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .modal-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
            font-weight: 700;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .badge-type {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-cash {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-ecash {
            background: #f0fdf4;
            color: #15803d;
        }

        .view-month-payments-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 700;
            color: #ffffff !important;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .view-month-payments-btn:hover {
            background: linear-gradient(135deg, #0f766e, #115e59);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(13, 148, 136, 0.35);
            color: #ffffff !important;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: capitalize;
        }

        .status-pill.success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .status-pill.warning {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .status-pill.danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .status-pill.neutral {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .room-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid #e2e8f0;
        }

        /* Action Form Buttons */
        .btn-approve {
            background-color: #16a34a;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-approve:hover {
            background-color: #15803d;
        }

        .btn-decline {
            background-color: #dc2626;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-decline:hover {
            background-color: #b91c1c;
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
                        Tenant Payments Verification
                    </h2>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                        @if ($selectedLocation)
                            Review and verify tenant payments for
                            <strong>{{ $selectedLocation->location_name }}</strong> (Year
                            <strong>{{ $selectedYear }}</strong>).
                        @else
                            Review and verify tenant payments across all locations for Year
                            <strong>{{ $selectedYear }}</strong>.
                        @endif
                    </p>
                </div>

                <!-- Error & Success Messages -->
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

                <!-- Filter Controls (Location & Year Dropdowns) -->
                <form action="{{ route('admin.payments.index') }}" method="GET" id="adminPaymentsFilterForm">
                    <div class="filter-controls-card">
                        <div class="filter-group">
                            <span class="filter-label">Filter Location:</span>
                            <select name="location_id" class="filter-select"
                                onchange="document.getElementById('adminPaymentsFilterForm').submit()">
                                <option value="">-- All Locations --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}"
                                        {{ $selectedLocationId == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->location_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <span class="filter-label">Select Year:</span>
                            <select name="year" class="filter-select"
                                onchange="document.getElementById('adminPaymentsFilterForm').submit()">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        Year {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Months Directory Datatable Card -->
                <div class="custom-table-card">
                    <div style="margin-bottom: 18px;">
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin: 0;">
                            Monthly Payments Overview ({{ $selectedYear }})
                        </h3>
                        <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">
                            Click <strong>View Payments</strong> on any month to inspect submitted receipts, verify
                            transfer proof, and approve or decline payments.
                        </p>
                    </div>

                    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table id="adminMonthsTable" class="display custom-table nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Payments Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthsData as $data)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div
                                                    style="width: 36px; height: 36px; border-radius: 8px; background: var(--primary-light); color: var(--primary); font-weight: 800; font-size: 0.82rem; display: flex; align-items: center; justify-content: center;">
                                                    {{ strtoupper(substr($data['month'], 0, 3)) }}
                                                </div>
                                                <strong style="font-size: 0.95rem; color: #0f172a;">
                                                    {{ $data['month'] }} {{ $selectedYear }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="room-pill">
                                                {{ $data['total_count'] }} Payment Submission(s)
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="view-month-payments-btn"
                                                data-month="{{ $data['month'] }}" data-year="{{ $selectedYear }}"
                                                data-payments='@json($data['payments'])'>
                                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                View Payments
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

    <!-- MODAL: MONTH PAYMENTS VERIFICATION MODAL -->
    <div class="modal-overlay" id="monthPaymentsModal">
        <div class="modal-container-xl">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="monthPaymentsModalTitle">Payment Verification List</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        Detailed list of submitted tenant payments for approval or decline.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeMonthPaymentsBtn">&times;</button>
            </div>
            <div class="modal-body">
                <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="monthPaymentsTable" class="display custom-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tenant Details</th>
                                <th>Payment Breakdown & Verification</th>
                            </tr>
                        </thead>
                        <tbody id="monthPaymentsTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="closeMonthPaymentsFooterBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL: IMAGE VIEWER MODAL -->
    <div class="modal-overlay" id="imageViewerModal" style="z-index: 10050;">
        <div class="modal-container-lg"
            style="max-width: 800px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
            <div class="modal-header"
                style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h3 class="modal-title" id="imageViewerCaption" style="color: #ffffff;">Image Preview</h3>
                <button type="button" class="modal-close-btn" id="closeImageViewerBtn"
                    style="color: #ffffff;">&times;</button>
            </div>
            <div class="modal-body"
                style="display: flex; align-items: center; justify-content: center; padding: 20px; text-align: center; overflow: hidden;">
                <img src="" id="fullImageViewerSrc"
                    style="max-width: 100%; max-height: 75vh; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); object-fit: contain;">
            </div>
            <div class="modal-footer" style="background: transparent; border-top: 1px solid rgba(255,255,255,0.1);">
                <button type="button" class="btn-secondary" id="closeImageViewerFooterBtn">Close</button>
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
            // Notyf Toast Setup
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

            // Sidebar & Navigation Dropdowns Setup
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

            // 1. Initialize Main Months Datatable
            $('#adminMonthsTable').DataTable({
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

            let monthPaymentsDataTable = null;

            // Modal Controls
            const monthPaymentsModal = document.getElementById('monthPaymentsModal');
            const closeMonthPaymentsBtn = document.getElementById('closeMonthPaymentsBtn');
            const closeMonthPaymentsFooterBtn = document.getElementById('closeMonthPaymentsFooterBtn');

            const imageViewerModal = document.getElementById('imageViewerModal');
            const closeImageViewerBtn = document.getElementById('closeImageViewerBtn');
            const closeImageViewerFooterBtn = document.getElementById('closeImageViewerFooterBtn');

            function openModal(modal) {
                if (modal) modal.classList.add('active');
            }

            function closeModal(modal) {
                if (modal) modal.classList.remove('active');
            }

            if (closeMonthPaymentsBtn) closeMonthPaymentsBtn.addEventListener('click', () => closeModal(
                monthPaymentsModal));
            if (closeMonthPaymentsFooterBtn) closeMonthPaymentsFooterBtn.addEventListener('click', () => closeModal(
                monthPaymentsModal));

            if (closeImageViewerBtn) closeImageViewerBtn.addEventListener('click', () => closeModal(
                imageViewerModal));
            if (closeImageViewerFooterBtn) closeImageViewerFooterBtn.addEventListener('click', () => closeModal(
                imageViewerModal));

            if (monthPaymentsModal) {
                monthPaymentsModal.addEventListener('click', function(e) {
                    if (e.target === monthPaymentsModal) closeModal(monthPaymentsModal);
                });
            }

            if (imageViewerModal) {
                imageViewerModal.addEventListener('click', function(e) {
                    if (e.target === imageViewerModal) closeModal(imageViewerModal);
                });
            }

            // Click listener for proof document preview links
            $(document).on('click', '.view-proof-img-link', function(e) {
                e.preventDefault();
                const src = $(this).data('src');
                const title = $(this).data('title') || 'Payment Proof';
                $('#fullImageViewerSrc').attr('src', src);
                $('#imageViewerCaption').text(title);
                openModal(imageViewerModal);
            });

            // 2. Click Event: View Month Payments
            $(document).on('click', '.view-month-payments-btn', function() {
                const month = $(this).data('month');
                const year = $(this).data('year');
                const payments = $(this).data('payments') || [];

                $('#monthPaymentsModalTitle').text(`Payment Verification Records for ${month} ${year}`);

                if ($.fn.DataTable.isDataTable('#monthPaymentsTable')) {
                    $('#monthPaymentsTable').DataTable().clear().destroy();
                }

                const tbody = $('#monthPaymentsTableBody');
                tbody.empty();

                const csrfToken = "{{ csrf_token() }}";
                const approveRouteTemplate = "{{ route('admin.payments.approve', ':id') }}";
                const declineRouteTemplate = "{{ route('admin.payments.decline', ':id') }}";

                if (Array.isArray(payments) && payments.length > 0) {
                    const grouped = {};
                    payments.forEach(function(p) {
                        const tenantName = p.tenant ? p.tenant.fullname : 'N/A';
                        const locationName = (p.tenant && p.tenant.location) ? p.tenant.location.location_name : 'N/A';
                        const room = (p.tenant && p.tenant.rent_information) ? p.tenant.rent_information.room : 'N/A';
                        const key = `${tenantName}_${locationName}_${room}`;

                        if (!grouped[key]) {
                            grouped[key] = {
                                tenantName: tenantName,
                                locationName: locationName,
                                room: room,
                                items: []
                            };
                        }
                        grouped[key].items.push(p);
                    });

                    Object.values(grouped).forEach(function(group) {
                        const initial = group.tenantName.charAt(0).toUpperCase();
                        let totalGroupAmount = 0;
                        let itemsHtml = '';

                        group.items.forEach(function(p) {
                            const dateStr = p.created_at ? new Date(p.created_at).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            }) : 'N/A';

                            const rawAmount = parseFloat(p.amount || 0);
                            totalGroupAmount += rawAmount;

                            const amount = rawAmount.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            const typeBadgeClass = (p.type === 'ECASH') ? 'badge-ecash' : 'badge-cash';
                            const paymentType = p.payment_type || 'Rent';
                            const status = p.status || 'Pending';

                            let statusClass = 'warning';
                            if (status === 'Approved' || status === 'Accepted') statusClass = 'success';
                            else if (status === 'Declined') statusClass = 'danger';

                            // 1. Proof of Billing Column (Electricity or Water statement)
                            let billingDocs = [];
                            if (p.file_electricity && p.file_electricity !== '') {
                                const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(p.file_electricity);
                                if (isImg) {
                                    billingDocs.push(
                                        `<a href="javascript:void(0)" class="view-proof-img-link" data-src="${p.file_electricity}" data-title="Electricity Bill #${p.id} (${group.tenantName})" style="color: #0284c7; font-weight: 700; text-decoration: underline; display: inline-block; margin-right: 8px;">⚡ Electric Bill Document</a>`
                                    );
                                } else {
                                    billingDocs.push(
                                        `<a href="${p.file_electricity}" target="_blank" style="color: #0284c7; font-weight: 700; text-decoration: underline; display: inline-block; margin-right: 8px;">⚡ Electric Bill (PDF)</a>`
                                    );
                                }
                            }
                            if (p.file_water && p.file_water !== '') {
                                const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(p.file_water);
                                if (isImg) {
                                    billingDocs.push(
                                        `<a href="javascript:void(0)" class="view-proof-img-link" data-src="${p.file_water}" data-title="Water Bill #${p.id} (${group.tenantName})" style="color: #0284c7; font-weight: 700; text-decoration: underline; display: inline-block;">💧 Water Bill Document</a>`
                                    );
                                } else {
                                    billingDocs.push(
                                        `<a href="${p.file_water}" target="_blank" style="color: #0284c7; font-weight: 700; text-decoration: underline; display: inline-block;">💧 Water Bill (PDF)</a>`
                                    );
                                }
                            }
                            let billingProofHtml = billingDocs.length > 0 ? billingDocs.join('') :
                                `<span style="color: #94a3b8; font-style: italic;">No billing statement</span>`;

                            // 2. Payment Proof Column (Receipt / Transfer proof)
                            let paymentProofHtml = `<span style="color: #94a3b8; font-style: italic;">No payment proof</span>`;
                            if (p.payment_proof && p.payment_proof !== '') {
                                const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(p.payment_proof);
                                if (isImg) {
                                    paymentProofHtml =
                                        `<a href="javascript:void(0)" class="view-proof-img-link" data-src="${p.payment_proof}" data-title="Payment Proof #${p.id} (${group.tenantName})" style="color: var(--primary); font-weight: 700; text-decoration: underline; display: inline-block;">💳 Payment Receipt</a>`;
                                } else {
                                    paymentProofHtml =
                                        `<a href="${p.payment_proof}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: underline; display: inline-block;">💳 Payment Receipt (PDF)</a>`;
                                }
                            }

                            const receiverName = p.receiver ? p.receiver.fullname : ((status === 'Approved' || status === 'Accepted') ? 'Admin' : '-');

                            let actionHtml = '';
                            if (status === 'Pending') {
                                const approveUrl = approveRouteTemplate.replace(':id', p.id);
                                const declineUrl = declineRouteTemplate.replace(':id', p.id);

                                actionHtml = `
                                    <div style="display: flex; gap: 6px; align-items: center;">
                                        <form action="${approveUrl}" method="POST" style="display:inline;">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <button type="submit" class="btn-approve" onclick="return confirm('Are you sure you want to APPROVE this ${paymentType} payment for ${group.tenantName}?')">
                                                ✓ Approve
                                            </button>
                                        </form>
                                        <form action="${declineUrl}" method="POST" style="display:inline;">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <button type="submit" class="btn-decline" onclick="return confirm('Are you sure you want to DECLINE this ${paymentType} payment for ${group.tenantName}?')">
                                                ✕ Decline
                                            </button>
                                        </form>
                                    </div>
                                `;
                            } else {
                                actionHtml = `
                                    <div style="text-align: right;">
                                        <span class="status-pill ${statusClass}">${status === 'Accepted' ? 'Approved' : status}</span>
                                        <div style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">By: ${receiverName}</div>
                                    </div>
                                `;
                            }

                            let handedToHtml = '';
                            if (p.type === 'CASH') {
                                const handedTo = p.get_fullname ? p.get_fullname : 'N/A';
                                handedToHtml = `<div style="font-size: 0.76rem; color: #334155; font-weight: 600; margin-top: 2px;">👤 Handed to: ${handedTo}</div>`;
                            }

                            itemsHtml += `
                                <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 16px; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                                    <div style="min-width: 140px;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <strong style="font-size: 0.92rem; color: #0f172a;">${paymentType}</strong>
                                            <span class="badge-type ${typeBadgeClass}">${p.type || 'CASH'}</span>
                                        </div>
                                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 3px;">
                                            Date: <strong>${dateStr}</strong> <span style="color:#94a3b8;">(#${p.id})</span>
                                        </div>
                                        ${handedToHtml}
                                    </div>

                                    <div style="min-width: 110px;">
                                        <span style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Amount Paid</span>
                                        <strong style="color: #166534; font-size: 0.98rem;">₱${amount}</strong>
                                    </div>

                                    <div style="min-width: 160px; font-size: 0.8rem;">
                                        <div style="margin-bottom: 2px;">
                                            <span style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Proof of Billing</span>
                                            ${billingProofHtml}
                                        </div>
                                        <div>
                                            <span style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Payment Proof</span>
                                            ${paymentProofHtml}
                                        </div>
                                    </div>

                                    <div style="display: flex; align-items: center;">
                                        ${actionHtml}
                                    </div>
                                </div>
                            `;
                        });

                        const formattedTotal = totalGroupAmount.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const rowHtml = `
                            <tr>
                                <td style="vertical-align: top; width: 280px;">
                                    <div style="display: flex; align-items: flex-start; gap: 12px; padding: 4px 0;">
                                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary-light); color: var(--primary); font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;">
                                            ${initial}
                                        </div>
                                        <div>
                                            <strong style="font-size: 0.95rem; color: #0f172a; display: block;">${group.tenantName}</strong>
                                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px;">
                                                📍 <span class="location-pill" style="font-size: 0.75rem;">${group.locationName}</span>
                                            </div>
                                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 3px;">
                                                🚪 Room: <span class="room-pill" style="font-size: 0.75rem;">${group.room}</span>
                                            </div>
                                            <div style="margin-top: 10px; font-size: 0.78rem; color: #334155; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                Total Submitted: <strong style="color: #166534; font-size: 0.88rem;">₱${formattedTotal}</strong>
                                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">(${group.items.length} payment category item(s))</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="vertical-align: top;">
                                    <div style="display: flex; flex-direction: column;">
                                        ${itemsHtml}
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.append(rowHtml);
                    });
                }

                monthPaymentsDataTable = $('#monthPaymentsTable').DataTable({
                    responsive: false,
                    scrollX: true,
                    autoWidth: false,
                    bLengthChange: false,
                    lengthChange: false,
                    pageLength: 5,
                    language: {
                        search: "Filter Payments:",
                        emptyTable: `No payment transactions found for ${month} ${year}.`,
                        zeroRecords: `No payment transactions found for ${month} ${year}.`
                    }
                });

                openModal(monthPaymentsModal);
            });
        });
    </script>
</body>

</html>
