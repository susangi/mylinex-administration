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

    <link href="{{ asset('fonts/heebo/heebo.css') }}?family=Heebo:300,400" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/doc-main.css') }}"/>
    <script src="{{ asset('js/doc-uikit.js') }}"></script>
</head>
<body>
<div data-uk-sticky="animation: uk-animation-slide-top; sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent; top: 200">
    <nav class="uk-navbar-container">
        <div class="uk-container">
            <div data-uk-navbar>
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo uk-visible@m" href="/">{{ config('app.name', 'MYLINEX') }}</a>
                    <a class="uk-navbar-toggle uk-hidden@m" href="#offcanvas-docs" data-uk-toggle><span
                                data-uk-navbar-toggle-icon></span> <span class="uk-margin-small-left">{{ config('app.name', 'MYLINEX') }}</span></a>
                    <ul class="uk-navbar-nav uk-visible@m">
                        <li class="uk-active"><a href="{{ url('/documentation') }}">Docs</a></li>
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
    <div class="uk-container">
        <div class="uk-grid-large" data-uk-grid>
            <div class="sidebar-fixed-width uk-visible@m">
                <div class="sidebar-docs uk-position-fixed uk-margin-top">
                    @foreach($side_panel as $key => $children)
                        <h5><a href="#{{Str::slug($key)}}">{{$key}}</a></h5>
                        <ul class="uk-nav uk-nav-default doc-nav">
                            @foreach($children as $child)
                                <li class=""><a href="#{{Str::slug($child)}}">{{$child}}</a></li>
                            @endforeach
                        </ul>
                    @endforeach
                    <h5>Help</h5>
                    <ul class="uk-nav uk-nav-default doc-nav">
                        <li><a href="{{ url('/doc-contact') }}">Contacting support</a></li>
                    </ul>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-expand@m">
                @foreach($roots as $root)
                    @php $root_permissions = $root->permissions; @endphp
                    @if ($root_permissions->count() == 0 || $user->hasAnyAccess($root_permissions->pluck('name')->toArray()))
                        <article class="uk-article">
                            <div id="{{Str::slug($root->title)}}"></div>
                            <h1 class="uk-article-title">{{$root->title}}</h1>
                            <div class="uk-article-meta uk-margin-top uk-margin-medium-bottom uk-flex uk-flex-middle">
                                <img class="uk-border-circle avatar" src="{{asset('images/profile/'.(!empty($root->user->image)?$root->user->image:'default.png'))}}" alt="{{$root->user->name}}">
                                <div>
                                    Written by
                                    <span>{{$root->user->name}}</span><br>
                                    <time datetime="{{$root->created_at}}">{{date("M d",strtotime($root->created_at))}}</time>
                                </div>
                            </div>
                            <div class="article-content link-primary">
                                {!! $root->description !!}
                                @foreach($root->children()->get() as $child)
                                    @php $child_permissions = $child->permissions; @endphp
                                    @if ($child_permissions->count() == 0 || $user->hasAnyAccess($child_permissions->pluck('name')->toArray()))
                                        <div id="{{Str::slug($child->title)}}"></div>
                                        <h2>{{$child->title}}</h2>
                                        {!! $child->description !!}
                                    @endif
                                @endforeach
                            </div>
                            <hr class="uk-margin-medium">
                        </article>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<div id="offcanvas-docs" data-uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar">
        <button class="uk-offcanvas-close" type="button" data-uk-close></button>
        @foreach($side_panel as $key => $children)
            <h5 class="uk-margin-top"><a href="#{{Str::slug($key)}}">{{$key}}</a></h5>
            <ul class="uk-nav uk-nav-default doc-nav">
                @foreach($children as $child)
                    <li class=""><a href="#{{Str::slug($child)}}">{{$child}}</a></li>
                @endforeach
            </ul>
        @endforeach
        <h5 class="uk-margin-top">Help</h5>
        <ul class="uk-nav uk-nav-default doc-nav">
            <li><a href="doc.html">Contacting support</a></li>
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
<script src="{{ asset('js/doc-awesomplete.js') }}"></script>
<script src="{{ asset('js/doc-custom.js') }}"></script>
</body>
</html>