@component('mail::message')
# Hello {{ $user->first_name.' '.$user->last_name }},

Your account has been successfully registered with us. You can login with the below login access credentials.
<br><br>
Email Address: {{ $user->email }}
<br>
Password: <b>{{ $user->password }}</b>
<br><br>
You can reset password with below button
@component('mail::button', ['url' => $tokenUrl ?? '' ])
    Create Password
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
