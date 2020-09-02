<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/required.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon.png"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 dashboard-navigation-sidebar">
                        <x-dashboard-navigation></x-dashboard-navigation>
                    </div>
                    <div class="col-lg-10 p-0">
                        <div>
                            @if(!Auth::user()->hasVerifiedEmail())
                                <div class="alert alert-warning">
                                    {{ __('main.Verify or Request') }}
                                    <a class="btn btn-success" href="{{ route('user.send.verification.email') }}" target="_blank">{{ __('main.Request Validation Email') }}</a>
                                </div>
                            @endif
                        </div>
                        @yield('content')
                    </div>
                </div>    
            </div>
        </main>
    </div>
    <footer>
        @stack('footer_scripts')
    </footer>
</body>
</html>
