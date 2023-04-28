
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'iBMS') }}</title>
    {{-- Styles --}}
    <link href="{{ asset('css/merge.css') }}" rel="stylesheet">
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/document-register-element/1.4.1/document-register-element.js"></script> --}}

</head>
<body class="hotel-custom-background">
    <div id="app">
        @yield('content')
        <guestcard v-bind:guest-Data="{{ $guestData ?? '' }}"></guestcard>
    </div>
    {{-- Scripts --}}
    <script src="//{{ env('IP_PUSH') }}:{{ env('LARAVEL_ECHO_SERVER_PORT') }}/socket.io/socket.io.js"></script>
    <script type="text/javascript">
        let authUser = {!! Auth::user() ? : '[]' !!};
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
