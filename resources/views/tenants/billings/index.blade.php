<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('dashboard/tenants/style.css') }}">

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

        .modal-container-lg {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            transform: translateY(20px);
            transition: transform 0.25s ease-in-out;
            overflow: hidden;
        }

        .modal-overlay.active .modal-container-xl,
        .modal-overlay.active .modal-container-lg {
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

        .btn-submit-payment,
        .view-month-billings-btn {
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

        .btn-submit-payment:hover,
        .view-month-billings-btn:hover {
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

        .location-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 8px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid #bae6fd;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-right: 4px;
        }

        .cat-rent {
            background: #fdf4ff;
            color: #86198f;
            border: 1px solid #f5d0fe;
        }

        .cat-elec {
            background: #fefce8;
            color: #a16207;
            border: 1px solid #fef08a;
        }

        .cat-water {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .month-billings-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 868px) {
            html, body, .main-layout, .content-panel {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            .custom-table-card {
                padding: 16px 12px;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            .filter-controls-card {
                padding: 14px 16px;
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                justify-content: space-between;
                width: 100%;
            }

            .filter-select {
                flex-grow: 1;
            }

            /* DataTables Mobile Responsive Controls */
            .dataTables_wrapper {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: left !important;
                width: 100% !important;
                margin: 8px 0 !important;
            }

            .dataTables_wrapper .dataTables_filter label {
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                width: 100% !important;
                gap: 6px !important;
                font-weight: 700 !important;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
                height: 40px !important;
                border-radius: 8px !important;
                border: 1.5px solid var(--border-color) !important;
                padding: 6px 12px !important;
                box-sizing: border-box !important;
            }

            .dataTables_wrapper .dataTables_paginate {
                display: flex !important;
                justify-content: center !important;
                flex-wrap: wrap !important;
                gap: 4px !important;
            }

            .modal-overlay {
                padding: 10px;
            }

            .modal-container-xl {
                width: 98vw;
                max-height: 95vh;
                border-radius: 12px;
            }

            .modal-header {
                padding: 14px 16px;
            }

            .modal-body {
                padding: 14px 16px;
            }

            .modal-footer {
                padding: 12px 16px;
            }

            .month-billings-grid {
                grid-template-columns: 1fr;
                gap: 16px;
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
                <!-- Page Title Header -->
                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <h2
                            style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">
                            My Billings Directory
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                            View and track your monthly rent, electricity, and water bill statements for Year
                            <strong>{{ $selectedYear }}</strong>.
                        </p>
                    </div>
                </div>

                <!-- Year Dropdown Filter -->
                <form action="{{ route('tenant.billings.index') }}" method="GET" id="tenantBillingsFilterForm">
                    <div class="filter-controls-card">
                        <div class="filter-group">
                            <span class="filter-label">Select Year:</span>
                            <select name="year" class="filter-select"
                                onchange="document.getElementById('tenantBillingsFilterForm').submit()">
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
                            Monthly Billings Directory ({{ $selectedYear }})
                        </h3>
                        <p style="font-size: 0.82rem; color: var(--text-light); margin-top: 2px;">
                            Click <strong>View Details</strong> on any month to inspect statement breakdowns, utility
                            proof documents, and balances.
                        </p>
                    </div>

                    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table id="tenantBillingsTable" class="display custom-table nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Statements Included</th>
                                    <th>Total Billed Amount</th>
                                    <th>Total Balance Due</th>
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
                                            @if ($data['rent'])
                                                <span class="cat-pill cat-rent">🏠 Rent</span>
                                            @endif
                                            @if ($data['electricity'])
                                                <span class="cat-pill cat-elec">⚡ Electricity</span>
                                            @endif
                                            @if ($data['water'])
                                                <span class="cat-pill cat-water">💧 Water</span>
                                            @endif
                                            @if (!$data['rent'] && !$data['electricity'] && !$data['water'])
                                                <span style="color: #94a3b8; font-style: italic; font-size: 0.8rem;">No
                                                    statement records</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong style="color: #0f172a; font-size: 0.95rem;">
                                                ₱{{ number_format($data['total_billed'], 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <strong
                                                style="color: {{ $data['total_balance'] > 0 ? '#dc2626' : '#166534' }}; font-size: 0.95rem;">
                                                ₱{{ number_format($data['total_balance'], 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <button type="button" class="view-month-billings-btn"
                                                data-month="{{ $data['month'] }}" data-year="{{ $selectedYear }}"
                                                data-billing='@json($data)'>
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
                            <tfoot>
                                <tr style="background: #f8fafc; border-top: 2.5px solid #cbd5e1; font-weight: 800;">
                                    <td colspan="2"
                                        style="text-align: right; font-size: 0.92rem; color: #0f172a; padding: 14px 18px;">
                                        <strong>TOTAL AMOUNT:</strong>
                                    </td>
                                    <td style="padding: 14px 18px;">
                                        <strong style="color: #0f172a; font-size: 1rem;">
                                            ₱{{ number_format($monthsData->sum('total_billed'), 2) }}
                                        </strong>
                                    </td>
                                    <td style="padding: 14px 18px;">
                                        <strong
                                            style="color: {{ $monthsData->sum('total_balance') > 0 ? '#dc2626' : '#166534' }}; font-size: 1rem;">
                                            ₱{{ number_format($monthsData->sum('total_balance'), 2) }}
                                        </strong>
                                    </td>
                                    <td style="padding: 14px 18px;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL: MONTH BILLINGS DETAILS MODAL -->
    <div class="modal-overlay" id="monthBillingsModal">
        <div class="modal-container-xl">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="monthBillingsModalTitle">Monthly Statement Details</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        Comprehensive breakdown of your monthly rent, utility bill statements, due dates, and balances.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeMonthBillingsBtn">&times;</button>
            </div>
            <div class="modal-body" id="monthBillingsModalBody">
                <!-- Populated dynamically via JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="closeMonthBillingsFooterBtn">Close</button>
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

            if (menuToggleBtn && dashboardLayout && sidebarOverlay) {
                menuToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dashboardLayout.classList.toggle('sidebar-open');
                });
                sidebarOverlay.addEventListener('click', function() {
                    dashboardLayout.classList.remove('sidebar-open');
                });
            }

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
            $('#tenantBillingsTable').DataTable({
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

            let monthBillingsDataTable = null;

            // Modal Controls
            const monthBillingsModal = document.getElementById('monthBillingsModal');
            const closeMonthBillingsBtn = document.getElementById('closeMonthBillingsBtn');
            const closeMonthBillingsFooterBtn = document.getElementById('closeMonthBillingsFooterBtn');

            const imageViewerModal = document.getElementById('imageViewerModal');
            const closeImageViewerBtn = document.getElementById('closeImageViewerBtn');
            const closeImageViewerFooterBtn = document.getElementById('closeImageViewerFooterBtn');

            function openModal(modal) {
                if (modal) modal.classList.add('active');
            }

            function closeModal(modal) {
                if (modal) modal.classList.remove('active');
            }

            if (closeMonthBillingsBtn) closeMonthBillingsBtn.addEventListener('click', () => closeModal(
                monthBillingsModal));
            if (closeMonthBillingsFooterBtn) closeMonthBillingsFooterBtn.addEventListener('click', () => closeModal(
                monthBillingsModal));

            if (closeImageViewerBtn) closeImageViewerBtn.addEventListener('click', () => closeModal(
                imageViewerModal));
            if (closeImageViewerFooterBtn) closeImageViewerFooterBtn.addEventListener('click', () => closeModal(
                imageViewerModal));

            if (monthBillingsModal) {
                monthBillingsModal.addEventListener('click', function(e) {
                    if (e.target === monthBillingsModal) closeModal(monthBillingsModal);
                });
            }

            if (imageViewerModal) {
                imageViewerModal.addEventListener('click', function(e) {
                    if (e.target === imageViewerModal) closeModal(imageViewerModal);
                });
            }

            // Click listener for clickable preview images
            $(document).on('click', '.preview-clickable-img', function() {
                const src = $(this).attr('src');
                const caption = $(this).attr('title') || 'Image Preview';
                $('#fullImageViewerSrc').attr('src', src);
                $('#imageViewerCaption').text(caption);
                openModal(imageViewerModal);
            });

            // Tenant Info Default values
            const tenantDefaultName = @json($tenant->fullname ?? 'Tenant');
            const tenantDefaultLocation = @json($tenant->location->location_name ?? 'N/A');
            const tenantDefaultRoom = @json($tenant->rentInformation->room ?? 'N/A');
            const submitPaymentRoute = @json(route('tenant.payments.index'));

            // 2. Click Event: View Month Billings
            $(document).on('click', '.view-month-billings-btn', function() {
                const month = $(this).data('month');
                const year = $(this).data('year');
                const data = $(this).data('billing') || {};

                $('#monthBillingsModalTitle').text(`Monthly Statement Details for ${month} ${year}`);

                if ($.fn.DataTable.isDataTable('#monthBillingsTable')) {
                    $('#monthBillingsTable').DataTable().clear().destroy();
                }

                const tbody = $('#monthBillingsTableBody');
                tbody.empty();

                const initial = tenantDefaultName.charAt(0).toUpperCase();

                const totalBilledFormatted = parseFloat(data.total_billed || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                const totalPaidFormatted = parseFloat(data.total_paid || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                const totalBalFormatted = parseFloat(data.total_balance || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                let cardsHtml = '';

                // 1. Rent Card
                if (data.rent) {
                    const r = data.rent;
                    const rAmt = parseFloat(r.amount || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const rPaid = parseFloat(r.paid || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const rBal = parseFloat(r.balance || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    let rStatusClass = 'warning';
                    if (r.status === 'Paid') rStatusClass = 'success';
                    else if (r.status === 'Unpaid') rStatusClass = 'danger';

                    cardsHtml += `
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 1.1rem;">🏠</span>
                                    <strong style="font-size: 1rem; color: #0f172a;">Monthly Rent Statement</strong>
                                </div>
                                <span class="status-pill ${rStatusClass}">${r.status}</span>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; font-size: 0.84rem; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Billed Amount</span>
                                    <strong style="color: #0f172a; font-size: 0.95rem;">₱${rAmt}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Paid Amount</span>
                                    <strong style="color: #166534; font-size: 0.95rem;">₱${rPaid}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Remaining Balance</span>
                                    <strong style="color: ${r.balance > 0 ? '#dc2626' : '#166534'}; font-size: 0.95rem;">₱${rBal}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Due Date</span>
                                    <span style="color: #334155; font-weight: 700;">📅 ${r.due_date}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // 2. Electricity Card
                if (data.electricity) {
                    const e = data.electricity;
                    const eAmt = parseFloat(e.amount || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const ePaid = parseFloat(e.paid || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const eBal = parseFloat(e.balance || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    let eStatusClass = 'warning';
                    if (e.status === 'Paid') eStatusClass = 'success';
                    else if (e.status === 'Unpaid') eStatusClass = 'danger';

                    let proofHtml = '';
                    if (e.proof && e.proof !== '') {
                        const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(e.proof);
                        if (isImg) {
                            proofHtml = `
                                <div style="margin-top: 10px; text-align: center; background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                    <img src="${e.proof}" class="preview-clickable-img" style="max-height: 200px; max-width: 100%; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s;" title="Electricity Bill Statement (${month})" alt="Electricity Statement">
                                    <div style="font-size: 0.76rem; color: #166534; font-weight: 700; margin-top: 6px;">
                                        🔍 Click image to view full size zoom in (⚡ Electricity Statement File)
                                    </div>
                                </div>
                            `;
                        } else {
                            proofHtml = `
                                <div style="margin-top: 10px; padding: 10px; background: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd;">
                                    <a href="${e.proof}" target="_blank" style="color: #0369a1; font-weight: 700; font-size: 0.82rem; text-decoration: underline;">
                                        ⚡ View Billed Electricity Statement (PDF Document) ↗
                                    </a>
                                </div>
                            `;
                        }
                    }

                    cardsHtml += `
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 1.1rem;">⚡</span>
                                    <strong style="font-size: 1rem; color: #0f172a;">Electricity Bill Statement</strong>
                                </div>
                                <span class="status-pill ${eStatusClass}">${e.status}</span>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; font-size: 0.84rem; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Billed Amount</span>
                                    <strong style="color: #0f172a; font-size: 0.95rem;">₱${eAmt}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Paid Amount</span>
                                    <strong style="color: #166534; font-size: 0.95rem;">₱${ePaid}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Remaining Balance</span>
                                    <strong style="color: ${e.balance > 0 ? '#dc2626' : '#166534'}; font-size: 0.95rem;">₱${eBal}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Due Date</span>
                                    <span style="color: #334155; font-weight: 700;">📅 ${e.due_date}</span>
                                </div>
                            </div>
                            ${proofHtml}
                        </div>
                    `;
                }

                // 3. Water Card
                if (data.water) {
                    const w = data.water;
                    const wAmt = parseFloat(w.amount || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const wPaid = parseFloat(w.paid || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const wBal = parseFloat(w.balance || 0).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    let wStatusClass = 'warning';
                    if (w.status === 'Paid') wStatusClass = 'success';
                    else if (w.status === 'Unpaid') wStatusClass = 'danger';

                    let proofHtml = '';
                    if (w.proof && w.proof !== '') {
                        const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(w.proof);
                        if (isImg) {
                            proofHtml = `
                                <div style="margin-top: 10px; text-align: center; background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                    <img src="${w.proof}" class="preview-clickable-img" style="max-height: 200px; max-width: 100%; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s;" title="Water Bill Statement (${month})" alt="Water Statement">
                                    <div style="font-size: 0.76rem; color: #166534; font-weight: 700; margin-top: 6px;">
                                        🔍 Click image to view full size zoom in (💧 Water Statement File)
                                    </div>
                                </div>
                            `;
                        } else {
                            proofHtml = `
                                <div style="margin-top: 10px; padding: 10px; background: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd;">
                                    <a href="${w.proof}" target="_blank" style="color: #0369a1; font-weight: 700; font-size: 0.82rem; text-decoration: underline;">
                                        💧 View Billed Water Statement (PDF Document) ↗
                                    </a>
                                </div>
                            `;
                        }
                    }

                    cardsHtml += `
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 1.1rem;">💧</span>
                                    <strong style="font-size: 1rem; color: #0f172a;">Water Bill Statement</strong>
                                </div>
                                <span class="status-pill ${wStatusClass}">${w.status}</span>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; font-size: 0.84rem; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Billed Amount</span>
                                    <strong style="color: #0f172a; font-size: 0.95rem;">₱${wAmt}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Paid Amount</span>
                                    <strong style="color: #166534; font-size: 0.95rem;">₱${wPaid}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Remaining Balance</span>
                                    <strong style="color: ${w.balance > 0 ? '#dc2626' : '#166534'}; font-size: 0.95rem;">₱${wBal}</strong>
                                </div>
                                <div>
                                    <span style="font-size: 0.72rem; color: #64748b; text-transform: uppercase; font-weight: 700; display: block;">Due Date</span>
                                    <span style="color: #334155; font-weight: 700;">📅 ${w.due_date}</span>
                                </div>
                            </div>
                            ${proofHtml}
                        </div>
                    `;
                }

                if (cardsHtml === '') {
                    cardsHtml = `
                        <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 24px; text-align: center; color: #64748b;">
                            No statement records generated for ${month} ${year}.
                        </div>
                    `;
                }

                const contentHtml = `
                    <div class="month-billings-grid">
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--primary-light); color: var(--primary); font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">
                                    ${initial}
                                </div>
                                <div>
                                    <strong style="font-size: 1rem; color: #0f172a; display: block;">${tenantDefaultName}</strong>
                                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                                        <span class="location-pill">📍 ${tenantDefaultLocation}</span>
                                        <span class="room-pill">🚪 Room ${tenantDefaultRoom}</span>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 16px; font-size: 0.82rem; color: #334155; background: #f8fafc; padding: 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <span>Total Billed:</span>
                                    <strong style="color: #0f172a;">₱${totalBilledFormatted}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                    <span>Total Paid:</span>
                                    <strong style="color: #166534;">₱${totalPaidFormatted}</strong>
                                </div>
                                <div style="padding-top: 6px; border-top: 1px dashed #cbd5e1; display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: 700; color: #475569;">Balance Due:</span>
                                    <strong style="color: ${data.total_balance > 0 ? '#dc2626' : '#166534'}; font-size: 0.95rem;">₱${totalBalFormatted}</strong>
                                </div>
                            </div>

                            ${data.total_balance > 0 ? `
                                <a href="${submitPaymentRoute}" class="btn-submit-payment" style="width: 100%; justify-content: center; margin-top: 14px; font-size: 0.85rem; padding: 10px 14px;">
                                    💳 Pay Remaining Balance
                                </a>
                            ` : ''}
                        </div>

                        <div style="display: flex; flex-direction: column;">
                            ${cardsHtml}
                        </div>
                    </div>
                `;

                $('#monthBillingsModalBody').html(contentHtml);
                openModal(monthBillingsModal);
            });
        });
    </script>
</body>

</html>
