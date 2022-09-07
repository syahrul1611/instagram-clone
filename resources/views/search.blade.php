@extends('layouts.main')
@section('submenu') Search @endsection
@section('content')
<div class="container mx-auto" style="margin-top: 4.5rem; max-width: 576px;">
    <form method="GET" action="{{ env('APP_URL').'/search' }}">
        <div class="input-group mb-3">
            <input type="text" name="keyword" class="form-control" placeholder="Cari user">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>
    @if (isset($users))
        <div class="d-flex flex-sm-row flex-column gap-3 align-items-center justify-content-center">
            @foreach ($users as $user)
            <a href="{{ env('APP_URL')."/profile/".$user->username }}" class="link-dark text-decoration-none">
                <img src="{{ asset('storage/profile')."/".$user->image }}" width="36" class="rounded-circle me-1" style="aspect-ratio:1;">
                <span>{{ $user->name }}</span>
            </a>
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            @foreach ($posts as $post)
            <img src="{{ asset('storage/post').'/'.$post->user_username.'/'.$post->image }}" class="col-sm-4 col-6 p-1 open-modal" data-bs-toggle="modal" data-bs-target="#post" role="button" data-slug="{{ $post->slug }}">
            @endforeach
        </div>
    @endif
</div>
{{-- modal post --}}
<x-modal :page="'search'"/>
@endsection
@section('js')
    <script>const appLink = "{{ env('APP_URL') }}";</script>
    <script src="{{ asset('js/modalPost.js') }}"></script>
    <script src="{{ asset('js/trixEvent.js') }}"></script>
@endsection