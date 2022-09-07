@extends('layouts.main')
@section('submenu') Home @endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/trix.css') }}">
@endsection
@section('content')
<div class="container" style="margin: 4.5rem auto;">
    @if (session()->has('uploaded'))
        <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('uploaded') }}</div>
    @endif
    @if (session()->has('deleted'))
        <div class="alert alert-warning mx-auto" style="max-width: 576px;">{{ session('deleted') }}</div>
    @endif
    @if (session()->has('updated'))
            <div class="alert alert-success mx-auto" style="max-width: 576px;">{{ session('updated') }}</div>
    @endif
    @foreach ($posts->sortByDesc('created_at') as $post)
    {{-- post card --}}
    <div class="card d-block mx-auto mb-3" style="max-width: 576px;">
        <div class="card-header">
            <a href="{{ env('APP_URL').'/profile/'.$post->user_username }}" class="link-dark text-decoration-none">
                <img src="{{ asset('storage/profile').'/'.$post->user_image }}" width="36" class="rounded-circle me-1" style="aspect-ratio:1;">
                {{ $post->user_name }}
            </a>
        </div>
        <img src="{{ asset('storage/post').'/'.$post->user_username.'/'.$post->image }}" class="card-img-top rounded-0 open-modal" data-bs-toggle="modal" data-bs-target="#post" role="button" data-slug="{{ $post->slug }}">
        <div class="card-body p-2">
            <div class="container d-flex justify-content-between">
                <i class="bi fs-5 like-button {{ ($post->likes->contains('user_username',Auth::user()->username)) ? 'bi-heart-fill text-danger' : 'bi-heart' }} {{ $post->slug }}" role="button" data-slug="{{ $post->slug }}"></i>
                <span class="like-count">{{ $post->likes->count() }} Suka</span>
            </div>
            <p class="card-text">{!! $post->caption !!}</p>
        </div>
    </div>
    @endforeach
</div>
{{-- post modal --}}
<x-modal :page="'home'"/>
@endsection
@section('js')
    <script>const appLink = "{{ env('APP_URL')}}";</script>
    <script src="{{ asset('js/trix.js') }}"></script>
    <script src="{{ asset('js/modalPost.js') }}"></script>
    <script src="{{ asset('js/trixEvent.js') }}"></script>
@endsection