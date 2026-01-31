<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Auto-Created Class Notification</title>
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
            background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%);
            background-color: #f5f7fa;
            color: #2d3748;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .email-wrapper {
            background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%);
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
            background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
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
        
        .class-card {
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
            color: #FC8EAC;
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
            border-left: 3px solid #FC8EAC;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%);
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
        
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .alert-text {
            font-size: 14px;
            color: #856404;
            margin: 0;
            line-height: 1.6;
        }
        
        .cta-wrapper {
            margin: 30px 0 20px 0;
            text-align: center;
        }
        
        .cta-button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #FC8EAC 0%, #6EC6C5 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 15px rgba(252, 142, 172, 0.4);
            transition: all 0.3s ease;
            border: none;
            text-align: center;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(252, 142, 172, 0.5);
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
            color: #FC8EAC;
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
            
            .class-card {
                padding: 20px;
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
                        <span class="header-icon">📚</span>
                    </div>
                    <h1 class="header-title">New Class Auto-Created</h1>
                    <p class="header-subtitle">{{ config('app.name', 'Hijab Journey') }}</p>
                </div>
            </div>
            
            <!-- Content Section -->
            <div class="content-section">
                <p class="greeting">Hello Admin,</p>
                <p class="intro-text">
                    A new class has been automatically created because all existing classes were full when a new student registered.
                </p>
                
                <!-- Alert Box -->
                <div class="alert-box">
                    <p class="alert-text">
                        <strong>⚠️ Action Required:</strong> Please review the new class details and assign a teacher if needed. The class has been temporarily assigned to the teacher with the fewest classes.
                    </p>
                </div>
                
                <!-- Class Information Card -->
                <div class="class-card">
                    <h2 class="card-title">
                        <span>📋</span>
                        Class Details
                    </h2>
                    
                    <div class="info-grid">
                        <!-- Class Name -->
                        <div class="info-item">
                            <div class="info-icon">📚</div>
                            <div class="info-content">
                                <div class="info-label">Class Name</div>
                                <div class="info-value">{{ $class->class_name }}</div>
                            </div>
                        </div>
                        
                        <!-- Teacher -->
                        <div class="info-item">
                            <div class="info-icon">👨‍🏫</div>
                            <div class="info-content">
                                <div class="info-label">Assigned Teacher</div>
                                <div class="info-value">
                                    @if($class->teacher)
                                        {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                                    @else
                                        <span style="color: #e53e3e;">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Capacity -->
                        <div class="info-item">
                            <div class="info-icon">👥</div>
                            <div class="info-content">
                                <div class="info-label">Capacity</div>
                                <div class="info-value">{{ $class->capacity }} students</div>
                            </div>
                        </div>
                        
                        <!-- Current Enrollment -->
                        <div class="info-item">
                            <div class="info-icon">✅</div>
                            <div class="info-content">
                                <div class="info-label">Current Enrollment</div>
                                <div class="info-value">{{ $class->current_enrollment }} student(s)</div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="info-item">
                            <div class="info-icon">📊</div>
                            <div class="info-content">
                                <div class="info-label">Status</div>
                                <div class="info-value" style="text-transform: capitalize;">{{ $class->status }}</div>
                            </div>
                        </div>
                        
                        <!-- Created Date -->
                        <div class="info-item">
                            <div class="info-icon">🕒</div>
                            <div class="info-content">
                                <div class="info-label">Created Date</div>
                                <div class="info-value">{{ $class->created_at->format('F j, Y \a\t g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="cta-wrapper">
                    <a href="{{ url('/admin/classes') }}" class="cta-button">Manage Classes →</a>
                </div>
                
                <p style="text-align: center; font-size: 14px; color: #718096; margin: 20px 0 0 0;">
                    Review and manage the new class from your admin dashboard.
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
