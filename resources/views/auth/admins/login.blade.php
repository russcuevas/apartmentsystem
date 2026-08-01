<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Apartment</title>

    <!-- Google Fonts: Plus Jakarta Sans for premium typography -->
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
        <!-- Left Panel: Login Form -->
        <div class="form-side">
            <div class="form-content">

                <!-- Brand Identifier -->
                <div class="brand">
                    <div class="brand-logo">
                        <!-- Custom SVG Building/Apartment Logo -->
                        <svg viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z" />
                        </svg>
                    </div>
                    <span class="brand-name">LSM<span> Apartment</span></span>
                </div>

                <!-- Form Header -->
                <div class="login-header">
                    <h1>Login page</h1>
                    <p>Enter your details below to access the management dashboard.</p>
                </div>

                <!-- Validation Errors Display -->
                @if ($errors->any())
                    <div class="alert">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            <strong>Login failed:</strong>
                            <ul style="list-style-type: none; margin-top: 4px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('admin.login.request') }}" method="POST">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            <div class="input-icon">
                                <!-- Envelope Icon -->
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

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Enter your password" required>
                            <div class="input-icon">
                                <!-- Lock Icon -->
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>

                            <!-- Toggle Visibility Button -->
                            <button type="button" class="password-toggle" id="passwordToggle"
                                aria-label="Toggle Password Visibility">
                                <!-- Eye Icon -->
                                <svg id="eyeIcon" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="form-options">
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        Sign in
                        <!-- Right Arrow Icon -->
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>

            </div>
        </div>

        <!-- Right Panel: Image Display -->
        <div class="image-side">
            <div class="glass-overlay">
                <span class="glass-tag">Apartment Management System</span>
                <h2 class="glass-title">Smart Apartment Management Made Effortless.</h2>
                <p class="glass-desc">Track monthly billing, record payments, and manage occupant directories all
                    inside a unified, secure workspace.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('auth/script.js') }}" defer></script>

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
        });
    </script>
</body>
</html>
