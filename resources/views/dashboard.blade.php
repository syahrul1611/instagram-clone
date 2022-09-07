@extends('layouts.main')
@section('submenu') {{ $user->name }} @endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/trix.css') }}">
    <style>
        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }
        .switch input {
        opacity: 0;
        width: 0;
        height: 0;
        }
        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }
        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }
        input:checked + .slider {
        background-color: #2196F3;
        }
        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }
        .slider.round {
        border-radius: 34px;
        }
        .slider.round:before {
        border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <div class="container mb-3" style="margin-top: 4.5rem;">
        @if ($errors->any())
            <div class="alert alert-danger mx-auto" style="max-width: 576px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('edited'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('edited') }}</div>
        @endif
        @if (session()->has('accepted'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('accepted') }}</div>
        @endif
        @if (session()->has('rejected'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('rejected') }}</div>
        @endif
        @if (session()->has('followed'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('followed') }}</div>
        @endif
        @if (session()->has('requested'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('requested') }}</div>
        @endif
        @if (session()->has('unfollowed'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('unfollowed') }}</div>
        @endif
        @if (session()->has('updated'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('updated') }}</div>
        @endif
        {{-- Card profile --}}
        <div class="card mb-3 mx-auto" style="max-width: 576px;">
            <div class="card-header">
                {{ $user->username }}
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between gap-2 mb-1 px-1 px-sm-3">
                    <img src="{{ asset('storage/profile').'/'.$user->image }}" class="img-fluid rounded-circle" style="max-width:72px;aspect-ratio:1;">
                    <div class="d-flex gap-2 justify-content-around justify-content-sm-center flex-fill">
                        <button type="button" class="btn btn-outline-secondary p-2" data-bs-toggle="modal" data-bs-target="#followers" style="width:90px;">
                            <p class="m-0">Diikuti</p>
                            <p class="m-0">{{ $user->followers->where('status', true)->count() }}</p>
                        </button>
                        <button type="button" class="btn btn-outline-secondary p-2" data-bs-toggle="modal" data-bs-target="#followings" style="width:90px;">
                            <p class="m-0">Mengikuti</p>
                            <p class="m-0">{{ $user->followings->where('status', true)->count() }}</p>
                        </button>
                    </div>
                </div>
                <h5 class="card-title">{{ $user->name }}</h5>
                {!! $user->bio !!}
                @if (Auth::user()->username === $user->username)
                <div class="d-flex gap-1 mt-1">
                    <button type="button" class="btn btn-outline-primary flex-fill" data-bs-toggle="modal" data-bs-target="#editProfile">
                        Edit Profile
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#logout">
                        <i class="bi bi-box-arrow-left"></i>
                    </button>
                </div>
                @else
                    @if ($user->followers->firstWhere('user_id_1', Auth::user()->id) === null)
                        <form action="{{ env('APP_URL').'/friend/'.$user->username }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-info text-dark w-100 p-0">Ikuti</button>
                        </form>
                    @elseif ($user->followers->firstWhere('user_id_1', Auth::user()->id)->status === 0)
                        <button type="button" class="btn btn-outline-info text-dark w-100 p-0" disabled>Dikirim</button>
                    @elseif ($user->followers->firstWhere('user_id_1', Auth::user()->id)->status === 1)
                        <form action="{{ env('APP_URL').'/friend/'.$user->username }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-outline-secondary text-dark w-100 p-0">Diikuti</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
        {{-- Posts --}}
        <div id="posts" class="row justify-content-center mx-auto" style="max-width: 576px;">
            @foreach ($user->posts as $post)
            <img src="{{ asset('storage/post').'/'.$user->username.'/'.$post->image }}" class="col-sm-4 col-6 p-1 open-modal" data-bs-toggle="modal" data-bs-target="#post" role="button" data-slug="{{ $post->slug }}">
            @endforeach
        </div>
    </div>
    {{-- modal post --}}
    <x-modal :page="'dashboard'"/>
    {{-- modal followers list --}}
    <div class="modal fade" id="followers" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="followersLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followersLabel">Diikuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($user->followers->where('status', true) as $data)
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ env('APP_URL').'/profile/'.$data->user_1->username }}" class="link-dark text-decoration-none">
                            <img src="{{ asset('storage/profile').'/'.$data->user_1->image }}" width="36" class="rounded-circle me-1" style="aspect-ratio:1;">
                            <span>{{ $data->user_1->name }}</span>
                        </a>
                    </div>
                    @endforeach
                    @if (Auth::user()->username === $user->username)
                    <hr class="m-0">
                    <p class="m-0 mb-1">Permintaan pertemanan</p>
                    @foreach ($user->followers->where('status', false) as $data)
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ env('APP_URL').'/profile/'.$data->user_1->username }}" class="link-dark text-decoration-none">
                            <img src="{{ asset('storage/profile').'/'.$data->user_1->image }}" width="36" class="rounded-circle me-1" style="aspect-ratio:1;">
                            <span>{{ $data->user_1->name }}</span>
                        </a>
                        <div class="d-flex gap-1">
                            <a href="{{ env('APP_URL').'/friend/accept/'.$data->user_1->username }}" class="btn btn-success p-1">Terima</a>
                            <a href="{{ env('APP_URL').'/friend/reject/'.$data->user_1->username }}" class="btn btn-danger p-1">Tolak</a>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- modal followings list --}}
    <div class="modal fade" id="followings" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="followingsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followingsLabel">Mengikuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($user->followings->where('status', true) as $data)
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ env('APP_URL').'/profile/'.$data->user_2->username }}" class="link-dark text-decoration-none">
                            <img src="{{ asset('storage/profile/').'/'.$data->user_2->image }}" width="36" class="rounded-circle me-1" style="aspect-ratio:1;">
                            <span>{{ $data->user_2->name }}</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()->username === $user->username)
    {{-- modal edit profile --}}
    <div class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ env('APP_URL').'/profile' }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <input type="hidden" name="oldImage" value="{{ $user->image }}">
                        <div class="mx-auto mb-3">
                            <div class="mb-3">
                                <input class="form-control" type="file" accept="image/*" id="inputGroupFile" name="image">
                            </div>
                            <div class="border rounded-lg text-center p-3">
                                <img src="{{ asset('storage/profile').'/'.$user->image }}" class="img-fluid rounded-circle" id="preview" width="100" style="aspect-ratio:1;">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
                            </div>
                        </div>
                        <label class="form-label">Bio <span class="string-length"></span></label>
                        <div class="position-relative mb-3">
                            <input id="bio" type="hidden" name="bio" value="{{ $user->bio }}">
                            <trix-toolbar id="profile" style="display:none;"></trix-toolbar>
                            <trix-editor toolbar="profile" input="bio"></trix-editor>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Private</label>
                            <div class="col-sm-10">
                                <label class="switch">
                                    <input type="checkbox" name="is_private" {{ ($user->is_private) ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 save">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- modal logout --}}
    <div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="logoutLabel">Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ env('APP_URL').'/logout' }}" method="post">@csrf<button type="submit" class="btn btn-danger">Iya</button></form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
@section('js')
    <script>const appLink = "{{ env('APP_URL') }}";</script>
    <script src="{{ asset('js/modalPost.js') }}"></script>
    @if (Auth::user()->username === $user->username)
    <script src="{{ asset('js/trixEvent.js') }}"></script>
    <script src="{{ asset('js/imagePreview.js') }}"></script>
    <script src="{{ asset('js/trix.js') }}"></script>
    @endif
@endsection