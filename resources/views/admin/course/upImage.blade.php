{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '画像準備画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card" style="width: 28rem;">
            <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}"> 
            <div class="card-body">
                <form action="{{ action('Admin\CourseController@profileUpdate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-10">
                        <input type="file" class="form-control-file" name="image">
                    </div>
                    <button type="submit" class="btn btn-success btn-block">管理者として画像をUP</button>
                </form>
            </div>
            <a href="">戻る</a>
        </div>
    </div>
             {{-- <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}">--}}
</div>
@endsection
