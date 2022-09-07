<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- bootstrap icon --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">

    {{-- jquery --}}
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>

    {{-- web profile --}}
    <title>{{ env('APP_NAME') }} | @yield('submenu')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @yield('css')
</head>
<body>
    {{-- navbar --}}
    <nav class="navbar fixed-top navbar-expand-lg bg-light">
        <div class="container">
            <a class="navbar-brand" href="/home">{{ env('APP_NAME') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link {{ (Request::is('home')) ? 'active' : '' }}" href="{{ env('APP_URL').'/home' }}">Home</a>
                    <a class="nav-link {{ (Request::is('search')) ? 'active' : '' }}" href="{{ env('APP_URL').'/search' }}">Search</a>
                    <a class="nav-link {{ (Request::is('post/create')) ? 'active' : '' }}" href="{{ env('APP_URL').'/post/create' }}">Upload</a>
                    <a class="nav-link {{ (Request::is('profile/*')) ? 'active' : '' }}" href="{{ env('APP_URL')."/profile/".Auth::user()->username }}">Profile</a>
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
    @yield('js')
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>