<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f3f5f8;
            font-family: 'DM Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .mail-container {
            max-width: 420px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(24,76,85,0.08);
            padding: 32px 24px 24px 24px;
            text-align: center;
        }
        .brand {
            font-size: 28px;
            font-weight: bold;
            color: #184C55;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        .heading {
            font-size: 20px;
            color: #184C55;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .desc {
            color: #444;
            font-size: 15px;
            margin-bottom: 24px;
        }
        .otp-box {
            display: inline-block;
            background: #184C55;
            color: #fff;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 6px;
            border-radius: 8px;
            padding: 16px 32px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(24,76,85,0.08);
        }
        .footer {
            font-size: 13px;
            color: #888;
            margin-top: 24px;
        }
        @media (max-width: 500px) {
            .mail-container { padding: 18px 4vw; }
            .otp-box { font-size: 24px; padding: 12px 16px; }
        }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="brand">RestoFinder</div>
        <div class="heading">Your 2FA Verification Code</div>
        <div class="desc">Use the code below to complete your Two-Factor Authentication setup. For your security, do not share this code with anyone.</div>
        <div class="otp-box">{{ $otp }}</div>
        <div class="footer">This code expires in 30 seconds.<br>If you did not request this, you can safely ignore this email.<br><br>&copy; {{ date('Y') }} RestoFinder</div>
    </div>
</body>
</html>
