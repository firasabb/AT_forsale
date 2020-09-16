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
            <div>
                <div class="row row no-gutters">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm side-navbar">
                            <a class="navbar-brand" href="{{ url('/') }}">
                                <div>
                                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 302.78 74.45" style="width: 6rem">
                                        <path d="M424.47,472.39a24.46,24.46,0,0,0,5.27-.51,25.55,25.55,0,0,0,4.34-1.36v-9.39H428a3,3,0,0,1-2.13-.72,2.4,2.4,0,0,1-.76-1.83v-7.9h22v26.13a32.49,32.49,0,0,1-4.95,3,32,32,0,0,1-5.48,2.06,40,40,0,0,1-6.21,1.19,60.24,60.24,0,0,1-7.07.38,32.45,32.45,0,0,1-12.58-2.4,30.11,30.11,0,0,1-16.64-16.66,32.62,32.62,0,0,1-2.4-12.6A34.31,34.31,0,0,1,394,439.05a28.76,28.76,0,0,1,6.57-10,29.84,29.84,0,0,1,10.32-6.54,37.31,37.31,0,0,1,13.54-2.34,39,39,0,0,1,7.27.64,35.4,35.4,0,0,1,6.27,1.74,29.28,29.28,0,0,1,5.25,2.64,28.76,28.76,0,0,1,4.25,3.31l-4.17,6.33a4.65,4.65,0,0,1-1.42,1.41,3.42,3.42,0,0,1-1.81.51,5,5,0,0,1-2.63-.85q-1.71-1-3.21-1.77a19.46,19.46,0,0,0-6.4-1.89A32.83,32.83,0,0,0,424,432a17.86,17.86,0,0,0-7.27,1.42,15.55,15.55,0,0,0-5.5,4,18,18,0,0,0-3.51,6.23,25.12,25.12,0,0,0-1.23,8.09,26,26,0,0,0,1.36,8.76,18.91,18.91,0,0,0,3.76,6.46,16,16,0,0,0,5.7,4A18.41,18.41,0,0,0,424.47,472.39Z" transform="translate(-391.75 -420.16)" style="fill:#ff8100"/>
                                        <path d="M475.29,444.86a19.14,19.14,0,0,1,6.78,1.15,14.7,14.7,0,0,1,5.3,3.35,15.28,15.28,0,0,1,3.45,5.38,19.7,19.7,0,0,1,1.24,7.22A16.33,16.33,0,0,1,492,464a3.4,3.4,0,0,1-.4,1.26,1.52,1.52,0,0,1-.77.65,3.67,3.67,0,0,1-1.25.18H468c.36,3.12,1.3,5.38,2.84,6.78A8.45,8.45,0,0,0,476.8,475a10.7,10.7,0,0,0,3.35-.47,17.27,17.27,0,0,0,2.5-1c.73-.39,1.4-.74,2-1.05a4.19,4.19,0,0,1,1.92-.47,2.32,2.32,0,0,1,2,1l3.24,4a16.39,16.39,0,0,1-3.73,3.22,19.41,19.41,0,0,1-4.1,2,22.24,22.24,0,0,1-4.21,1,31.8,31.8,0,0,1-4,.27,21,21,0,0,1-7.36-1.28,16.85,16.85,0,0,1-6-3.8,17.87,17.87,0,0,1-4.07-6.26,23.34,23.34,0,0,1-1.49-8.71,19.72,19.72,0,0,1,1.27-7.09,17.31,17.31,0,0,1,9.47-9.87A19.21,19.21,0,0,1,475.29,444.86Zm.21,7.7a6.73,6.73,0,0,0-5,1.82,9.52,9.52,0,0,0-2.38,5.24h13.94a9.82,9.82,0,0,0-.35-2.6,6.24,6.24,0,0,0-1.11-2.25,5.75,5.75,0,0,0-2-1.6A6.93,6.93,0,0,0,475.5,452.56Z" transform="translate(-391.75 -420.16)" style="fill:#b3b3b3"/>
                                        <path d="M498.47,482.76V445.43h6.91a3.11,3.11,0,0,1,1.75.47,2.48,2.48,0,0,1,1,1.4l.65,2.16a28,28,0,0,1,2.23-1.87,13.6,13.6,0,0,1,2.47-1.46,14.78,14.78,0,0,1,6.17-1.27,13,13,0,0,1,5.42,1.06,11.29,11.29,0,0,1,4,2.95,12.7,12.7,0,0,1,2.44,4.5,18.42,18.42,0,0,1,.83,5.67v23.72H521.15V459a6.55,6.55,0,0,0-1.26-4.27,4.52,4.52,0,0,0-3.71-1.53,7.73,7.73,0,0,0-3.45.8,13.26,13.26,0,0,0-3.1,2.12v26.6Z" transform="translate(-391.75 -420.16)" style="fill:#b3b3b3"/>
                                        <path d="M554.06,492.45a3.19,3.19,0,0,1-3.53,2.16h-8.35l7.2-15.12-14.91-34.06h9.87a3.1,3.1,0,0,1,2,.58,3.05,3.05,0,0,1,1,1.37l6.12,16.09a34.74,34.74,0,0,1,1.4,4.46c.24-.77.51-1.52.8-2.27s.56-1.5.82-2.26l5.55-16a2.7,2.7,0,0,1,1.13-1.39,3.27,3.27,0,0,1,1.82-.56h9Z" transform="translate(-391.75 -420.16)" style="fill:#b3b3b3"/>
                                        <path d="M593.8,444.86a21.53,21.53,0,0,1,7.74,1.33,17,17,0,0,1,6,3.81,17.18,17.18,0,0,1,3.89,6,21.69,21.69,0,0,1,1.39,8,22,22,0,0,1-1.39,8,17.26,17.26,0,0,1-3.89,6.09,16.92,16.92,0,0,1-6,3.85A23,23,0,0,1,586,482a16.86,16.86,0,0,1-10-9.94,22.19,22.19,0,0,1-1.38-8,21.9,21.9,0,0,1,1.38-8,17.13,17.13,0,0,1,3.93-6,17.4,17.4,0,0,1,6-3.81A21.73,21.73,0,0,1,593.8,444.86Zm0,30.2a6.34,6.34,0,0,0,5.67-2.72q1.81-2.71,1.82-8.26c0-3.7-.61-6.44-1.82-8.24a6.35,6.35,0,0,0-5.67-2.7,6.52,6.52,0,0,0-5.8,2.7c-1.22,1.8-1.83,4.54-1.83,8.24s.61,6.45,1.83,8.26A6.51,6.51,0,0,0,593.8,475.06Z" transform="translate(-391.75 -420.16)" style="fill:#b3b3b3"/>
                                        <path d="M635.56,444.86a21.53,21.53,0,0,1,7.74,1.33,17,17,0,0,1,6,3.81,17.18,17.18,0,0,1,3.89,6,21.9,21.9,0,0,1,1.39,8,22.19,22.19,0,0,1-1.39,8,17.26,17.26,0,0,1-3.89,6.09,16.92,16.92,0,0,1-6,3.85,23,23,0,0,1-15.53,0,16.86,16.86,0,0,1-10-9.94,22.19,22.19,0,0,1-1.38-8,21.9,21.9,0,0,1,1.38-8,17.13,17.13,0,0,1,3.93-6,17.31,17.31,0,0,1,6-3.81A21.73,21.73,0,0,1,635.56,444.86Zm0,30.2a6.34,6.34,0,0,0,5.67-2.72q1.82-2.71,1.82-8.26c0-3.7-.61-6.44-1.82-8.24a6.35,6.35,0,0,0-5.67-2.7,6.52,6.52,0,0,0-5.8,2.7c-1.22,1.8-1.83,4.54-1.83,8.24s.61,6.45,1.83,8.26A6.51,6.51,0,0,0,635.56,475.06Z" transform="translate(-391.75 -420.16)" style="fill:#b3b3b3"/>
                                        <path d="M660.69,482.76V445.43h6.91a3.07,3.07,0,0,1,1.74.47,2.48,2.48,0,0,1,1,1.4l.65,2.16c.72-.67,1.47-1.29,2.23-1.87a13.6,13.6,0,0,1,2.47-1.46,14.78,14.78,0,0,1,6.17-1.27,13,13,0,0,1,5.42,1.06,11.29,11.29,0,0,1,4,2.95,12.87,12.87,0,0,1,2.45,4.5,18.72,18.72,0,0,1,.83,5.67v23.72H683.37V459a6.55,6.55,0,0,0-1.26-4.27,4.53,4.53,0,0,0-3.71-1.53,7.74,7.74,0,0,0-3.46.8,13,13,0,0,0-3.09,2.12v26.6Z" transform="translate(-391.75 -420.16)" style="fill:#b3b3b3"/>
                                    </svg>
                                </div>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="pt-4">
                                    <a class="side-navbar-a" href="{{ route('user.profile.dashboard.show') }}">
                                        <li>
                                            <p class="m-0"><span class="dashboard-icon-span">@svg('user', 'dashboard-icon')</span> {{ __('main.My Profile') }}</p>
                                        </li>
                                    </a>
                                    <a class="side-navbar-a" href="{{ route('user.setup.show') }}">
                                        <li>
                                            <p class="m-0"><span class="dashboard-icon-span">@svg('edit', 'dashboard-icon')</span> {{ __('main.Edit Profile') }}</p>
                                        </li>
                                    </a>
                                    <a class="side-navbar-a" href="{{ route('user.posts.show') }}">
                                        <li>
                                            <p class="m-0"><span class="dashboard-icon-span">@svg('th-list', 'dashboard-icon')</span> {{ __('main.My Posts') }}</p>
                                        </li>
                                    </a>
                                    <a class="side-navbar-a" href="{{ route('user.password.show') }}">
                                        <li>
                                            <p class="m-0"><span class="dashboard-icon-span">@svg('lock-closed', 'dashboard-icon')</span> {{ __('main.Change Password') }}</p>
                                        </li>
                                    </a>
                                    <div class="py-5 text-center">
                                        <a target="_blank" href="{{ route('create.post') }}" class="btn btn-primary">{{ __('main.upload') }}&nbsp;&nbsp;@svg('upload', 'dashboard-upload-icon')</a>
                                    </div>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-lg-10 p-0">
                        <div>
                            @if(!Auth::user()->hasVerifiedEmail())
                                <div class="alert alert-warning text-center">
                                    {{ __('main.Verify or Request') }} &nbsp;&nbsp;
                                    <a class="btn btn-success" href="{{ route('user.send.verification.email') }}" target="_blank">{{ __('main.Request Validation Email') }}</a>
                                </div>
                            @endif
                        </div>
                        @yield('content')
                    </div>
                </div>    
            </div>
        </main>
    <footer>
        @stack('footer_scripts')
    </footer>
</body>
</html>
