<h2>New Reservation Received</h2>
<p>You have received a new reservation (ID: {{ $reservation->id }}) for {{ $reservation->reservation_time->timezone($reservation->user->timezone ?? config('app.timezone'))->format('Y-m-d H:i') }}.</p>
<p><strong>Customer:</strong> {{ $reservation->user->first_name }} {{ $reservation->user->last_name }}</p>
<p><strong>Guests:</strong> {{ $reservation->number_of_people }}</p>
@if($reservation->special_requests)
    <p><strong>Special Requests:</strong> {{ $reservation->special_requests }}</p>
@endif
<p>Please log in to your dashboard to view and manage this reservation.</p>
