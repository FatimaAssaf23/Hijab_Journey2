<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New User Registration Notification</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
    <style>
        /* Reset styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
        
        /* Main styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-color: #f5f7fa;
            color: #2d3748;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .email-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .header-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
            opacity: 0.3;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .icon-wrapper {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .header-icon {
            font-size: 32px;
        }
        
        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .header-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-weight: 400;
        }
        
        .content-section {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin: 0 0 16px 0;
            font-weight: 600;
        }
        
        .intro-text {
            font-size: 15px;
            color: #4a5568;
            margin: 0 0 30px 0;
            line-height: 1.7;
        }
        
        .user-card {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-grid {
            display: grid;
            gap: 16px;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            padding: 16px;
            background: #ffffff;
            border-radius: 8px;
            border-left: 3px solid #667eea;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .info-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 16px;
            color: #ffffff;
            font-size: 18px;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            word-break: break-word;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }
        
        .badge-student {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .badge-teacher {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        .badge-admin {
            background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
            color: #9f1239;
            border: 1px solid #f9a8d4;
        }
        
        .cta-wrapper {
            margin: 30px 0 20px 0;
            text-align: center;
        }
        
        .cta-button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            border: none;
            text-align: center;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-text {
            font-size: 13px;
            color: #718096;
            margin: 0;
            line-height: 1.6;
        }
        
        .footer-logo {
            color: #667eea;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }
            
            .header-gradient {
                padding: 30px 20px;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .content-section {
                padding: 30px 20px;
            }
            
            .user-card {
                padding: 20px;
            }
            
            .info-item {
                flex-direction: column;
                text-align: center;
            }
            
            .info-icon {
                margin-right: 0;
                margin-bottom: 12px;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background: #1a202c;
            }
            
            .greeting,
            .info-value {
                color: #f7fafc;
            }
            
            .intro-text {
                color: #cbd5e0;
            }
            
            .user-card {
                background: #2d3748;
                border-color: #4a5568;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header with Gradient -->
            <div class="header-gradient">
                <div class="header-content">
                    <div class="icon-wrapper">
                        <span class="header-icon">👤</span>
                    </div>
                    <h1 class="header-title">New User Registration</h1>
                    <p class="header-subtitle">{{ config('app.name', 'Hijab Journey') }}</p>
                </div>
            </div>
            
            <!-- Content Section -->
            <div class="content-section">
                <p class="greeting">Hello Admin,</p>
                <p class="intro-text">
                    A new user has successfully registered on the platform. Below are the registration details for your review.
                </p>
                
                <!-- User Information Card -->
                <div class="user-card">
                    <h2 class="card-title">
                        <span>📋</span>
                        Registration Details
                    </h2>
                    
                    <div class="info-grid">
                        <!-- Name -->
                        <div class="info-item">
                            <div class="info-icon">👤</div>
                            <div class="info-content">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $user->first_name }} {{ $user->last_name }}</div>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="info-item">
                            <div class="info-icon">✉️</div>
                            <div class="info-content">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                        </div>
                        
                        <!-- Role -->
                        <div class="info-item">
                            <div class="info-icon">🎭</div>
                            <div class="info-content">
                                <div class="info-label">User Role</div>
                                <div class="info-value">
                                    <span class="badge badge-{{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Country -->
                        @if($user->country)
                        <div class="info-item">
                            <div class="info-icon">🌍</div>
                            <div class="info-content">
                                <div class="info-label">Country</div>
                                <div class="info-value">{{ $user->country }}</div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Registration Date -->
                        <div class="info-item">
                            <div class="info-icon">🕒</div>
                            <div class="info-content">
                                <div class="info-label">Registration Date</div>
                                <div class="info-value">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="cta-wrapper">
                    <a href="{{ url('/admin') }}" class="cta-button">View in Admin Dashboard →</a>
                </div>
                
                <p style="text-align: center; font-size: 14px; color: #718096; margin: 20px 0 0 0;">
                    Manage users, view analytics, and monitor platform activity from your admin dashboard.
                </p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p class="footer-logo">{{ config('app.name', 'Hijab Journey') }}</p>
                <p class="footer-text">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Hijab Journey') }}. All rights reserved.<br>
                    This is an automated notification. Please do not reply to this email.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
