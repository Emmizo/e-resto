<!doctype html>
<html lang="en">
<!-- Head -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ isset($title) ? $title . ' - ' : '' }} {{ config('app.name') }}</title>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-auth" content="{{ Auth::check() ? 'authenticated' : 'unauthenticated' }}">

    <script>
        var siteUrl = "{{ url('/') }}";
        var csrf_token = "{{ csrf_token() }}";

    </script>


    @include('layouts.header')
    @yield('style')
</head>
<body>
    <div class="wrapper">
        @include('layouts.navbar')
        @include('layouts.sidebar')
        <div class="content-wrapper" >
            @yield('content')
        </div>
        @include('layouts.footer')
        <aside >
        </aside>
    </div>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://unpkg.com/laravel-echo/dist/echo.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
    <script>
    console.log('Pusher:', typeof Pusher, Pusher);
    console.log('Echo:', typeof Echo, Echo);
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env('PUSHER_APP_KEY') }}',
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        forceTLS: true
    });
    </script>

    @yield('script')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (tz) {
            fetch('/set-timezone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ timezone: tz })
            });
        }
    });
    </script>

    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Only run if user is logged in (optional)
        @if(auth()->check())
            const beamsClient = new PusherPushNotifications.Client({
                instanceId: 'daf09ca2-485c-4af3-abeb-14e865ef9a8e',
            });

            beamsClient.start()
                .then(() => {
                    // Subscribe to a unique interest for this user (e.g., user ID)
                    return beamsClient.addDeviceInterest('user-{{ auth()->id() }}');
                })
                .then(() => {
                    console.log('Successfully registered and subscribed for push notifications!');
                })
                .catch(console.error);
        @endif
    });
    </script>

    <script>
    // Register service worker for offline/PWA
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
    </script>
</body>

</html>
