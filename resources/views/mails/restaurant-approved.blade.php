<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Approval Status</title>
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
        .cta-btn { display: inline-block; background: #184C55; color: #fff !important; font-size: 16px; font-weight: 600; border-radius: 8px; padding: 12px 32px; text-decoration: none; margin: 18px 0 0 0; box-shadow: 0 2px 8px rgba(24,76,85,0.08); transition: background 0.2s; }
        .cta-btn:hover { background: #133a41; }
        .footer { font-size: 13px; color: #888; margin-top: 32px; text-align: center; padding: 0 24px; }
        @media (max-width: 600px) { .mail-container { padding: 0; } .content { padding: 0 4vw; } }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Platform Logo" class="logo">
            @if($restaurant && $restaurant->image)
                <img src="{{ $restaurant->image }}" alt="{{ $restaurant->name }} Logo" class="restaurant-logo">
            @endif
            <div class="heading">Restaurant Approval Status</div>
            <div class="subheading">{{ $restaurant->name ?? config('app.name') }}</div>
        </div>
        <div class="content">
            <div class="desc">
                @if($approved)
                    <strong>Congratulations, {{ $restaurant->owner->first_name }}!</strong><br><br>
                    Your restaurant <b>{{ $restaurant->name }}</b> has been <b>approved</b> and is now live on our platform.<br>
                    You can now start managing your menu, receiving orders, and more.<br>
                    Thank you for joining us!
                @else
                    <strong>Hello, {{ $restaurant->owner->first_name }}</strong><br><br>
                    We regret to inform you that your restaurant <b>{{ $restaurant->name }}</b> has been <b>unapproved</b> by our admin team.<br>
                    If you have questions or believe this is a mistake, please contact support.
                @endif
            </div>
            <div class="card">
                <div><strong>Status:</strong> {{ $approved ? 'Approved' : 'Unapproved' }}</div>
                <div><strong>Restaurant:</strong> {{ $restaurant->name }}</div>
            </div>
            <a href="{{ url('/dashboard/restaurant') }}" class="cta-btn">Manage Restaurant</a>
            <hr class="divider">
        </div>
        <div class="footer">
            Best regards,<br>{{ config('app.name') }} Team<br>
            <span style="color:#184C55; font-weight:600;">Support:</span> <a href="mailto:support@restofinder.com" style="color:#184C55; text-decoration:underline;">support@restofinder.com</a><br>
            &copy; {{ date('Y') }} RestoFinder
        </div>
    </div>
</body>
</html>
