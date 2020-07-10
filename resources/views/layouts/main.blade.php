<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Meta Tags -->

    @stack('meta_tags')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/main_page.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/home.js') }}" defer></script>
    <script src="{{ asset('js/required.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon.png"/>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172345607-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-172345607-1');
    </script>


</head>
<body>
    @guest
        @include('cookieConsent::index')
    @endguest
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" class="py-0">
                    <div class="mr-3">
                        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 455.66 90.7" style="width: 10rem">
                            <text transform="translate(73.87 73.46)" style="font-size:85px;fill:#ff8100;font-family:Lato-Black, Lato;font-weight:800">
                                <tspan style="letter-spacing:0.05em">G</tspan>
                                <tspan x="64.77" y="0" style="fill:#b3b3b3;font-size:72px">e</tspan>
                                <tspan x="103.94" y="0" style="fill:#b3b3b3;font-size:72px;letter-spacing:-0.019992404513888888em">ny</tspan>
                                <tspan x="182.63" y="0" style="fill:#b3b3b3;font-size:72px">oon</tspan>
                            </text>
                        </svg>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('main.search.categories', ['category' => 'stock-photos']) }}">Stock Photos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('main.search.categories', ['category' => 'sound-effects']) }}">Sound Effects</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <a class="btn btn-primary mr-5 my-1" target="_blank" href="{{ route('create.asset') }}"><i class="fa fa-arrow-up"></i>  Upload</a>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><strong>{{ __('Login') }}</strong></a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><strong>{{ __('Register') }}</strong></a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->hasRole('admin'))
                                    <a class="dropdown-item" href="{{ url('/admin/dashboard/') }}">
                                        Admin Panel
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('user.profile.dashboard.show') }}">
                                        Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
    <footer>
        <div class="py-5">
            <div class="py-1 text-center">
                <ul>
                    <li><a class="a-no-decoration-white" target="_blank" href="{{ url('/page/privacy-policy') }}">Privacy Policy</a></li>
                    <li><a class="a-no-decoration-white pl-4" target="_blank" href="{{ url('/page/terms-of-service') }}">Terms of Service</a></li>
                    <li><a class="a-no-decoration-white pl-4" target="_blank" href="{{ url('/page/cookies-policy') }}">Cookies Policy</a></li>
                    <li><a class="a-no-decoration-white pl-4" target="_blank" href="{{ route('create.contactus') }}">Contact Us</a></li>
                </ul>
            </div>
            <div class="text-center">
                &copy; 2020 {{ config('app.name', 'Laravel')}}
            </div>
        </div>
        @stack('footer_scripts')
        
        <!-- Google Adsense -->
        <script data-ad-client="ca-pub-5166868654451969" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    </footer>
</body>
</html>