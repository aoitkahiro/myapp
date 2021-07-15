{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '作成開始画面')

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
                 <label class="col-md-8">タイトル</label>
                    <div class="col-md-10">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                    </div>
                    <br>
                 <button type="button" class="btn btn-warning"><font size="1">この名前で作成</font></button>
                 <br><br>
                 <button type="button" class="btn btn-warning"><font size="1">とにかく作成（名前は後で）</font></button>
              </div>
            </div>
        </div>
@endsection