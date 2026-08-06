<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0fdfa;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 580px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0d9488, #115e59);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .body-content {
            padding: 32px 28px;
            line-height: 1.6;
            color: #334155;
        }
        .body-content p {
            margin-bottom: 20px;
            font-size: 15px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn-reset {
            display: inline-block;
            background-color: #0d9488;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }
        .note {
            font-size: 13px;
            color: #64748b;
            background-color: #f8fafc;
            border-left: 4px solid #0d9488;
            padding: 12px 16px;
            border-radius: 4px;
            margin-top: 24px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        .link-url {
            word-break: break-all;
            color: #0d9488;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>LMS Apartment Admin</h1>
        </div>
        <div class="body-content">
            <p>Hello Admin,</p>
            <p>You are receiving this email because we received a password reset request for your LMS Apartment administrator account.</p>
            
            <div class="btn-wrapper">
                <a href="{{ $resetUrl }}" class="btn-reset" target="_blank">Reset Password</a>
            </div>

            <p>This password reset link will expire in 60 minutes.</p>
            <p>If you did not request a password reset, no further action is required and your password will remain unchanged.</p>

            <div class="note">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
                <a href="{{ $resetUrl }}" class="link-url">{{ $resetUrl }}</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} LMS Apartment Management System. All rights reserved.
        </div>
    </div>
</body>
</html>
