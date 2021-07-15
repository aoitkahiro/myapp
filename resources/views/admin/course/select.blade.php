{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '単語帳orテスト')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
            <div class="row justify-content-around">
              <div class="col-4">
                <p>選んでね</p>
                </div>
              <div class="col-4">
                <a href="">戻る</a>
                 <br></br>
              </div>
            </div>
            <div class="row">
              <div class="col-6 offset-3">
                    <br>
                 <button type="button" class="btn btn-warning"><font size="1">単語帳</font></button>
                 <br><br>
                 <button type="button" class="btn btn-warning"><font size="1">テスト</font></button>
              </div>
            </div>
        </div>
@endsection