<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'iBMS') }} </title>

    {{-- Styles --}}
    <link href="{{ asset('css/merge.css') }}" rel="stylesheet">
</head>
<body class="hotel-custom-background">
    <div id="app">
        <checkin></checkin>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/sb-admin.js') }}"></script>
</body>
</html>
