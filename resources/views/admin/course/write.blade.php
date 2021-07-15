{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'カード書き込み画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
            <div class="row justify-content-around">
              <div class="col-4">
                <a href="">次のカード</a>
                </div>
              <div class="col-4">
                <a href="">戻る</a>
                 <br></br>
              </div>
            </div>
            <div class="row">
              <div class="col-6 offset-3">
                 <label class="col-md-8">表 面</label>
                    <div class="col-md-10">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                    </div>
                    <br>
                 <label class="col-md-8">裏 面</label>
                    <div class="col-md-10">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                    </div>
                    <br>
                 <button type="button" class="btn btn-warning"><font size="1">画像を追加／変更</font></button>
              </div>
              <div class="col-4">
                <a href="">保存</a>\
              </div>
            </div>
        </div>
@endsection