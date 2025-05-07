@component('mail::message')
# Reservation Status Updated

Hello {{ $customerName }},

Your reservation (ID: **{{ $reservationId }}**) for **{{ $reservationTime->format('F j, Y, g:i a') }}** has been updated to the following status: **{{ ucfirst($status) }}**.

We look forward to seeing you!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
