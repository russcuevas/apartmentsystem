<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment - Moved Out Tenants Directory</title>

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
                            Moved Out Tenants Directory
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 4px;">
                            @if ($selectedLocation)
                                Showing moved out tenants under
                                <strong>{{ $selectedLocation->location_name }}</strong>.
                            @else
                                View records, statement breakdown, and attached documents of moved out tenants.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Data Table Glass Card -->
                <div class="glass-card section-card tenants-card-container">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                        <h3 class="card-title-main" style="font-size: 1.15rem; font-weight: 800;">
                            Moved Out Tenants
                        </h3>
                        <span style="font-size: 0.82rem; color: var(--text-light); font-weight: 600;">
                            Total: {{ $tenants->count() }} Moved Out Tenant(s)
                        </span>
                    </div>

                    <table id="moveOutTenantsTable" class="display custom-table responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tenant Name</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th>Room</th>
                                <th>Base Rental</th>
                                <th>Lease Start Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenants as $tenant)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div class="tenant-avatar-badge"
                                                style="background: #f1f5f9; color: #64748b;">
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
                                        <span class="status-pill danger"
                                            style="background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; font-weight: 800;">
                                            MOVED OUT
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="action-btn-sm action-btn-info view-move-out-details-btn"
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
                                                'ledger' => $tenant->ledger_data ?? [],
                                                'documents' => $tenant->documents_data ?? [],
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

    <!-- MOVED OUT TENANT DETAILS & DOCUMENTS MODAL (2 COLUMNS) -->
    <div class="modal-overlay" id="moveOutDetailsModal">
        <div class="modal-container-xl" style="width: 96vw; max-width: 1500px; padding: 24px;">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="moveOutDetailsModalTitle">Moved Out Tenant Statements & Documents</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                        Complete history breakdown of billing statements, payments, and uploaded documents.
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeMoveOutDetailsBtn">&times;</button>
            </div>
            <div class="modal-body" style="padding-top: 14px;">
                <!-- Tenant Header Summary Card -->
                <div
                    style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div id="modalTenantAvatar"
                            style="width: 48px; height: 48px; border-radius: 12px; background: #e2e8f0; color: #475569; font-weight: 800; font-size: 1.25rem; display: flex; align-items: center; justify-content: center;">
                            -
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <h4 id="modalTenantName"
                                    style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">-</h4>
                                <span class="status-pill danger"
                                    style="font-size: 0.72rem; padding: 2px 8px; border-radius: 6px;">MOVED OUT</span>
                            </div>
                            <div
                                style="font-size: 0.82rem; color: #64748b; margin-top: 4px; display: flex; gap: 12px; flex-wrap: wrap;">
                                <span>📞 Phone: <strong id="modalTenantPhone" style="color: #334155;">-</strong></span>
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
                                style="font-size: 0.72rem; color: #64748b; font-weight: 700; text-transform: uppercase; display: block;">Monthly
                                Rent</span>
                            <strong id="modalTenantRent"
                                style="font-size: 1rem; color: var(--primary);">₱0.00</strong>
                        </div>
                        <div
                            style="text-align: right; background: #fef2f2; padding: 10px 16px; border-radius: 10px; border: 1px solid #fca5a5;">
                            <span
                                style="font-size: 0.72rem; color: #991b1b; font-weight: 700; text-transform: uppercase; display: block;">Final
                                Balance</span>
                            <strong id="modalTenantTotalBalance"
                                style="font-size: 1.1rem; color: #dc2626;">₱0.00</strong>
                        </div>
                    </div>
                </div>

                <!-- 2-COLUMN LAYOUT -->
                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;" id="moveOutGridContainer">
                    <!-- COLUMN 1: PAYMENTS & BILLINGS BREAKDOWN -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <div
                            style="margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                            <h4
                                style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 14l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Statements & Payments Ledger
                            </h4>
                            <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Full Financial
                                Summary</span>
                        </div>

                        <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                            <table id="moveOutLedgerTable" class="display custom-table nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Month / Year</th>
                                        <th>Rent & Utilities</th>
                                        <th>Total Billed</th>
                                        <th>Approved Paid</th>
                                        <th>Outstanding</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="moveOutLedgerTableBody">
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- COLUMN 2: ATTACHED DOCUMENTS & PROOFS -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <div
                            style="margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                            <h4
                                style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                Tenant Attached Documents
                            </h4>
                            <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;"
                                id="moveOutDocsCount">0 Document(s)</span>
                        </div>

                        <div id="moveOutDocsContainer"
                            style="display: flex; flex-direction: column; gap: 10px; max-height: 520px; overflow-y: auto; padding-right: 4px;">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="closeMoveOutDetailsFooterBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- DOCUMENT PREVIEW MODAL -->
    <div class="modal-overlay" id="moveOutDocViewerModal" style="z-index: 10050;">
        <div class="modal-container-md" style="max-width: 800px;">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="moveOutDocViewerTitle">Document Preview</h3>
                </div>
                <button type="button" class="modal-close-btn" id="closeMoveOutDocViewerBtn">&times;</button>
            </div>
            <div class="modal-body" style="text-align: center; padding: 20px;">
                <div id="moveOutDocViewerContent">
                    <img id="moveOutDocViewerImg" src="" alt="Document Preview"
                        style="max-width: 100%; max-height: 70vh; border-radius: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="closeMoveOutDocViewerFooterBtn">Close</button>
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
            // DataTables for Main Table
            $('#moveOutTenantsTable').DataTable({
                responsive: true,
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "Search Moved Out Tenant:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ moved out tenants",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "→",
                        previous: "←"
                    }
                }
            });

            // Modal Controls
            const moveOutDetailsModal = document.getElementById('moveOutDetailsModal');
            const closeMoveOutDetailsBtn = document.getElementById('closeMoveOutDetailsBtn');
            const closeMoveOutDetailsFooterBtn = document.getElementById('closeMoveOutDetailsFooterBtn');

            function openModal(modal) {
                if (modal) modal.classList.add('active');
            }

            function closeModal(modal) {
                if (modal) modal.classList.remove('active');
            }

            if (closeMoveOutDetailsBtn) closeMoveOutDetailsBtn.addEventListener('click', () => closeModal(
                moveOutDetailsModal));
            if (closeMoveOutDetailsFooterBtn) closeMoveOutDetailsFooterBtn.addEventListener('click', () =>
                closeModal(moveOutDetailsModal));

            if (moveOutDetailsModal) {
                moveOutDetailsModal.addEventListener('click', function(e) {
                    if (e.target === moveOutDetailsModal) closeModal(moveOutDetailsModal);
                });
            }

            // Document Viewer Modal Controls
            const moveOutDocViewerModal = document.getElementById('moveOutDocViewerModal');
            const closeMoveOutDocViewerBtn = document.getElementById('closeMoveOutDocViewerBtn');
            const closeMoveOutDocViewerFooterBtn = document.getElementById('closeMoveOutDocViewerFooterBtn');

            if (closeMoveOutDocViewerBtn) closeMoveOutDocViewerBtn.addEventListener('click', () => closeModal(
                moveOutDocViewerModal));
            if (closeMoveOutDocViewerFooterBtn) closeMoveOutDocViewerFooterBtn.addEventListener('click', () =>
                closeModal(moveOutDocViewerModal));

            $(document).on('click', '.view-move-out-details-btn', function() {
                const data = $(this).data('tenant');
                if (!data) return;

                const initial = data.fullname ? data.fullname.charAt(0).toUpperCase() : 'T';

                $('#modalTenantAvatar').text(initial);
                $('#modalTenantName').text(data.fullname);
                $('#modalTenantPhone').text(data.phone_number);
                $('#modalTenantLocation').text(data.location_name);
                $('#modalTenantRoom').text(data.room);
                $('#modalTenantRent').text(`₱${data.monthly_rental}`);
                $('#modalTenantTotalBalance').text(`₱${data.total_balance}`);

                $('#moveOutDetailsModalTitle').text(`Moved Out Tenant History - ${data.fullname}`);

                // 1. Populate Column 1: Ledger Table
                if ($.fn.DataTable.isDataTable('#moveOutLedgerTable')) {
                    $('#moveOutLedgerTable').DataTable().clear().destroy();
                }

                const tbody = $('#moveOutLedgerTableBody');
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
                        const cumBalFormatted = parseFloat(row.cumulative_balance || 0)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                        const rowHtml = `
                            <tr>
                                <td>
                                    <strong style="font-size: 0.88rem; color: #0f172a;">${row.month} ${row.year || ''}</strong>
                                </td>
                                <td>
                                    <div style="font-size: 0.8rem; line-height: 1.4;">
                                        <div><span style="color: #64748b;">Rent:</span> <strong>₱${rentFormatted}</strong></div>
                                        <div><span style="color: #0284c7;">⚡ Elec:</span> <strong>₱${elecFormatted}</strong></div>
                                        <div><span style="color: #0ea5e9;">💧 Water:</span> <strong>₱${waterFormatted}</strong></div>
                                    </div>
                                </td>
                                <td><strong style="color: #0f172a;">₱${totalBilledFormatted}</strong></td>
                                <td><strong style="color: #166534;">₱${totalPaidFormatted}</strong></td>
                                <td><strong style="color: ${parseFloat(row.cumulative_balance) > 0 ? '#dc2626' : '#166534'};">₱${cumBalFormatted}</strong></td>
                                <td><span class="status-pill ${row.status_class}">${row.status}</span></td>
                            </tr>
                        `;
                        tbody.append(rowHtml);
                    });
                }

                $('#moveOutLedgerTable').DataTable({
                    responsive: true,
                    scrollX: true,
                    autoWidth: false,
                    bLengthChange: false,
                    pageLength: 8,
                    ordering: false,
                    language: {
                        search: "Filter:",
                        emptyTable: "No billing records found."
                    }
                });

                // 2. Populate Column 2: Documents Container
                const docsContainer = $('#moveOutDocsContainer');
                docsContainer.empty();

                const docs = data.documents || [];
                $('#moveOutDocsCount').text(`${docs.length} Document(s)`);

                if (Array.isArray(docs) && docs.length > 0) {
                    docs.forEach(function(doc) {
                        const isPdf = doc.url.toLowerCase().endsWith('.pdf');
                        const docCardHtml = `
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                <div>
                                    <strong style="font-size: 0.85rem; color: #0f172a; display: block;">${doc.type}</strong>
                                    <span style="font-size: 0.76rem; color: #64748b;">📅 ${doc.month} ${doc.year} &bull; Added: ${doc.date_added}</span>
                                    ${doc.amount ? `<div style="font-size: 0.78rem; color: #15803d; font-weight: 700; margin-top: 2px;">Amount Paid: ₱${doc.amount} (${doc.status})</div>` : ''}
                                </div>
                                <div>
                                    <button type="button" class="preview-doc-btn" data-url="${doc.url}" data-title="${doc.type} - ${doc.month} ${doc.year}" style="background: #0ea5e9; color: #ffffff; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 0.78rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </button>
                                </div>
                            </div>
                        `;
                        docsContainer.append(docCardHtml);
                    });
                } else {
                    docsContainer.html(`
                        <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 24px; text-align: center; color: #64748b; font-size: 0.85rem;">
                            No attached documents or proof files found for this moved out tenant.
                        </div>
                    `);
                }

                openModal(moveOutDetailsModal);
            });

            // Preview Document Handler
            $(document).on('click', '.preview-doc-btn', function() {
                const url = $(this).data('url');
                const title = $(this).data('title');

                if (!url) return;

                $('#moveOutDocViewerTitle').text(title || 'Document Preview');
                $('#moveOutDocViewerImg').attr('src', url);

                openModal(moveOutDocViewerModal);
            });

            // Sidebar Toggle
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
                        }
                        if (dropdown === userProfileDropdown && notificationDropdown) {
                            notificationDropdown.classList.remove('show');
                        }
                        dropdown.classList.toggle('show');
                    });
                }
            }

            toggleDropdown(notificationBtn, notificationDropdown);
            toggleDropdown(userProfileBtn, userProfileDropdown);

            document.addEventListener('click', function(e) {
                if (notificationDropdown && !notificationDropdown.contains(e.target) && notificationBtn && !
                    notificationBtn.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
                if (userProfileDropdown && !userProfileDropdown.contains(e.target) && userProfileBtn && !
                    userProfileBtn.contains(e.target)) {
                    userProfileDropdown.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>
