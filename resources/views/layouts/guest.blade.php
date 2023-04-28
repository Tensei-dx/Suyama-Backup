{{--
    <System Name> iBMS
    <Program Name> guest.blade.php
    <Create> 2021.01.08 TP Uddin
--}}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/document-register-element/1.4.1/document-register-element.js">
    </script>

</head>

<body class="custom-bg-darkgray">
    <div id="app">
        @yield('content')
    </div>
    {{-- Scripts --}}
    <script type="text/javascript">
        let authUser = {!! Auth::user() ?: '[]' !!};
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
