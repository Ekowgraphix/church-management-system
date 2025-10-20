<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h2 {
            color: #16a34a;
            margin-top: 0;
        }
        .verify-button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .verify-button:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px 30px;
            text-align: center;
            color: #666666;
            font-size: 12px;
        }
        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 Verify Your Email</h1>
        </div>
        
        <div class="email-body">
            <h2>Hello {{ $user->name }}!</h2>
            
            <p>Thank you for joining our church community! We're excited to have you with us.</p>
            
            <p>To complete your registration and activate your account, please verify your email address by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="verify-button">
                    Verify Email Address
                </a>
            </div>
            
            <div class="divider"></div>
            
            <p style="color: #666; font-size: 14px;">
                If the button above doesn't work, copy and paste the following link into your browser:
            </p>
            <p style="color: #16a34a; word-break: break-all; font-size: 12px;">
                {{ $url }}
            </p>
            
            <div class="divider"></div>
            
            <p style="color: #999; font-size: 12px;">
                <strong>Note:</strong> This verification link will expire in 24 hours for security reasons.
            </p>
            
            <p style="color: #999; font-size: 12px;">
                If you didn't create an account with us, please ignore this email.
            </p>
        </div>
        
        <div class="email-footer">
            <p>© 2025 Church Management System. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
