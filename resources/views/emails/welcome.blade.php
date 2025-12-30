<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Welcome to {{ config('app.name') }}</title>
    <style type="text/css">
        body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            padding: 60px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .content {
            padding: 48px 40px;
        }
        .greeting {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }
        .message {
            font-size: 17px;
            line-height: 1.7;
            color: #475569;
            margin-bottom: 32px;
        }
        .feature-grid {
            margin-bottom: 40px;
        }
        .feature-card {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
        }
        .feature-card table {
            width: 100%;
        }
        .feature-icon {
            font-size: 28px;
            width: 50px;
            text-align: center;
        }
        .feature-text {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            padding-left: 16px;
        }
        .cta-container {
            text-align: center;
            padding: 20px 0;
        }
        .button {
            background-color: #4f46e5;
            color: #ffffff !important;
            display: inline-block;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }
        .footer {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
            font-size: 14px;
        }
        .footer p {
            margin: 6px 0;
        }
        .footer a {
            color: #6366f1;
            text-decoration: none;
        }
        
        @media only screen and (max-width: 600px) {
            .content {
                padding: 32px 20px;
            }
            .header {
                padding: 40px 20px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <center>
            <table class="main" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="header">
                        <h1>{{ config('app.name') }}</h1>
                    </td>
                </tr>
                <tr>
                    <td class="content">
                        <div class="greeting">Hi {{ $user->name }}, Welcome! 👋</div>
                        <p class="message">
                            We're absolutely thrilled to have you on board! You've just taken the first step towards mastering new skills with {{ config('app.name') }}.
                        </p>

                        <div class="feature-grid">
                            <div class="feature-card">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="feature-icon">🚀</td>
                                        <td class="feature-text">Instantly access premium courses</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="feature-card">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="feature-icon">📈</td>
                                        <td class="feature-text">Track your learning progress daily</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="feature-card">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="feature-icon">💬</td>
                                        <td class="feature-text">Join discussions with instructors</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="cta-container">
                            <a href="{{ route('student.dashboard') }}" class="button">Explore Dashboard</a>
                        </div>

                        <p class="message" style="margin-top: 40px; margin-bottom: 0; text-align: center; font-style: italic;">
                            "The beautiful thing about learning is that no one can take it away from you."
                        </p>
                        
                        <div style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                            <p class="message" style="margin-bottom: 0;">
                                Happy Learning,<br>
                                <strong>The {{ config('app.name') }} Team</strong>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>
                    <a href="#">Help Center</a> &bull; 
                    <a href="#">Privacy Policy</a> &bull; 
                    <a href="#">Unsubscribe</a>
                </p>
            </div>
        </center>
    </div>
</body>
</html>
