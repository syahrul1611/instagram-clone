@extends('layouts.main')
@section('submenu') Upload @endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/trix.css') }}">
@endsection
@section('content')
    <div class="container mb-5" style="margin-top: 4.5rem; max-width: 576px;">
        @if ($errors->any())
            <div class="alert alert-danger mx-auto" style="max-width: 576px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ env('APP_URL').'/post' }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mx-auto mb-3">
                <div class="mb-3">
                    <label for="inputGroupFile" class="form-label">Pilih foto yang akan di upload</label>
                    <input class="form-control" type="file" accept="image/*" id="inputGroupFile" name="image">
                </div>
                <div class="border rounded-lg text-center p-3">
                    <i class="bi bi-camera fs-1 preview-camera"></i>
                    <img src="" class="img-fluid" id="preview"/>
                </div>
            </div>
            <label class="form-label">Caption <span class="string-length">(0/150)</span></label>
            <input id="x" type="hidden" name="caption">
            <div class="position-relative">
                <trix-toolbar id="hidden_trix_toolbar" style="display:none;"></trix-toolbar>
                <trix-editor toolbar="hidden_trix_toolbar" input="x"></trix-editor>
            </div>
            <button type="submit" class="btn btn-info text-white w-100 mt-2 submit">Unggah</button>
        </form>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/imagePreview.js') }}"></script>
    <script src="{{ asset('js/trix.js') }}"></script>
    <script>
        addEventListener("trix-change", event => {
            const { editor } = event.target;
            const string = editor.getDocument().toString();
            const characterCount = string.length - 1;
            $('.string-length').text(`(${characterCount}/150)`);
            if (characterCount > 150) {
                $('.string-length').css('color', 'red');
                $('.submit').attr('disabled','');
            } else {
                $('.string-length').css('color', 'black');
                $('.submit').removeAttr('disabled','');
            }
        });
    </script>
@endsection