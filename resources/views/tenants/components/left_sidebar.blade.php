<aside class="sidebar-aside" id="sidebarAside">
    <!-- Sidebar Header with Logo -->
    <div class="sidebar-header">
        <a href="{{ route('tenant.dashboard.page') }}" class="sidebar-logo">
            <div class="logo-icon">
                <!-- Custom SVG Apartment Building Icon -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19 2H9c-1.1 0-2 .9-2 2v3H3c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM5 20H3V9h2v11zm4 0H7v-3h2v3zm0-5H7v-3h2v3zm0-5H7V7h2v3zm10 10h-8V4h8v16zm-2-12h-4v2h4V8zm0 4h-4v2h4v-2zm0 4h-4v2h4v-2z" />
                </svg>
            </div>
            <span class="logo-text">LMS <span>Apartment</span></span>
        </a>
    </div>

    <!-- Sidebar Main Menu Options -->
    <div class="sidebar-content">
        <p class="menu-label">Main Navigation</p>
        <ul class="sidebar-menu">
            <!-- Dashboard Link -->
            <li>
                <a href="{{ route('tenant.dashboard.page') }}" class="menu-item-link active">
                    <span class="menu-item-left">
                        <!-- Dashboard Grid Icon -->
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Dashboard
                    </span>
                </a>
            </li>

            <!-- Billings Link -->
            <li>
                <a href="#" class="menu-item-link">
                    <span class="menu-item-left">
                        <!-- Document/Billing Receipt Icon -->
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        My Billings
                    </span>
                </a>
            </li>

            <!-- Payments Link -->
            <li>
                <a href="#" class="menu-item-link">
                    <span class="menu-item-left">
                        <!-- Credit Card / Payments Icon -->
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        My Payments
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-avatar">{{ strtoupper(substr(Auth::guard('tenant')->user()->fullname ?? 'T', 0, 1)) }}</div>
        <div class="footer-user-info">
            <span class="footer-user-name">{{ Auth::guard('tenant')->user()->fullname ?? 'Tenant User' }}</span>
            <span class="footer-user-role">Tenant</span>
        </div>
    </div>
</aside>
