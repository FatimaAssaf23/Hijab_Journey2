<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Hijab Journey</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            padding: 32px 28px;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 1.7rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: #3b82f6;
        }
        .header p {
            color: #64748b;
            font-size: 1rem;
            margin: 0;
        }
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 24px 0;
        }
        .content {
            margin-bottom: 24px;
        }
        .credentials {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        .credentials strong {
            display: inline-block;
            width: 110px;
            color: #3b82f6;
            font-size: 0.97rem;
        }
        .cta {
            display: block;
            width: 100%;
            text-align: center;
            background: #3b82f6;
            color: #fff;
            text-decoration: none;
            padding: 12px 0;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin: 24px 0 0 0;
            transition: background 0.2s;
        }
        .cta:hover {
            background: #2563eb;
        }
        .footer {
            text-align: center;
            color: #94a3b8;
            font-size: 0.95rem;
            margin-top: 32px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Welcome, {{ $teacherName }}!</h1>
        <p>Your application to become a teacher at <strong>Hijab Journey</strong> has been approved.</p>
    </div>
    <hr class="divider">
    <div class="content">
        <p>We're excited to have you join our team of educators. Here are your login credentials:</p>
        <div class="credentials">
            <div><strong>Email:</strong> {{ $email }}</div>
            <div><strong>Password:</strong> {{ $password }}</div>
        </div>
        <p style="margin-top:18px;">For your security, please change your password after your first login.</p>
        <a href="{{ $loginUrl }}" class="cta">Go to Dashboard</a>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Hijab Journey. All rights reserved.
    </div>
</div>
</body>
</html>
