<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the Team</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #f3f5f8; font-family: 'DM Sans', Arial, sans-serif; margin: 0; padding: 0; }
        .mail-container { max-width: 420px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(24,76,85,0.08); padding: 32px 24px 24px 24px; text-align: center; }
        .brand { font-size: 28px; font-weight: bold; color: #184C55; margin-bottom: 12px; letter-spacing: 1px; }
        .heading { font-size: 20px; color: #184C55; margin-bottom: 8px; font-weight: 600; }
        .desc { color: #444; font-size: 15px; margin-bottom: 24px; }
        .footer { font-size: 13px; color: #888; margin-top: 24px; }
        @media (max-width: 500px) { .mail-container { padding: 18px 4vw; } }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="brand">RestoFinder</div>
        <div class="heading">Welcome to the Team, {{ $user->first_name }}!</div>
        <div class="desc">
            We’re delighted to officially welcome you to <b>{{ $data['users']->restaurant_name }}</b>!<br><br>
            It’s great to have you as part of our team, and we’re excited about the journey ahead. We truly believe that your skills and talents will be a valuable addition to our company.<br><br>
            We hope you’ve been settling in well and getting to know your colleagues. If there’s anything you need or any questions you have, please don’t hesitate to reach out. We’re all here to support you and ensure you have a smooth transition.<br><br>
            Looking forward to achieving great things together!<br>Once again, welcome aboard!
        </div>
        <div class="footer">&copy; {{ date('Y') }} {{ $data['users']->restaurant_name }}<br>Powered by RestoFinder</div>
    </div>
</body>
</html>
