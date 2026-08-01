<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Management System</title>

    <!-- Google Fonts: Plus Jakarta Sans for premium typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Externalized Tenant Stylesheets -->
    <link rel="stylesheet" href="{{ asset('auth/tenants/style.css') }}">


</head>

<body>

    <!-- Glassmorphic Login Card -->
    <div class="login-card">

        <!-- Brand / Identity -->
        <div class="brand">
            <div class="brand-logo">
                <!-- Friendly Home Key SVG Icon -->
                <svg viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z" />
                </svg>
            </div>
            <span class="brand-name">LSM<span> Apartment</span></span>
        </div>

        <!-- Header -->
        <div class="login-header">
            <h1>Tenant Portal</h1>
            <p>Access your billing statements, rent details, and payment histories.</p>
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
                    <strong>Access denied:</strong>
                    <ul style="list-style-type: none; margin-top: 4px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('tenant.login.page') }}" method="POST">
            @csrf

            <!-- Phone Number Input -->
            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number</label>
                <div class="input-wrapper">
                    <input type="tel" id="phone_number" name="phone_number" class="form-control"
                        placeholder="09xxxxxxxxx" value="{{ old('phone_number') }}" required autofocus>
                    <div class="input-icon">
                        <!-- Phone Icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Enter your tenant password" required>
                    <div class="input-icon">
                        <!-- Lock Icon -->
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
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
                Sign In
                <!-- Key/Right Arrow SVG Icon -->
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>

    </div>

    <!-- Shared Toggle Script -->
    <script src="{{ asset('auth/script.js') }}" defer></script>
</body>

</html>
