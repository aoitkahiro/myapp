{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'end')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card" style="width: rem;">
            <p><img class="d-block mx-auto" style="max-width:300px;" src="{{ secure_asset('image/' . 'sugoi.jpg') }}"></p>
            {{--<p><a href="{{action('Admin\CourseController@index')}}">戻る</a></p>--}}なぜかエラー？解決？
            <p><a href="{{ action('Admin\CourseController@index') }}">戻る</a></p>
        </div>
    </div>
             {{-- <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}">--}}
</div>
@endsection
