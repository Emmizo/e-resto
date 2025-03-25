<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        var siteUrl = "{{ url('/') }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <title>{{ (isset($title)) ? $title.' - ' : '' }} {{ config('app.name', 'RestoFinder') }}</title>

    @include('layouts.header')
    <style type="text/css">
    	.invalid-feedback{
    		display: block;
    	}
    	.required{
    		color:red;
    	}
    </style>
</head>
<body>

    @yield('content')

    @include('layouts.footer')
</body>
</html>
