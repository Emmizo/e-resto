<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #f3f5f8; font-family: 'DM Sans', Arial, sans-serif; margin: 0; padding: 0; }
        .mail-container { max-width: 420px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(24,76,85,0.08); padding: 32px 24px 24px 24px; text-align: center; }
        .brand { font-size: 28px; font-weight: bold; color: #184C55; margin-bottom: 12px; letter-spacing: 1px; }
        .heading { font-size: 20px; color: #184C55; margin-bottom: 8px; font-weight: 600; }
        .desc { color: #444; font-size: 15px; margin-bottom: 24px; }
        .credentials { background: #f3f5f8; border-radius: 8px; padding: 16px; margin: 18px 0 24px 0; text-align: left; display: inline-block; min-width: 220px; }
        .credentials strong { color: #184C55; }
        .footer { font-size: 13px; color: #888; margin-top: 24px; }
        @media (max-width: 500px) { .mail-container { padding: 18px 4vw; } .credentials { min-width: 0; width: 100%; } }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="brand">RestoFinder</div>
        <div class="heading">Hello {{ $first_name }},</div>
        <div class="desc">Your account password has been successfully reset. You can login with the credentials below:</div>
        <div class="credentials">
            <div><strong>Email Address:</strong> {{ $email }}</div>
            <div><strong>Password:</strong> <span style="font-family:monospace;">{{ $password }}</span></div>
        </div>
        <div class="footer">If you have any issues, please contact support.<br><br>&copy; {{ date('Y') }} RestoFinder</div>
    </div>
</body>
</html>
