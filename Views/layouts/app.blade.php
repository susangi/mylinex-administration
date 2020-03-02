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
    <link href="{{asset('plugins/sweetalert2/sweetalert2.css')}}" rel="stylesheet" type="text/css">

    @stack('styles')

    <!-- Custom CSS -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css">

</head>

<body>
<div id="app">
    <div class="hk-wrapper hk-alt-nav hk-icon-nav container-fluid">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar hk-navbar-alt">
            <a class="navbar-toggle-btn nav-link-hover navbar-toggler" href="javascript:void(0);" data-toggle="collapse"
               data-target="#navbarCollapseAlt" aria-controls="navbarCollapseAlt" aria-expanded="false"
               aria-label="Toggle navigation"><span class="feather-icon"><i data-feather="menu"></i></span></a>
            <a class="navbar-brand text-red" href="/">
                <img src="{{asset('images/myl-logo-icon.png')}}">
            </a>
            <div class="collapse navbar-collapse" id="navbarCollapseAlt">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown show-on-hover active">
                        <a class="nav-link " href="/" aria-haspopup="true" aria-expanded="false">
                            Dashboard
                        </a>
                    </li>
                    {!! $menu !!}
                    <div id="menu"></div>
                </ul>
            </div>

            <ul class="navbar-nav hk-navbar-content">
                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="#" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <img
                                        src="{{asset('images/profile/'.(!empty($user->image)?$user->image:'default.jpg'))}}"
                                        alt="user"
                                        class="avatar-img rounded-circle">
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                <span>{{Auth::user()->name ?? ''}}<i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX"
                         data-dropdown-out="flipOutX">
                        <a class="dropdown-item"
                           href="{{route('users.profile',\Illuminate\Support\Facades\Auth::id())}}"><i
                                class="dropdown-icon zmdi zmdi-account"></i><span>Profile</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"><i
                                    class="dropdown-icon zmdi zmdi-power"></i><span>Log out</span></a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /Top Navbar -->

        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Breadcrumb -->
            <nav class="hk-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light bg-transparent">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title','Dashboard')</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->
            <!-- Container -->
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

<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('plugins/popper/popper.js')}}"></script>

<!-- Notification JS -->
<script src="{{asset('plugins/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.js')}}"></script>

<!-- Init JavaScript -->
<script src="{{asset('js/init.js')}}"></script>

<script src="{{asset('js/notifications.js')}}"></script>
<script src="{{asset('js/modal.js')}}"></script>
<script src="{{asset('js/dataTable.js')}}"></script>
<script src="{{asset('js/FormOptions.js')}}"></script>



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
</script>
@stack('scripts')
</body>
</html>
