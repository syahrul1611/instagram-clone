<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- web profile --}}
    <title>{{ env('APP_NAME') }} | Welcome</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form method="POST" action="{{ env('APP_URL').'/login' }}">
            @csrf
            <img class="mb-4" src="{{ asset('img/favicon-9.png') }}" alt="logo" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">{{ env('APP_NAME') }}</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="form-floating">
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                <label for="email">Alamat Email</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <label for="password">Password</label>
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Masuk</button>
            <button type="button" class="border-0 text-decoration-underline link-dark mt-3" data-bs-toggle="modal" data-bs-target="#register">
                Tidak punya akun?
            </button>
            <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
        </form>
    </main>

    {{-- modal register --}}
    <div class="modal fade" id="register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="register" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ env('APP_URL').'/register' }}">
                    @csrf
                    <main class="form-signup w-100 m-auto">
                        <img class="mb-4" src="{{ asset('img/favicon-9.png') }}" alt="logo" width="72" height="57">
                        <h1 class="h3 mb-3 fw-normal">{{ env('APP_NAME') }}</h1>
                        <div class="form-floating">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Fulan bin fulan" max="36">
                            <label for="name">Nama (maks:36)</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="username" class="form-control" id="username" placeholder="fulan123" min="8" max="18">
                            <label for="username">Username (maks:18)</label>
                        </div>
                        <div class="form-floating">
                            <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" max="50">
                            <label for="email">Alamat Email</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" min="8" max="18">
                            <label for="password">Password (maks:18)</label>
                        </div>
                    </main>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- bootstrap js --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>