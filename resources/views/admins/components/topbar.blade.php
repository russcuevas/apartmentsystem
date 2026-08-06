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
                <a href="javascript:void(0)" class="user-menu-item" id="openAdminAccountBtn">
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

<style>
    /* Admin Account Self-Contained Modal Overlay & Layout Styles */
    #adminAccountModal.modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        z-index: 10040;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease-in-out;
    }

    #adminAccountModal.modal-overlay.active {
        opacity: 1 !important;
        visibility: visible !important;
    }

    #adminAccountModal .modal-container-lg {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 760px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        transform: translateY(20px);
        transition: transform 0.25s ease-in-out;
        overflow: hidden;
    }

    #adminAccountModal.modal-overlay.active .modal-container-lg {
        transform: translateY(0);
    }

    #adminAccountModal .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
    }

    #adminAccountModal .modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    #adminAccountModal .modal-close-btn {
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

    #adminAccountModal .modal-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    #adminAccountModal .modal-body {
        padding: 24px;
        overflow-y: auto;
        max-height: calc(90vh - 80px);
        -webkit-overflow-scrolling: touch;
    }

    /* Admin Form Custom Inputs */
    #adminAccountModal .admin-account-input {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease-in-out;
        box-sizing: border-box;
        font-family: inherit;
    }

    #adminAccountModal .admin-account-input:focus {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15) !important;
    }

    #adminAccountModal .admin-account-btn-secondary {
        background: #e2e8f0;
        color: #334155;
        font-weight: 700;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }

    #adminAccountModal .admin-account-btn-secondary:hover {
        background: #cbd5e1;
    }

    #adminAccountModal .admin-account-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #ffffff !important;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        transition: all 0.2s ease-in-out;
    }

    #adminAccountModal .admin-account-btn-primary:hover {
        background: linear-gradient(135deg, #0f766e, #115e59);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(13, 148, 136, 0.35);
    }
</style>

<!-- MODAL: ADMIN MY ACCOUNT MODAL -->
<div class="modal-overlay @if ($errors->has('fullname') || $errors->has('email') || $errors->has('current_password') || $errors->has('new_password')) active @endif" id="adminAccountModal">
    <div class="modal-container-lg">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">⚙️ Admin Account Settings</h3>
                <p style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                    Edit your profile details, contact information, and update your security password.
                </p>
            </div>
            <button type="button" class="modal-close-btn" id="closeAdminAccountBtn">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.account.update') }}" method="POST" id="adminAccountUpdateForm">
                @csrf

                <!-- SECTION 1: Editable Profile Information -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 24px;">
                    <div style="font-size: 0.88rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;">
                        <span>📋 Account Profile Information</span>
                        <span style="font-size: 0.72rem; padding: 3px 10px; border-radius: 20px; background: #e0f2fe; color: #0369a1; font-weight: 800; border: 1px solid #bae6fd;">
                            Administrator
                        </span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Full Name <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="fullname" class="admin-account-input" value="{{ Auth::guard('admin')->user()->fullname ?? '' }}" style="width: 100%;" required>
                            @error('fullname')
                                <span style="color: #dc2626; font-size: 0.78rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                            <div>
                                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                    Email Address (Login Email) <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="email" name="email" class="admin-account-input" value="{{ Auth::guard('admin')->user()->email ?? '' }}" style="width: 100%;" required>
                                @error('email')
                                    <span style="color: #dc2626; font-size: 0.78rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                    Phone Number (Contact No.)
                                </label>
                                <input type="text" name="phone_number" class="admin-account-input" value="{{ Auth::guard('admin')->user()->phone_number ?? '' }}" placeholder="09xxxxxxxxx" style="width: 100%;">
                                @error('phone_number')
                                    <span style="color: #dc2626; font-size: 0.78rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Update Password (Optional) -->
                <div style="background: #ffffff; border: 1.5px solid #0d9488; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.08);">
                    <div style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                        <span>🔒 Security Password Update</span>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 14px;">
                        Leave password fields blank if you do not wish to change your current password.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Current Password
                            </label>
                            <input type="password" name="current_password" class="admin-account-input" style="width: 100%; height: 42px;" placeholder="Required only if changing password...">
                            @error('current_password')
                                <span style="color: #dc2626; font-size: 0.78rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                            <div>
                                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                    New Password
                                </label>
                                <input type="password" name="new_password" class="admin-account-input" style="width: 100%; height: 42px;" placeholder="Min. 6 characters...">
                                @error('new_password')
                                    <span style="color: #dc2626; font-size: 0.78rem; font-weight: 700; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                    Confirm New Password
                                </label>
                                <input type="password" name="new_password_confirmation" class="admin-account-input" style="width: 100%; height: 42px;" placeholder="Re-type new password...">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="admin-account-btn-secondary" id="cancelAdminAccountBtn">Close</button>
                    <button type="submit" class="admin-account-btn-primary">✓ Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', function(e) {
        // Open Modal when clicking My Account in Admin Topbar
        const openBtn = e.target.closest('#openAdminAccountBtn');
        if (openBtn) {
            e.preventDefault();
            e.stopPropagation();
            const userProfileDropdown = document.getElementById('userProfileDropdown');
            if (userProfileDropdown) userProfileDropdown.classList.remove('show');
            const modal = document.getElementById('adminAccountModal');
            if (modal) modal.classList.add('active');
            return;
        }

        // Close Modal when clicking Close / Cancel buttons or Overlay
        const closeBtn = e.target.closest('#closeAdminAccountBtn') || e.target.closest('#cancelAdminAccountBtn');
        const modal = document.getElementById('adminAccountModal');
        if (closeBtn && modal) {
            e.preventDefault();
            modal.classList.remove('active');
            return;
        }

        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
</script>
