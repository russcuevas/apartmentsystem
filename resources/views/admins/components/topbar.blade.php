<header class="topbar-header">
    <div class="topbar-left">
        <!-- Responsive Mobile Hamburger Toggle Menu -->
        <button class="menu-toggle-btn" id="menuToggleBtn" aria-label="Toggle Navigation Menu">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div class="topbar-right">
        <!-- Notifications Wrapper -->
        {{-- <div class="nav-action-wrapper">
            <button class="nav-action-btn" id="notificationBtn" aria-label="View Notifications">
                <!-- Bell Icon -->
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <span class="btn-badge">3</span>
            </button>

            <!-- Notifications Dropdown Menu Card -->
            <div class="dropdown-menu-card" id="notificationDropdown">
                <div class="dropdown-card-header">
                    <span class="dropdown-card-title">Recent Alerts</span>
                    <a href="#" class="dropdown-card-action">Mark all as read</a>
                </div>
                <ul class="dropdown-list">
                    <li class="dropdown-list-item">
                        <div class="item-icon-wrapper success">
                            <!-- Dollar/Cash payment icon -->
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 8h6m-6 2h3m-3 4h3m-6 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="item-content">
                            <span class="item-text">Proof of Payment uploaded by Tala Tenant (Unit 4B)</span>
                            <span class="item-time">5 mins ago</span>
                        </div>
                    </li>
                    <li class="dropdown-list-item">
                        <div class="item-icon-wrapper error">
                            <!-- Warning/Alert icon -->
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="item-content">
                            <span class="item-text">Billing Overdue Warning: Silang Unit 10</span>
                            <span class="item-time">2 hours ago</span>
                        </div>
                    </li>
                    <li class="dropdown-list-item">
                        <div class="item-icon-wrapper success">
                            <!-- Check/New user icon -->
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                        </div>
                        <div class="item-content">
                            <span class="item-text">New tenant account registration: Balai (Unit 1A)</span>
                            <span class="item-time">1 day ago</span>
                        </div>
                    </li>
                </ul>
                <div class="dropdown-card-footer">
                    <a href="#" class="dropdown-footer-link">See all alerts</a>
                </div>
            </div>
        </div> --}}

        <!-- User Profile Wrapper -->
        <div class="nav-action-wrapper">
            <button class="user-profile-trigger" id="userProfileBtn" aria-label="User Account Options">
                <div class="footer-avatar"
                    style="border: 2px solid var(--primary); font-size: 0.95rem; width: 38px; height: 38px;">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->fullname ?? 'A', 0, 1)) }}</div>
                <div class="profile-info">
                    <div class="profile-name">{{ Auth::guard('admin')->user()->fullname ?? 'Admin User' }}</div>
                    <div class="profile-role">Administrator</div>
                </div>
                <!-- Dropdown Icon -->
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- User Settings Dropdown Card -->
            <div class="dropdown-menu-card user-menu-dropdown" id="userProfileDropdown">
                <div class="dropdown-card-header">
                    <span class="dropdown-card-title">Manage Account</span>
                </div>
                <a href="#" class="user-menu-item">
                    <!-- User Icon -->
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    My Account
                </a>

                <a href="#" class="user-menu-item logout"
                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <!-- Logout arrow-left icon -->
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Log out
                </a>

                <!-- Form submission for logout -->
                <form id="admin-logout-form" action="{{ route('admin.logout.request') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>
