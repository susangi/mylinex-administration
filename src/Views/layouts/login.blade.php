<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>{{ config('app.name', 'ADMIN-CONSOLE') }} | @yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/myl-favicon.png')}}">
    <link rel="icon" href="{{asset('images/myl-favicon.png')}}" type="image/x-icon">

    <!-- Toastr CSS -->
    <link href="{{asset('plugins/jquery-toast-plugin/jquery.toast.min.css')}}" rel="stylesheet" type="text/css">

@include('layouts.includes.styles.fonts')

@stack('styles')

<!-- Custom CSS -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css">


</head>
<body>
<div id="app">
    <div class="hk-wrapper hk-alt-nav hk-icon-nav">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar hk-navbar-alt">
            <a class="navbar-toggle-btn nav-link-hover navbar-toggler" href="javascript:void(0);" data-toggle="collapse"
               data-target="#navbarCollapseAlt" aria-controls="navbarCollapseAlt" aria-expanded="false"
               aria-label="Toggle navigation"><span class="feather-icon"><i data-feather="menu"></i></span></a>
            <a class="navbar-brand text-red" href="/">
                <img src="{{asset('images/myl-logo-icon.png')}}">
            </a>
        </nav>
        <!-- /Top Navbar -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper hk-auth-wrapper">

        @yield('content', 'Default Content')
        <!-- /Container -->
            <!-- Footer -->
            <div class="hk-footer-wrap container">
                <footer class="footer">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <p>Powered by<a href="https://www.mylinex.com/" class="text-dark" target="_blank">Mylinex
                                    PVT LTD </a> © {{\Carbon\Carbon::now()->year}}</p>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- /Footer -->
        </div>
        <!-- /Main Content -->

    </div>
</div>

<!-- jQuery -->
<script src="{{asset('js/app.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{asset('plugins/popper/popper.js')}}"></script>

@include('layouts.includes.scripts.fonts')

<!-- Slimscroll JavaScript -->
<script src="{{asset('plugins/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
<script src="{{asset('plugins/jquery-toast-plugin/jquery.toast.min.js')}}"></script>

<script src="{{asset('js/notifications.js')}}"></script>
<script src="{{asset('js/FormOptions.js')}}"></script>

<script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
<script src="{{asset('js/notifications.js')}}"></script>
<script>
        @if(Session::has('message'))
    let type = "{{ Session::get('alert-type', 'info') }}";
    let msg = "{{ Session::get('message') }}";
    switch (type) {
        case 'info':
            Notifications.showSuccessMsg(msg);
            break;
        case 'warning':
            Notifications.showSuccessMsg(msg);
            break;
        case 'success':
            Notifications.showSuccessMsg(msg);
            break;
        case 'error':
            Notifications.showErrorMsg(msg);
            break;
    }
    @endif

    /*Feather Icon*/
    var featherIcon = $('.feather-icon');
    if (featherIcon.length > 0) {
        feather.replace();
    }

    $("#loginForm").validate({
        errorPlacement: function (error, element) {
            if (element.attr("name") == "password") {
                error.appendTo($("#password_error"));
            } else {
                error.insertAfter(element);
            }
        },
        success: function (error, element) {
            error.remove();
            element.classList.remove('error-element');
        }
    });
</script>

@stack('scripts')
</body>
</html>
