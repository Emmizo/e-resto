@component('mail::message')
    # Hello {{ $first_name }}

    Your account has been successfully reset. You can login with the below login access credentials.


    Email Address:

    {{ $email }}

    Password:

    {{ $password }}
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
