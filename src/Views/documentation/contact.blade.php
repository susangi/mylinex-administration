<!DOCTYPE html>
<html lang="en-gb" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MYLINEX') }} | Documentation</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/myl-favicon.png')}}">
    <link rel="icon" href="{{asset('images/myl-favicon.png')}}" type="image/x-icon">

    <!-- Toastr CSS -->
    <link href="{{asset('plugins/jquery-toast-plugin/jquery.toast.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{ asset('fonts/heebo/heebo.css') }}?family=Heebo:300,400" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/doc-main.css') }}"/>
    <script src="{{ asset('js/doc-uikit.js') }}"></script>
</head>
<body>
<div id="app">
    <div data-uk-sticky="animation: uk-animation-slide-top; sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent; top: 200">
        <nav class="uk-navbar-container">
            <div class="uk-container">
                <div data-uk-navbar>
                    <div class="uk-navbar-left">
                        <a class="uk-navbar-item uk-logo uk-visible@m" href="/">{{ config('app.name', 'MYLINEX') }}</a>
                        <a class="uk-navbar-toggle uk-hidden@m" href="#offcanvas-docs" data-uk-toggle><span
                                    data-uk-navbar-toggle-icon></span> <span class="uk-margin-small-left">{{ config('app.name', 'MYLINEX') }}</span></a>
                        <ul class="uk-navbar-nav uk-visible@m">
                            <li class=""><a href="{{ url('/documentation') }}">Docs</a></li>
                            <li class=""><a href="{{ url('/doc-changelog') }}">Change logs</a></li>
                            <li class=""><a href="http://www.mylinex.com/support/" target="_blank">Support</a></li>
                        </ul>
                    </div>
                    <div class="uk-navbar-center uk-hidden@m">
                        <a class="uk-navbar-item uk-logo" href="{{ url('/documentation') }}">Docs</a>
                    </div>
                    <div class="uk-navbar-right">
                        <div>
                            <a id="search-navbar-toggle" class="uk-navbar-toggle" data-uk-search-icon href="#"></a>
                            <div class="uk-background-default uk-border-rounded"
                                 data-uk-drop="mode: click; pos: left-center; offset: 0">
                                <form class="uk-search uk-search-navbar uk-width-1-1" onsubmit="return false;">
                                    <input id="search-navbar" class="uk-search-input" type="search" placeholder="Search for answers"
                                           autofocus autocomplete="off" data-minchars="1" data-maxitems="30">
                                </form>
                            </div>
                        </div>
                        <ul class="uk-navbar-nav uk-visible@m">
                            <li>
                                <div class="uk-navbar-item">
                                    <a class="uk-button uk-button-primary-outline" href="{{ url('/doc-changelog') }}">Change log</a>
                                </div>
                            </li>
                            <li>
                                <div class="uk-navbar-item">
                                    <a class="uk-button uk-button-success" href="{{ url('/doc-contact') }}">Contact</a>
                                </div>
                            </li>
                        </ul>
                        <a class="uk-navbar-toggle uk-hidden@m" href="#offcanvas" data-uk-toggle><span
                                    data-uk-navbar-toggle-icon></span> <span class="uk-margin-small-left">Menu</span></a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <div class="uk-section">
        <div class="uk-container uk-container-xsmall">
            <article class="uk-article">
                <h1 class="uk-article-title">Got Any Questions</h1>
                <div class="article-content link-primary">
                    {{--                <h5 id="morbi-varius-in-accumsan-blandit-elit-ligula-velit-luctus-mattis-ante-nulla-nulla"></h5>--}}
                    <p>Please fill in the contact form and we will get in touch with you within 24 hours.</p>
                    {!! Form::open(['url' => 'send-mail', 'method' => 'post', 'id' => 'sendMailForm','class'=>'uk-form-stacked uk-margin-medium-top','novalidate']) !!}
                    <div class="uk-margin-medium-bottom">
                        <label class="uk-form-label uk-margin-small-bottom" for="name">Name</label>
                        <div class="uk-form-controls">
                            <input id="name" class="uk-input uk-form-large uk-border-rounded" name="name" type="text" required="">
                        </div>
                    </div>
                    <div class="uk-margin-medium-bottom">
                        <label class="uk-form-label uk-margin-small-bottom" for="_replyto">Email</label>
                        <div class="uk-form-controls">
                            <input id="_replyto" class="uk-input uk-form-large uk-border-rounded" name="_replyto" type="email" required="">
                        </div>
                    </div>
                    <div class="uk-margin-medium-bottom">
                        <label class="uk-form-label uk-margin-small-bottom" for="_subject">Subject</label>
                        <div class="uk-form-controls">
                            <input id="_subject" class="uk-input uk-form-large uk-border-rounded" name="_subject" type="text">
                        </div>
                    </div>
                    <div class="uk-margin-medium-bottom">
                        <label class="uk-form-label uk-margin-small-bottom" for="message">Message</label>
                        <div class="uk-form-controls">
                            <textarea id="message" class="uk-textarea uk-form-large uk-border-rounded" name="message" rows="5" minlength="10" required=""></textarea>
                        </div>
                    </div>
                    <div>
                        <input class="uk-button uk-button-primary uk-button-large uk-width-1-1" type="submit" value="Send">
                    </div>
                    {!! Form::close() !!}
                </div>
            </article>
        </div>
    </div>

    <div id="offcanvas-docs" data-uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar">
            <button class="uk-offcanvas-close" type="button" data-uk-close></button>
            @foreach($side_panel as $key => $children)
                <h5 class="uk-margin-top"><a href="{{ url('/documentation') }}#{{Str::slug($key)}}">{{$key}}</a></h5>
                <ul class="uk-nav uk-nav-default doc-nav">
                    @foreach($children as $child)
                        <li class=""><a href="{{ url('/documentation') }}#{{Str::slug($child)}}">{{$child}}</a></li>
                    @endforeach
                </ul>
            @endforeach
            <h5 class="uk-margin-top">Help</h5>
            <ul class="uk-nav uk-nav-default doc-nav">
                <li><a href="{{ url('/doc-contact') }}">Contacting support</a></li>
            </ul>
        </div>
    </div>

    <div id="offcanvas" data-uk-offcanvas="flip: true; overlay: true">
        <div class="uk-offcanvas-bar">
            <a class="uk-logo" href="/">{{ config('app.name', 'MYLINEX') }}</a>
            <button class="uk-offcanvas-close" type="button" data-uk-close></button>
            <ul class="uk-nav uk-nav-primary uk-nav-offcanvas uk-margin-top">
                <li class="uk-active"><a href="{{ url('/documentation') }}">Docs</a></li>
                <li class=""><a href="{{ url('/doc-changelog') }}">Change logs</a></li>
                <li class=""><a href="http://www.mylinex.com/support/" target="_blank">Support</a></li>
                <li>
                    <div class="uk-navbar-item"><a class="uk-button uk-button-success" href="{{ url('/doc-contact') }}">Contact</a></div>
                </li>
            </ul>
            <div class="uk-margin-top uk-text-center">
                <div data-uk-grid class="uk-child-width-auto uk-grid-small uk-flex-center">
                    <div>
                        <a href="https://twitter.com/MylinexIntl" data-uk-icon="icon: twitter" class="uk-icon-link" target="_blank"></a>
                    </div>
                    <div>
                        <a href="https://www.facebook.com/Mylinex/" data-uk-icon="icon: facebook" class="uk-icon-link" target="_blank"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="uk-section uk-text-center uk-text-muted">
        <div class="uk-container uk-container-small">
            <div>
                <ul class="uk-subnav uk-flex-center">
                    <li><a href="/">{{ config('app.name', 'MYLINEX') }}</a></li>
                    <li><a href="{{ url('/documentation') }}">Docs</a></li>
                    <li><a href="{{ url('/doc-changelog') }}">Change logs</a></li>
                    <li><a href="{{ url('/doc-contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="uk-margin-medium">
                <div data-uk-grid class="uk-child-width-auto uk-grid-small uk-flex-center">
                    <div class="uk-first-column">
                        <a href="https://twitter.com/MylinexIntl" data-uk-icon="icon: twitter" class="uk-icon-link uk-icon" target="_blank"></a>
                    </div>
                    <div>
                        <a href="https://www.facebook.com/Mylinex/" data-uk-icon="icon: facebook" class="uk-icon-link uk-icon" target="_blank"></a>
                    </div>
                </div>
            </div>
            <div class="uk-margin-medium uk-text-small copyright link-secondary">Made by a <a href="http://www.mylinex.com/">Mylinex International Pvt Ltd</a>.
            </div>
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/doc-awesomplete.js')}}"></script>
<script src="{{asset('js/doc-custom.js')}}"></script>
<script src="{{asset('js/notifications.js')}}"></script>

<!-- Toastr JS -->
<script src="{{asset('plugins/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
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
</body>
</html>