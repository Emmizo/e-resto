<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #f3f5f8; font-family: 'DM Sans', Arial, sans-serif; margin: 0; padding: 0; }
        .mail-container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(24,76,85,0.10); padding: 0 0 32px 0; overflow: hidden; }
        .header { background: linear-gradient(90deg, #184C55 60%, #2e7d91 100%); padding: 32px 24px 16px 24px; text-align: center; }
        .logo { height: 48px; margin-bottom: 8px; }
        .restaurant-logo { height: 40px; border-radius: 8px; background: #fff; margin-bottom: 8px; }
        .heading { font-size: 22px; color: #fff; font-weight: 700; margin-bottom: 4px; }
        .subheading { color: #e0f7fa; font-size: 15px; margin-bottom: 0; }
        .divider { border: none; border-top: 1px solid #e0e0e0; margin: 24px 0; }
        .content { padding: 0 24px; }
        .card { background: #f8fafb; border-radius: 12px; padding: 20px 18px; margin-bottom: 18px; box-shadow: 0 2px 8px rgba(24,76,85,0.04); }
        .card strong { color: #184C55; }
        .desc { color: #444; font-size: 15px; margin-bottom: 18px; }
        .otp-box { display: inline-block; background: #184C55; color: #fff; font-size: 32px; font-weight: bold; letter-spacing: 6px; border-radius: 8px; padding: 16px 32px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(24,76,85,0.08); }
        .cta-btn { display: inline-block; background: #184C55; color: #fff !important; font-size: 16px; font-weight: 600; border-radius: 8px; padding: 12px 32px; text-decoration: none; margin: 18px 0 0 0; box-shadow: 0 2px 8px rgba(24,76,85,0.08); transition: background 0.2s; }
        .cta-btn:hover { background: #133a41; }
        .footer { font-size: 13px; color: #888; margin-top: 32px; text-align: center; padding: 0 24px; }
        @media (max-width: 600px) { .mail-container { padding: 0; } .content { padding: 0 4vw; } .otp-box { font-size: 24px; padding: 12px 16px; } }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Platform Logo" class="logo">
            @if(isset($restaurant_image) && $restaurant_image)
                <img src="{{ $restaurant_image }}" alt="{{ $restaurant_name }} Logo" class="restaurant-logo">
            @endif
            <div class="heading">Your 2FA Verification Code</div>
            <div class="subheading">{{ $restaurant_name ?? config('app.name') }}</div>
            @if(isset($restaurant_address) && $restaurant_address)
                <div style="color:#fff; font-size:13px; margin-top:2px;">{{ $restaurant_address }}</div>
            @endif
        </div>
        <div class="content">
            <div class="desc">Use the code below to complete your Two-Factor Authentication setup. For your security, do not share this code with anyone.</div>
            <div class="card">
                <div class="otp-box">{{ $otp }}</div>
            </div>
            <a href="{{ url('/login') }}" class="cta-btn">Login to Your Account</a>
            <hr class="divider">
        </div>
        <div class="footer">
            This code expires in 30 seconds.<br>If you did not request this, you can safely ignore this email.<br><br>
            Best regards,<br>{{ config('app.name') }} Team<br>
            <span style="color:#184C55; font-weight:600;">Support:</span> <a href="mailto:support@restofinder.com" style="color:#184C55; text-decoration:underline;">support@restofinder.com</a><br>
            &copy; {{ date('Y') }} RestoFinder
        </div>
    </div>
</body>
</html>
