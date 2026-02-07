<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>🎉 Welcome to Hijab Journey!</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #fdf2f8 0%, #ecfeff 30%, #f0fdfa 100%);
            background-color: #f5f6fa;
            color: #2d3748;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .email-wrapper {
            background: linear-gradient(135deg, #fdf2f8 0%, #ecfeff 30%, #f0fdfa 100%);
            padding: 40px 20px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(236, 118, 154, 0.15);
            overflow: hidden;
            border: 1px solid #F8C5C8;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #FC8EAC 0%, #EC769A 50%, #6EC6C5 100%);
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
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
        
        .celebration-icon {
            font-size: 64px;
            margin-bottom: 20px;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .header-icon {
            font-size: 40px;
        }
        
        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .header-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-weight: 500;
            line-height: 1.5;
        }
        
        .content-section {
            padding: 45px 35px;
        }
        
        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin: 0 0 20px 0;
            font-weight: 600;
        }
        
        .intro-text {
            font-size: 16px;
            color: #4a5568;
            margin: 0 0 30px 0;
            line-height: 1.8;
        }
        
        .credentials-card {
            background: linear-gradient(135deg, #fdf2f8 0%, #f0f9ff 100%);
            border-radius: 16px;
            padding: 28px;
            margin: 30px 0;
            border: 2px solid #F8C5C8;
            box-shadow: 0 4px 16px rgba(236, 118, 154, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .credentials-card::before {
            content: '🔐';
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 32px;
            opacity: 0.2;
        }
        
        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: #EC769A;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .credential-item {
            display: flex;
            align-items: center;
            padding: 16px;
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 12px;
            border-left: 4px solid #EC769A;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .credential-item:last-child {
            margin-bottom: 0;
        }
        
        .credential-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #FC8EAC 0%, #EC769A 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 16px;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(236, 118, 154, 0.3);
        }
        
        .credential-content {
            flex: 1;
        }
        
        .credential-label {
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 6px 0;
        }
        
        .credential-value {
            font-size: 17px;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            word-break: break-word;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        .security-note {
            background: #fff7ed;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 18px 20px;
            margin: 25px 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .security-icon {
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .security-text {
            font-size: 14px;
            color: #92400e;
            margin: 0;
            line-height: 1.6;
        }
        
        .cta-wrapper {
            margin: 35px 0 25px 0;
            text-align: center;
        }
        
        .cta-button {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #FC8EAC 0%, #EC769A 50%, #6EC6C5 100%);
            background-size: 200% auto;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 20px rgba(236, 118, 154, 0.4);
            transition: all 0.3s ease;
            border: none;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(236, 118, 154, 0.5);
        }
        
        .footer {
            background: linear-gradient(135deg, #f7fafc 0%, #f1f5f9 100%);
            padding: 35px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-logo {
            color: #EC769A;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        
        .footer-text {
            font-size: 13px;
            color: #718096;
            margin: 0;
            line-height: 1.7;
        }
        
        .footer-emoji {
            font-size: 20px;
            margin: 0 5px;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }
            
            .header-gradient {
                padding: 40px 20px;
            }
            
            .header-title {
                font-size: 26px;
            }
            
            .header-subtitle {
                font-size: 16px;
            }
            
            .content-section {
                padding: 30px 20px;
            }
            
            .credentials-card {
                padding: 20px;
            }
            
            .credential-item {
                flex-direction: column;
                text-align: center;
            }
            
            .credential-icon {
                margin-right: 0;
                margin-bottom: 12px;
            }
            
            .cta-button {
                padding: 16px 30px;
                font-size: 16px;
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
                        <span class="header-icon">🎓</span>
                    </div>
                    <div class="celebration-icon">🎉</div>
                    <h1 class="header-title">Congratulations, {{ $teacherName }}!</h1>
                    <p class="header-subtitle">Your application to become a teacher at <strong>Hijab Journey</strong> has been approved! ✨</p>
                </div>
            </div>
            
            <!-- Content Section -->
            <div class="content-section">
                <p class="greeting">Hello {{ $teacherName }},</p>
                <p class="intro-text">
                    We're absolutely thrilled to welcome you to our team of dedicated educators! 🌟 Your passion for teaching and commitment to Islamic education has impressed us, and we're excited to see the positive impact you'll make on our students' learning journey.
                </p>
                
                <!-- Credentials Card -->
                <div class="credentials-card">
                    <h2 class="card-title">
                        <span>🔑</span>
                        Your Login Credentials
                    </h2>
                    
                    <div class="credential-item">
                        <div class="credential-icon">✉️</div>
                        <div class="credential-content">
                            <div class="credential-label">Email Address</div>
                            <div class="credential-value">{{ $email }}</div>
                        </div>
                    </div>
                    
                    <div class="credential-item">
                        <div class="credential-icon">🔒</div>
                        <div class="credential-content">
                            <div class="credential-label">Temporary Password</div>
                            <div class="credential-value">{{ $password }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Security Note -->
                <div class="security-note">
                    <div class="security-icon">⚠️</div>
                    <p class="security-text">
                        <strong>Important:</strong> For your security, please change your password immediately after your first login. Keep your credentials safe and never share them with anyone.
                    </p>
                </div>
                
                <!-- Call to Action -->
                <div class="cta-wrapper">
                    <a href="{{ $loginUrl }}" class="cta-button">🚀 Go to Teacher Dashboard</a>
                </div>
                
                <p style="text-align: center; font-size: 15px; color: #718096; margin: 25px 0 0 0; line-height: 1.7;">
                    We're here to support you every step of the way. If you have any questions or need assistance, don't hesitate to reach out to our team. Welcome aboard! 💝
                </p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p class="footer-logo">Hijab Journey <span class="footer-emoji">🌸</span></p>
                <p class="footer-text">
                    &copy; {{ date('Y') }} Hijab Journey. All rights reserved.<br>
                    Empowering Islamic education, one lesson at a time.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
