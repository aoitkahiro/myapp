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
                <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' => 0, 'category' => $unique_category, 'page'=> '1']) }}">
                    <p><button type="button" class="btn btn-warning">{{$unique_category}}</button></p>
                </a> 
            @endforeach
            <br>
            {{--<a href="https://f6003bf85196481c9df5c1f7e84f45ff.vfs.cloud9.us-east-2.amazonaws.com/admin/course/wordbook?abc=1">wordbook.blade.phpへ（idを持っていく）</a>--}}
        </div>
    </div>
@endsection