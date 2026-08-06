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

        .btn-submit-payment,
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

        .btn-submit-payment:hover,
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

            .modal-container-lg, .modal-container-xl {
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
                            My Payments Directory
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                            Track your monthly payment transactions, receipts, and verification status for Year
                            <strong>{{ $selectedYear }}</strong>.
                        </p>
                    </div>
                    <button type="button" class="btn-submit-payment" id="openAddPaymentBtn"
                        style="font-size: 0.9rem; padding: 10px 20px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                        </svg>
                        Submit Payment
                    </button>
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

                <!-- Year Dropdown Filter -->
                <form action="{{ route('tenant.payments.index') }}" method="GET" id="tenantPaymentsFilterForm">
                    <div class="filter-controls-card">
                        <div class="filter-group">
                            <span class="filter-label">Select Year:</span>
                            <select name="year" class="filter-select"
                                onchange="document.getElementById('tenantPaymentsFilterForm').submit()">
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
                            transfer proof, and track payment status.
                        </p>
                    </div>

                    <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table id="tenantMonthsTable" class="display custom-table nowrap" style="width:100%">
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

    <!-- MODAL 0: SUBMIT PAYMENT MODAL -->
    <div class="modal-overlay @if ($errors->any()) active @endif" id="addTenantPaymentModal">
        <div class="modal-container-lg" style="max-width: 720px;">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Submit Payment</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        Tenant: <strong>{{ $tenant->fullname }}</strong> | Room:
                        <strong>{{ $tenant->rentInformation->room ?? 'N/A' }}</strong>
                        ({{ $tenant->location->location_name ?? 'N/A' }})
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeAddPaymentBtn">&times;</button>
            </div>
            <form action="{{ route('tenant.payments.store') }}" method="POST" enctype="multipart/form-data"
                style="display: flex; flex-direction: column; overflow: hidden; max-height: calc(85vh - 70px);">
                @csrf
                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">

                <div class="modal-body"
                    style="padding: 24px; overflow-y: auto; max-height: calc(85vh - 140px); -webkit-overflow-scrolling: touch;">
                    <!-- Payment Guidelines & Rules Info Box -->
                    <div
                        style="background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border: 1.5px solid #93c5fd; border-radius: 12px; padding: 16px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <span
                                style="font-size: 0.9rem; font-weight: 800; color: #0369a1; display: flex; align-items: center; gap: 6px;">
                                💡 Payment Submission Guide & Rules
                            </span>
                            <span
                                style="font-size: 0.72rem; background: #0284c7; color: #ffffff; padding: 2px 8px; border-radius: 12px; font-weight: 700;">Important</span>
                        </div>

                        <ul
                            style="margin: 0; padding-left: 18px; font-size: 0.81rem; color: #334155; line-height: 1.55;">
                            <li style="margin-bottom: 4px;">
                                <strong>1. Select Category & Billing Month:</strong> Choose whether paying for
                                <em>Monthly Rental</em>, <em>Electricity</em>, or <em>Water</em> for your target month.
                            </li>
                            <li style="margin-bottom: 4px;">
                                <strong>2. Utility Bills (Electricity / Water):</strong> Upload your bill statement
                                document (Proof of Billing) so admin can verify the billed statement amount.
                            </li>
                            <li style="margin-bottom: 4px;">
                                <strong>3. Payment Method:</strong> For <strong>ECASH</strong> (GCash/Bank), attach your
                                payment transfer receipt. For <strong>CASH</strong>, enter the full name of the admin
                                who received your payment over-the-counter.
                            </li>
                            <li>
                                <strong>4. Verification Status:</strong> Submitted payments will be tagged as <span
                                    class="status-pill warning"
                                    style="font-size: 0.7rem; padding: 2px 8px;">Pending</span> until approved by the
                                admin.
                            </li>
                        </ul>
                    </div>

                    <!-- 1. Category & Billing Month (2 Columns) -->
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label
                                style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Select Payment Category <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="category" id="paymentCategorySelect" class="filter-select"
                                style="width: 100%; height: 42px;" required>
                                <option value="Monthly Rental" selected>Monthly Rental</option>
                                <option value="Electricity">Electricity</option>
                                <option value="Water">Water</option>
                            </select>
                        </div>

                        <div>
                            <label
                                style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Billing Month <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="billing_month" class="filter-select" style="width: 100%; height: 42px;"
                                required>
                                @foreach ($allMonths as $m)
                                    <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Existing Billing Statement Summary Info Box -->
                    <div id="existingBillInfoBox"
                        style="display: none; background: #f0fdf4; border: 1.5px solid #86efac; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;">
                        <div
                            style="font-size: 0.88rem; font-weight: 800; color: #166534; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                            <span id="existingBillTitle">📄 Existing Billing Statement Details</span>
                            <span class="status-pill success" id="existingBillStatusBadge">Active Statement</span>
                        </div>
                        <div style="font-size: 0.84rem; color: #334155; line-height: 1.5;">
                            <div style="display: flex; gap: 18px; flex-wrap: wrap; margin-top: 4px;">
                                <span>Total Statement Billed: <strong id="existingBillTotal"
                                        style="color: #0f172a;">₱0.00</strong></span>
                                <span>Already Paid / Submitted: <strong id="existingBillPaid"
                                        style="color: #166534;">₱0.00</strong></span>
                            </div>
                            <div
                                style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed #cbd5e1; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                                <span style="font-weight: 700; color: #475569;">Remaining Balance Due (Kulang Na
                                    Lang):</span>
                                <strong id="existingBillBalance"
                                    style="font-size: 1.05rem; font-weight: 800; color: #dc2626;">₱0.00</strong>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Proof of Billing & Utility Total Amount Card (Shown only for Electricity or Water) -->
                    <div id="proofOfBillingGroup"
                        style="display: none; background: #f0f9ff; border: 1.5px dashed #0284c7; padding: 18px; border-radius: 12px; margin-bottom: 20px;">
                        <div
                            style="font-size: 0.88rem; font-weight: 800; color: #0369a1; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                            <span>⚡ Utility Statement Details</span>
                        </div>

                        <!-- Existing Uploaded Statement Banner -->
                        <div id="existingProofOfBillingContainer"
                            style="display: none; background: #ffffff; border: 1.5px solid #bae6fd; border-radius: 10px; padding: 12px 16px; margin-bottom: 14px; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.06);">
                            <div
                                style="font-size: 0.84rem; font-weight: 800; color: #0369a1; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <span>✓ Utility Bill Statement Already Uploaded</span>
                                <span class="status-pill success" style="font-size: 0.72rem; padding: 2px 8px;">On
                                    File</span>
                            </div>
                            <div style="font-size: 0.81rem; color: #334155; margin-top: 6px;"
                                id="existingProofOfBillingLinkBox">
                                <!-- Dynamic link / preview via JS -->
                            </div>
                            <div
                                style="font-size: 0.75rem; color: #0284c7; margin-top: 6px; font-weight: 600; background: #f0f9ff; padding: 6px 10px; border-radius: 6px;">
                                ℹ️ You do NOT need to re-upload the bill statement file. It will be attached
                                automatically to this payment submission.
                            </div>
                        </div>

                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                            <div id="uploadProofOfBillingWrapper">
                                <label id="proofOfBillingLabelText"
                                    style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                    Upload Utility Bill Statement (Proof of Billing) <span
                                        style="color: #ef4444;">*</span>
                                </label>
                                <input type="file" name="proof_of_billing" id="proofOfBillingInput"
                                    accept="image/*,application/pdf" class="filter-select" style="width: 100%;">
                                <div id="proofOfBillingPreview" style="margin-top: 6px;"></div>
                            </div>

                            <div id="utilityAmountContainer">
                                <label id="utilityAmountLabel"
                                    style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                    Utility Bill Total Amount (₱) <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="number" step="0.01" min="0" id="utilityAmountInput"
                                    name="electricity_amount" class="filter-select"
                                    style="width: 100%; height: 42px;"
                                    placeholder="Type total amount from billing receipt...">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Payment Method & Amount Paid (2 Columns) -->
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label
                                style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Payment Method <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="type" id="paymentTypeSelect" class="filter-select"
                                style="width: 100%; height: 42px;" required>
                                <option value="CASH" selected>CASH (Over-the-Counter / Handed to Admin)</option>
                                <option value="ECASH">ECASH (GCash / Maya / Bank Transfer)</option>
                            </select>
                        </div>

                        <div>
                            <label
                                style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Amount Paid (₱) <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" name="amount"
                                class="filter-select" style="width: 100%; height: 42px;" placeholder="0.00" required>
                        </div>
                    </div>

                    <!-- ECASH QR Code Info Box (Shown when ECASH is selected) -->
                    <div id="ecashQrGroup"
                        style="display: none; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border: 1.5px solid #0d9488; border-radius: 12px; padding: 18px; margin-bottom: 20px; text-align: center; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.08);">
                        <div
                            style="font-size: 0.9rem; font-weight: 800; color: #0f766e; margin-bottom: 6px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            📲 ECASH (GCash / Maya) Payment QR Code
                        </div>
                        <p style="font-size: 0.8rem; color: #334155; margin-bottom: 12px;">
                            Scan the QR code below using your <strong>GCash / Maya app</strong> to pay your bill
                            directly.
                        </p>
                        <div
                            style="display: inline-block; background: #ffffff; padding: 12px; border-radius: 12px; border: 1px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <img src="{{ asset('uploads/ecash/gcash-qr.jpg') }}" class="preview-clickable-img"
                                style="max-height: 260px; max-width: 100%; border-radius: 8px; cursor: pointer; transition: transform 0.2s; display: block; margin: 0 auto;"
                                title="GCash QR Code - Click to zoom in full size" alt="GCash QR Code">
                            <div
                                style="font-size: 0.78rem; color: #166534; font-weight: 700; margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                🔍 Click QR Code image to view full size / zoom in
                            </div>
                        </div>
                    </div>

                    <!-- 4. Handed To / Received By Name (Shown only when CASH is selected) -->
                    <div id="getFullnameGroup"
                        style="display: block; background: #fff7ed; border: 1px solid #ffedd5; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                        <label
                            style="display: block; font-size: 0.84rem; font-weight: 700; color: #c2410c; margin-bottom: 6px;">
                            Handed / Received By (Full Name) <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="get_fullname" id="getFullnameInput" class="filter-select"
                            style="width: 100%; height: 42px;" required
                            placeholder="Type the full name of admin/person who received cash...">
                    </div>

                    <!-- 5. Upload Payment Proof / Receipt Card -->
                    <div
                        style="background: #f8fafc; border: 1.5px dashed #cbd5e1; padding: 18px; border-radius: 12px;">
                        <label
                            style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Upload Payment Receipt / Transfer Proof <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="file" name="payment_proof" id="paymentProofInput"
                            accept="image/*,application/pdf" class="filter-select" style="width: 100%;">
                        <div id="paymentProofPreview" style="margin-top: 6px;"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelAddPaymentBtn">Cancel</button>
                    <button type="submit" class="btn-submit-payment" style="margin-left: 10px;">✓ Submit
                        Payment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: MONTH PAYMENTS VERIFICATION MODAL -->
    <div class="modal-overlay" id="monthPaymentsModal">
        <div class="modal-container-xl">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="monthPaymentsModalTitle">Payment Verification List</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        Detailed list of submitted tenant payments and verification status.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeMonthPaymentsBtn">&times;</button>
            </div>
            <div class="modal-body">
                <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="monthPaymentsTable" class="display custom-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>My Details</th>
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
            $('#tenantMonthsTable').DataTable({
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
            const addTenantPaymentModal = document.getElementById('addTenantPaymentModal');
            const openAddPaymentBtn = document.getElementById('openAddPaymentBtn');
            const closeAddPaymentBtn = document.getElementById('closeAddPaymentBtn');
            const cancelAddPaymentBtn = document.getElementById('cancelAddPaymentBtn');

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

            if (openAddPaymentBtn) openAddPaymentBtn.addEventListener('click', () => {
                openModal(addTenantPaymentModal);
                $('#paymentTypeSelect').trigger('change');
            });
            if (closeAddPaymentBtn) closeAddPaymentBtn.addEventListener('click', () => closeModal(
                addTenantPaymentModal));
            if (cancelAddPaymentBtn) cancelAddPaymentBtn.addEventListener('click', () => closeModal(
                addTenantPaymentModal));

            if (closeMonthPaymentsBtn) closeMonthPaymentsBtn.addEventListener('click', () => closeModal(
                monthPaymentsModal));
            if (closeMonthPaymentsFooterBtn) closeMonthPaymentsFooterBtn.addEventListener('click', () => closeModal(
                monthPaymentsModal));

            if (closeImageViewerBtn) closeImageViewerBtn.addEventListener('click', () => closeModal(
                imageViewerModal));
            if (closeImageViewerFooterBtn) closeImageViewerFooterBtn.addEventListener('click', () => closeModal(
                imageViewerModal));

            if (addTenantPaymentModal) {
                addTenantPaymentModal.addEventListener('click', function(e) {
                    if (e.target === addTenantPaymentModal) closeModal(addTenantPaymentModal);
                });
            }

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

            $(document).on('click', '.preview-clickable-img', function() {
                const src = $(this).attr('src');
                const caption = $(this).attr('title') || 'Image Preview';
                $('#fullImageViewerSrc').attr('src', src);
                $('#imageViewerCaption').text(caption);
                openModal(imageViewerModal);
            });

            // Payment Method Change Handler (CASH vs ECASH)
            $('#paymentTypeSelect').on('change', function() {
                const val = $(this).val();
                if (val === 'CASH') {
                    $('#getFullnameGroup').slideDown(150);
                    $('#getFullnameInput').prop('required', true);
                    $('#ecashQrGroup').slideUp(150);
                } else {
                    $('#getFullnameGroup').slideUp(150);
                    $('#getFullnameInput').prop('required', false).val('');
                    $('#ecashQrGroup').slideDown(150);
                }
            });

            // Existing Billings Summary Data from Server
            const tenantBillingsSummary = @json($tenantBillingsSummary ?? []);
            const tenantDefaultName = @json($tenant->fullname ?? 'Tenant');
            const tenantDefaultLocation = @json($tenant->location->location_name ?? 'N/A');
            const tenantDefaultRoom = @json($tenant->rentInformation->room ?? 'N/A');

            function updateBillingSummaryView() {
                const category = $('#paymentCategorySelect').val();
                const month = $('select[name="billing_month"]').val();

                if (!category || !month || !tenantBillingsSummary[month]) {
                    $('#existingBillInfoBox').slideUp(150);
                    return;
                }

                const catData = tenantBillingsSummary[month][category];
                if (!catData) {
                    $('#existingBillInfoBox').slideUp(150);
                    return;
                }

                const totalAmt = parseFloat(catData.total_amount || 0);
                const paidAmt = parseFloat(catData.paid_amount || 0);
                const balAmt = parseFloat(catData.balance || 0);
                const hasBill = catData.has_existing_bill;

                const totalFormatted = totalAmt.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                const paidFormatted = paidAmt.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                const balFormatted = balAmt.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                if (hasBill && totalAmt > 0) {
                    $('#existingBillTitle').text(`📄 Existing ${category} Billing Statement for ${month}`);
                    $('#existingBillTotal').text(`₱${totalFormatted}`);
                    $('#existingBillPaid').text(`₱${paidFormatted}`);
                    $('#existingBillBalance').text(`₱${balFormatted}`);

                    if (balAmt <= 0) {
                        $('#existingBillStatusBadge').attr('class', 'status-pill success').text('Paid in Full');
                    } else if (paidAmt > 0) {
                        $('#existingBillStatusBadge').attr('class', 'status-pill warning').text('Partial Balance');
                    } else {
                        $('#existingBillStatusBadge').attr('class', 'status-pill danger').text('Unpaid Statement');
                    }

                    $('#existingBillInfoBox').slideDown(150);

                    // Auto pre-fill Amount Paid input with remaining balance
                    if (balAmt > 0) {
                        $('input[name="amount"]').val(balAmt.toFixed(2));
                    }

                    if (category === 'Electricity' || category === 'Water') {
                        $('#proofOfBillingGroup').slideDown(150);
                        $('#utilityAmountContainer').hide();
                        $('#utilityAmountInput').prop('required', false).val(totalAmt.toFixed(2));

                        const existingProof = catData.proof_of_billing;
                        if (existingProof && existingProof !== '') {
                            const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(existingProof);
                            let proofHtml = '';
                            if (isImg) {
                                proofHtml = `
                                    <div style="margin-top: 8px; text-align: center; background: #ffffff; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1;">
                                        <img src="${existingProof}" class="preview-clickable-img" style="max-height: 250px; max-width: 100%; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); object-fit: contain; display: block; margin: 0 auto; cursor: pointer; transition: transform 0.2s;" title="Uploaded ${category} Bill Statement (${month})" alt="Uploaded ${category} Bill Statement">
                                        <div style="font-size: 0.78rem; color: #166534; font-weight: 700; margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                            🔍 Click image to view full size / zoom in (⚡ ${category} Bill Statement for ${month})
                                        </div>
                                    </div>
                                `;
                            } else {
                                proofHtml = `
                                    <div style="margin-top: 8px; padding: 12px; background: #e0f2fe; border-radius: 8px; border: 1px solid #7dd3fc;">
                                        <a href="${existingProof}" target="_blank" style="color: #0369a1; font-weight: 700; font-size: 0.85rem; text-decoration: underline; display: flex; align-items: center; gap: 6px;">
                                            📄 View Uploaded ${category} Bill Statement (PDF Document) ↗
                                        </a>
                                    </div>
                                `;
                            }
                            $('#existingProofOfBillingLinkBox').html(proofHtml);
                            $('#existingProofOfBillingContainer').show();
                            $('#uploadProofOfBillingWrapper').hide();
                            $('#proofOfBillingInput').prop('required', false);
                        } else {
                            $('#existingProofOfBillingContainer').hide();
                            $('#uploadProofOfBillingWrapper').show();
                            $('#proofOfBillingLabelText').html(
                                'Upload Utility Bill Statement (Proof of Billing) <span style="color: #ef4444;">*</span>'
                            );
                            $('#proofOfBillingInput').prop('required', false);
                        }
                    } else {
                        $('#proofOfBillingGroup').slideUp(150);
                        $('#uploadProofOfBillingWrapper').hide();
                        $('#proofOfBillingInput').prop('required', false);
                    }
                } else {
                    $('#existingBillInfoBox').slideUp(150);

                    if (category === 'Electricity' || category === 'Water') {
                        $('#proofOfBillingGroup').slideDown(150);
                        $('#existingProofOfBillingContainer').hide();
                        $('#uploadProofOfBillingWrapper').show();
                        $('#proofOfBillingLabelText').html(
                            'Upload Utility Bill Statement (Proof of Billing) <span style="color: #ef4444;">*</span>'
                        );
                        $('#proofOfBillingInput').prop('required', true);
                        $('#utilityAmountContainer').show();
                        $('#utilityAmountInput').prop('required', true);
                        if (category === 'Electricity') {
                            $('#utilityAmountLabel').html(
                                'Electricity Bill Total Amount (₱) <span style="color: #ef4444;">*</span>');
                            $('#utilityAmountInput').attr('name', 'electricity_amount');
                        } else {
                            $('#utilityAmountLabel').html(
                                'Water Bill Total Amount (₱) <span style="color: #ef4444;">*</span>');
                            $('#utilityAmountInput').attr('name', 'water_amount');
                        }
                    } else {
                        $('#proofOfBillingGroup').slideUp(150);
                        $('#uploadProofOfBillingWrapper').hide();
                        $('#proofOfBillingInput').prop('required', false);
                        $('#utilityAmountInput').prop('required', false).removeAttr('name').val('');
                    }
                }
            }

            $('#paymentCategorySelect, select[name="billing_month"]').on('change', updateBillingSummaryView);

            // Also call on modal open
            $('#openAddPaymentBtn').on('click', function() {
                setTimeout(updateBillingSummaryView, 100);
            });

            // Instant File Upload Preview Helper
            function setupFilePreview(inputId, previewContainerId) {
                $(inputId).on('change', function() {
                    const file = this.files[0];
                    const container = $(previewContainerId);
                    container.empty();

                    if (file) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                container.html(`
                                    <div style="margin-top: 8px; text-align: center; background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                        <img src="${e.target.result}" class="preview-clickable-img" style="max-height: 140px; max-width: 100%; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s;" title="${file.name}">
                                        <div style="font-size: 0.78rem; color: #15803d; font-weight: 700; margin-top: 6px;">
                                            🔍 Click image to view full size (${file.name})
                                        </div>
                                    </div>
                                `);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            container.html(`
                                <div style="margin-top: 8px; padding: 10px; background: #e0f2fe; border-radius: 8px; color: #0369a1; font-weight: 700; font-size: 0.82rem; display: flex; align-items: center; gap: 8px;">
                                    <span>📄 ${file.name}</span>
                                    <span style="font-size: 0.72rem; background: #0284c7; color: #fff; padding: 2px 6px; border-radius: 4px;">PDF Document</span>
                                </div>
                            `);
                        }
                    }
                });
            }

            setupFilePreview('#paymentProofInput', '#paymentProofPreview');
            setupFilePreview('#proofOfBillingInput', '#proofOfBillingPreview');

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

                if (Array.isArray(payments) && payments.length > 0) {
                    const grouped = {};
                    payments.forEach(function(p) {
                        const tenantName = (p.tenant && p.tenant.fullname) ? p.tenant.fullname :
                            tenantDefaultName;
                        const locationName = (p.tenant && p.tenant.location) ? p.tenant.location
                            .location_name : tenantDefaultLocation;
                        const room = (p.tenant && p.tenant.rent_information) ? p.tenant
                            .rent_information.room : (p.tenant && p.tenant.rentInformation ? p
                                .tenant.rentInformation.room : tenantDefaultRoom);
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
                            const dateStr = p.created_at ? new Date(p.created_at)
                                .toLocaleDateString('en-US', {
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

                            const typeBadgeClass = (p.type === 'ECASH') ? 'badge-ecash' :
                                'badge-cash';
                            const paymentType = p.payment_type || 'Rent';
                            const status = p.status || 'Pending';

                            let statusClass = 'warning';
                            if (status === 'Approved' || status === 'Accepted')
                                statusClass = 'success';
                            else if (status === 'Declined') statusClass = 'danger';

                            // 1. Proof of Billing Column (Electricity or Water statement)
                            let billingDocs = [];
                            if (p.file_electricity && p.file_electricity !== '') {
                                const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(p
                                    .file_electricity);
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
                                const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(p
                                    .file_water);
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
                            let billingProofHtml = billingDocs.length > 0 ? billingDocs
                                .join('') :
                                `<span style="color: #94a3b8; font-style: italic;">No billing statement</span>`;

                            // 2. Payment Proof Column (Receipt / Transfer proof)
                            let paymentProofHtml =
                                `<span style="color: #94a3b8; font-style: italic;">No payment proof</span>`;
                            if (p.payment_proof && p.payment_proof !== '') {
                                const isImg = /\.(jpg|jpeg|png|webp|gif)$/i.test(p
                                    .payment_proof);
                                if (isImg) {
                                    paymentProofHtml =
                                        `<a href="javascript:void(0)" class="view-proof-img-link" data-src="${p.payment_proof}" data-title="Payment Proof #${p.id} (${group.tenantName})" style="color: var(--primary); font-weight: 700; text-decoration: underline; display: inline-block;">💳 Payment Receipt</a>`;
                                } else {
                                    paymentProofHtml =
                                        `<a href="${p.payment_proof}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: underline; display: inline-block;">💳 Payment Receipt (PDF)</a>`;
                                }
                            }

                            const receiverName = p.receiver ? p.receiver.fullname : ((
                                    status === 'Approved' || status === 'Accepted') ?
                                'Admin' : '-');

                            let actionHtml = `
                                <div style="text-align: right;">
                                    <span class="status-pill ${statusClass}">${(status === 'Accepted' || status === 'Approved') ? 'Approved' : status}</span>
                                    <div style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">By: ${receiverName}</div>
                                </div>
                            `;

                            let handedToHtml = '';
                            if (p.type === 'CASH') {
                                const handedTo = p.get_fullname ? p.get_fullname : 'N/A';
                                handedToHtml =
                                    `<div style="font-size: 0.76rem; color: #334155; font-weight: 600; margin-top: 2px;">👤 Handed to: ${handedTo}</div>`;
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
