<!DOCTYPE html>
<html>
<head>
    <title>Verify your RestoFinder account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #f3f5f8; font-family: 'DM Sans', Arial, sans-serif; margin: 0; padding: 0; }
        .mail-container { max-width: 440px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(24,76,85,0.08); padding: 32px 24px 24px 24px; text-align: center; }
        .brand { font-size: 28px; font-weight: bold; color: #184C55; margin-bottom: 12px; letter-spacing: 1px; }
        .badge-verify { display: inline-block; background: #d1fae5; color: #065f46; font-size: 13px; font-weight: 600; border-radius: 20px; padding: 4px 14px; margin-bottom: 16px; }
        .heading { font-size: 20px; color: #184C55; margin-bottom: 8px; font-weight: 600; }
        .desc { color: #444; font-size: 15px; margin-bottom: 20px; line-height: 1.6; }
        .credentials { background: #f3f5f8; border-radius: 8px; padding: 14px 16px; margin: 0 0 20px 0; text-align: left; }
        .credentials strong { color: #184C55; }
        .note { font-size: 13px; color: #666; margin-bottom: 8px; }
        .button { display: inline-block; background: #184C55; color: #fff !important; font-size: 16px; font-weight: 600; border-radius: 8px; padding: 13px 32px; text-decoration: none; margin: 10px 0 20px 0; box-shadow: 0 2px 8px rgba(24,76,85,0.12); }
        .button:hover { background: #133a41; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
        .footer { font-size: 12px; color: #9ca3af; margin-top: 16px; line-height: 1.6; }
        @media (max-width: 500px) { .mail-container { padding: 18px 4vw; } .button { font-size: 15px; padding: 12px 10vw; } }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="brand">RestoFinder</div>
        <div class="badge-verify">&#10003; Account Created</div>
        <div class="heading">Hello {{ $user->first_name.' '.$user->last_name }},</div>
        <div class="desc">
            Welcome! Your account has been created. Click the button below to verify your email address and set your password to activate your account.
        </div>

        <div class="credentials">
            <div><strong>Email:</strong> {{ $user->email }}</div>
            @if(!empty($user->plain_password))
            <div style="margin-top:6px;"><strong>Temporary password:</strong> <span style="font-family:monospace;letter-spacing:1px;">{{ $user->plain_password }}</span></div>
            @endif
        </div>

        <div class="note">This link expires in 60 minutes.</div>
        <a href="{{ $user->tokenUrl ?? '#' }}" class="button">Verify &amp; Set Password</a>

        <hr class="divider">
        <div class="footer">
            If you did not create a RestoFinder account, you can safely ignore this email.<br><br>
            &copy; {{ date('Y') }} RestoFinder
        </div>
    </div>
</body>
</html>
