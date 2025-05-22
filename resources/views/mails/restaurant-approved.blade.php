<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Approval Status</title>
</head>
<body>
    @if($approved)
        <h2>Congratulations, {{ $restaurant->owner->first_name }}!</h2>
        <p>Your restaurant <strong>{{ $restaurant->name }}</strong> has been <b>approved</b> and is now live on our platform.</p>
        <p>You can now start managing your menu, receiving orders, and more.</p>
        <p>Thank you for joining us!</p>
    @else
        <h2>Hello, {{ $restaurant->owner->first_name }}</h2>
        <p>We regret to inform you that your restaurant <strong>{{ $restaurant->name }}</strong> has been <b>unapproved</b> by our admin team.</p>
        <p>If you have questions or believe this is a mistake, please contact support.</p>
    @endif
    <br>
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>
