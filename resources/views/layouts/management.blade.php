{{--
    <System Name> iBMS
    <Program Name> management.blade.php
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
    <script>
        $(window).bind('scroll', function() {
            if ($(window).height() == 1920) {
                if ($(window).scrollTop() > 20) {
                    $('.navbar').addClass('fixed');
                    $('.navbar').removeClass('bg-dark-nav');
                } else {
                    $('.navbar').removeClass('fixed');
                    $('.navbar').addClass('bg-dark-nav');
                }
            } else {
                if ($(window).scrollTop() > 50) {
                    $('.navbar').addClass('fixed');
                    $('.navbar').removeClass('bg-dark-nav');
                } else {
                    $('.navbar').removeClass('fixed');
                    $('.navbar').addClass('bg-dark-nav');
                }
            }
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(function() {
            $('[data-toggle="popover"]').popover()
        })
        $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');


            $(this).parents('.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass("show");
            });
            return false;
        });
    </script>
</body>

</html>
