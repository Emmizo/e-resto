<h2>Reservation Status Update</h2>
<p>Dear {{ $reservation->user->first_name }},</p>
<p>Your reservation (ID: {{ $reservation->id }}) at <strong>{{ $reservation->restaurant->name }}</strong> has been updated.</p>
<p><strong>New Status:</strong> {{ ucfirst($reservation->status) }}</p>
<p><strong>Date & Time:</strong> {{ $reservation->reservation_time->format('Y-m-d H:i') }}</p>
<p><strong>Guests:</strong> {{ $reservation->number_of_people }}</p>
@if($reservation->special_requests)
    <p><strong>Special Requests:</strong> {{ $reservation->special_requests }}</p>
@endif
<p>Thank you for choosing us!</p>
<p>Best regards,<br>{{ config('app.name') }} Team</p>
