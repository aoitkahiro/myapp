{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'csv取り込み画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
              <div class="col-3 offset-9">
                <a href="">戻る</a>
                 <br></br>
              </div>
            <div class="row">
              <div class="col-6 offset-3">
                <div class="form-group">
                    <label for="InputFile">ファイル</label>
                    <input type="file" id="InputFile">
                    <p class="help-block">（CSVファイルonly）</p>
                    <button type="submit" class="btn btn-default">送信</button>
                </div>
                 <button type="button" class="btn btn-warning"><font size="1">作ったcsvデータを取り込む</font></button>
                 <br><br>
                 <button type="button" class="btn btn-warning"><font size="1">データのひな形をダウンロード　　→</font></button>
              </div>
            </div>
        </div>
@endsection