{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'プロフィール編集画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
            <br>
            <div class="row">
              <div class="col-6 offset-3">
                <form action="{{ action('Admin\CourseController@profileUpdate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">変更</button>
                        @if(Session::has('done')) {{-- フラッシュメッセージ --}} 
                                        {{ session('done') }}
                        @endif
                    <br>
                    <div class= "form-group row" >
                        <label class="col-md-2">画像</label>
                        <div class="col-md-10">
                            <input type="file" class="form-control-file" name="image">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">ニックネーム</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">目標を宣言</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="mygoal" value="{{ old('mygoal') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">すでに知っている単語のページ</label>
                        <div class="col-md-10">
                            <input type="radio" name="has_known" value="0">表示
                            <input type="radio" name="has_known" value="1">非表示
                        </div>
                    </div>
                </form>
              </div>
                <div class="col-3 offset-9">
                  <a href="">戻る</a>
                  <br><br><br>
                </div>
                <img src="{{ asset('storage/tango/' . Auth::user()->image_path) }}"> {{-- asse()でディレクトリを指定、受け取っている値で詳しいファイル名を指定 --}} 
                <br>
            </div>
            <br>
        </div>
@endsection
