{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '単語帳')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
          <br><br>
            <div class="row justify-content-around">
              <div class="col-4">
                <a href="">暗記済みにする☑</a>
              </div>
              <div class="col-4">
                <a href="">覚えにくい単語にする☑</a>
              </div>
              <div class="col-4">
                <a href="">編集</a>
              </div>
            </div>
           <br>
           <br>
            <div class="row">
              <div class="col-6 offset-3">
                 <button type="button" class="btn btn-warning"><font size="4">@front_text</font></button>
                 <br><br>
                 <button type="button" class="btn btn-warning"><font size="3">@image をヒントとして表示するボタン</font></button>
              </div>
            </div>
          <br><br>
            <div class="row justify-content-center">
                <div class="col col-lg-1">
                  <button type="button" class="btn btn-warning"><font size="1">◀</font></button>
                </div>
                <div class="col-auto">
                  ＠今のページ / ＠今の科目の全ページ
                </div>
                <div class="col col-lg-1">
                  <button type="button" class="btn btn-warning"><font size="1">▶</font></button>
                </div>
            </div>
        </div>

@endsection