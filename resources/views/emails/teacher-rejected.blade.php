<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Application Update</title>
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
                    font-size: 1.5rem;
                    font-weight: 700;
                    margin: 0 0 8px 0;
                    color: #ef4444;
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
                .reason {
                    background: #fef2f2;
                    border-left: 4px solid #ef4444;
                    border-radius: 8px;
                    padding: 14px 16px;
                    margin: 18px 0 12px 0;
                    color: #b91c1c;
                }
                .footer {
                    text-align: center;
                    color: #94a3b8;
                    font-size: 0.95rem;
                    margin-top: 32px;
                }
                .cta {
                    display: block;
                    width: 100%;
                    text-align: center;
                    background: #ef4444;
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
                    background: #dc2626;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <div class="header">
                <h1>Application Update</h1>
                <p>Dear {{ $teacherName }},</p>
            </div>
            <hr class="divider">
            <div class="content">
                <p>Thank you for your interest in becoming a teacher at <strong>Hijab Journey</strong>.</p>
                <div class="reason">
                    <strong>Reason for Rejection:</strong><br>
                    {{ $rejectionReason }}
                </div>
                <p>If you have questions or would like more feedback, please contact our support team.</p>
                <a href="{{ url('/become-teacher') }}" class="cta">Apply Again</a>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Hijab Journey. All rights reserved.
            </div>
        </div>
        </body>
        </html>
            border-radius: 50px;
