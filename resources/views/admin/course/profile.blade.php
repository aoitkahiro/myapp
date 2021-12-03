{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'プロフィール編集画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card" style="width: 28rem;">
            <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}"> 
            <div class="card-body">
                <form action="{{ action('Admin\CourseController@profileUpdate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <p class="col-md-10">
                       画像を設定して下さい <input type="file" class="form-control-file" name="image">
                    </p>
                    <label class="col-md-4">ニックネーム</label>
                    <input type="text" class="form-control" name="name" value="{{ $a_user->name }}">
                    <label class="col-md-4">目標を宣言</label>
                    <input type="text" class="form-control" name="mygoal" value="{{ $a_user->mygoal }}">
                    
                        <br>
                        <form action="{{ action('Admin\StatusController@changeIsImageDisplayed') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
                        @csrf
                        @if( Auth::user()->is_image_displayed == true)
                            <button type="submit" class="btn btn-secondary margin_bottom_2px primaryBtnWidth" name="is_image_displayed" value=0 >画像を最初から表示しない</button>
                        @else    
                            <button type="submit" class="btn btn-primary margin_bottom_2px primaryBtnWidth" name="is_image_displayed" value=1>画像を最初から表示する</button>
                        @endif
                        </form>
                    <br>
                    <button type="submit" class="btn btn-warning btn-block"><a href="{{ action('Admin\CourseController@index')}}">設定完了！</a></button>
                </form>
            </div>
        </div>
    </div>
             {{-- <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}">--}}
</div>
@endsection
