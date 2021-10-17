{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '科目選択画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
    <div class="container">
        <div class="col-6 offset-3">
            <br><br>
            @foreach($unique_categories as $unique_category)
                <p>
                    <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' => 0, 'category' => $unique_category, 'page'=> 1 ]) }}">
                        <button type="button" class="btn btn-warning">{{$unique_category}}</button>
                    </a> 
                    <a href="{{action('Admin\CourseController@quiz',['category'=>$unique_category])}}">
                        <button type="button" class="btn btn-warning">クイズ</button>
                    </a>
                </p>
            @endforeach
            <br>
            </div>
    </div>
@endsection