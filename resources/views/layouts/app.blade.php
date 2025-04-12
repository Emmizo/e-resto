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
    @yield('script')
</body>

</html>
