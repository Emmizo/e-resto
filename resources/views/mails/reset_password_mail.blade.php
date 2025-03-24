@component('mail::message')
# Hello {{ $name }}

Click on the link below to reset password

@component('mail::button', ['url' => $tokenUrl])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
