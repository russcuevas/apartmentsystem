<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LMS Apartment</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Externalized Theme Stylesheets -->
    <link rel="stylesheet" href="{{ asset('auth/admins/style.css') }}">

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
</head>

<body>

    <div class="login-wrapper">
        <!-- Left Panel: Form Side -->
        <div class="form-side">
            <div class="form-content">

                <!-- Brand Identifier -->
                <div class="brand">
                    <div class="brand-logo">
                        <svg viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z" />
                        </svg>
                    </div>
                    <span class="brand-name">LMS<span> Apartment</span></span>
                </div>

                <!-- Header -->
                <div class="login-header">
                    <h1>Set new password</h1>
                    <p>Enter your email and create a new secure password for your admin account.</p>
                </div>

                <!-- Errors Display -->
                @if ($errors->any())
                    <div class="alert">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            <strong>Password reset failed:</strong>
                            <ul style="list-style-type: none; margin-top: 4px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Reset Form -->
                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email', $email) }}" placeholder="admin@example.com" required readonly>
                            <div class="input-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- New Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter new password (min. 6 characters)" required autofocus>
                            <div class="input-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <button type="button" class="password-toggle" id="passwordToggle1"
                                aria-label="Toggle Password Visibility">
                                <svg id="eyeIcon1" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Re-enter new password" required>
                            <div class="input-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <button type="button" class="password-toggle" id="passwordToggle2"
                                aria-label="Toggle Confirm Password Visibility">
                                <svg id="eyeIcon2" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Options Row -->
                    <div class="form-options">
                        <a href="{{ route('admin.login.page') }}" class="forgot-link">← Back to Login</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        Reset Password
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>
                </form>

            </div>
        </div>

        <!-- Right Panel: Image Side -->
        <div class="image-side">
            <div class="glass-overlay">
                <span class="glass-tag">Apartment Management System</span>
                <h2 class="glass-title">Secure & Efficient Property Management.</h2>
                <p class="glass-desc">Your security is our top priority. Password updates are encrypted and stored safely.</p>
            </div>
        </div>
    </div>

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notyf = new Notyf({
                duration: 4000,
                position: { x: 'right', y: 'top' },
                dismissible: true
            });

            @if(session('success'))
                notyf.success(@json(session('success')));
            @endif

            @if(session('error'))
                notyf.error(@json(session('error')));
            @endif

            // Password Toggles
            const passInput1 = document.getElementById('password');
            const toggleBtn1 = document.getElementById('passwordToggle1');
            if (toggleBtn1 && passInput1) {
                toggleBtn1.addEventListener('click', function() {
                    const type = passInput1.getAttribute('type') === 'password' ? 'text' : 'password';
                    passInput1.setAttribute('type', type);
                });
            }

            const passInput2 = document.getElementById('password_confirmation');
            const toggleBtn2 = document.getElementById('passwordToggle2');
            if (toggleBtn2 && passInput2) {
                toggleBtn2.addEventListener('click', function() {
                    const type = passInput2.getAttribute('type') === 'password' ? 'text' : 'password';
                    passInput2.setAttribute('type', type);
                });
            }
        });
    </script>
</body>
</html>
