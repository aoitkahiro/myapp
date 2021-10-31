{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '科目選択画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class ="col-3"><font size="2">単語帳</font><font size="1">-暗記率　</font></div>
            <div class ="col-4"><font size="2">クイズで復習</font><font size="1"></font></div>
            <div class ="col-1">　</div>
            <div class ="col-1">　</div>
        </div>
            @for($i = 0; $i < count($unique_categories); $i++)
            <div class="row justify-content-center">
                <div class ="col-md-6">
                    <a class="d-flex" href="{{ action('Admin\CourseController@wordbook', ['tango_id' => 0, 'category' => current( array_slice($unique_categories, $i, 1, true) ), 'page'=> 1 ]) }}">
                        <button type="button" class="btn btn-yellow">{{current( array_slice($unique_categories, $i, 1, true) )}}</button>
                    @if($memory_per[$i] == 100)
                        <span>Complete!</span>
                    @elseif($memory_per[$i] >= 90)
                        <span>{{$memory_per[$i]}}<font size="1">% もう少し！</font></span>
                    @else
                        <span>{{$memory_per[$i]}}<font size="1">%　</font></span>
                    @endif
                    </a>
                </div>
                <div class ="col-md-6">
                    <div class="row">
                        <a href="{{action('Admin\CourseController@quiz',['category'=>current( array_slice($unique_categories, $i, 1, true) ), 'question_quantity'=> 5])}}">
                            <button type="button" class="btn btn-orange">5 問Q</button>
                        </a>
                        @if($five[$i][0] == null)
                        <font size="1">-</font>
                        @elseif($five[$i][0] == 1)
                        👑 1<font size="1">位</font>
                        @else
                        {{$five[$i][0]}}<font size="1">位　</font>
                        @endif
                        <a href="{{action('Admin\CourseController@quiz',['category'=>current( array_slice($unique_categories, $i, 1, true) ), 'question_quantity'=> 10])}}">
                            <button type="button" class="btn btn-orange">10 問Q</button>
                        </a>
                        @if($ten[$i][0] == null)
                        <font size="1">　</font>
                        @elseif($ten[$i][0] == 1)
                        👑 1<font size="1">位　</font>
                        @else
                        {{$ten[$i][0]}}<font size="1">位</font>
                        @endif
                        <a href="{{action('Admin\CourseController@quiz',['category'=>current( array_slice($unique_categories, $i, 1, true) ), 'question_quantity'=> 15])}}">
                            <button type="button" class="btn btn-orange">15 問Q</button>
                        </a>
                        @if($fifteen[$i][0] == null)
                        <font size="1">　</font>
                        @elseif($fifteen[$i][0] == 1)
                        👑 1<font size="1">位　</font>
                        @else
                        {{$fifteen[$i][0]}}<font size="1">位</font>
                        @endif
                    </div>
                </div>
                </div>
            @endfor
            <br>
    </div>
@endsection