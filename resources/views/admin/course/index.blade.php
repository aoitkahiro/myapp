{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '最初の画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
            <div class="col-6 offset-3">
                <br><br>
                <button type="button" class="btn btn-warning">TOEIC単語 目標500点</button>
            </div>
        </div>
@endsection