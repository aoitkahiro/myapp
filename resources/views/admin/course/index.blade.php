@extends('layouts.admin')

@section('title', '科目選択画面')

@section('content')
    <div class="container">
    <div class ="col-md-11"><img class="gazou" style="max-height:30px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}">　{{Auth::user()->name }}さん 　<font size="1">今の目標：</font>{{Auth::user()->mygoal }}</div>
    </div>
    <div class="container">
        <div class="row justify-content-center margin_bottom_2px">
            <div class ="col-6"><font size="2">単語帳</font><font size="1">-暗記率　</font></div>
            <div class ="col-4"><font size="2">Qで復習</font><font size="1"></font></div>
        </div>
            @for($i = 0; $i < count($unique_categories); $i++)
            <div class="row">
                <div class ="col-md-6">
                    <div class ="row">
                        <div class ="col-9  justify-content-center margin_bottom_2px">
                            <a class="d-flex" href="{{ action('Admin\CourseController@wordbook', ['tango_id' => 0, 'category' => current( array_slice($unique_categories, $i, 1, true) ), 'page'=> 1 ]) }}">
                            @if($has_done != NULL)
                                @if($last_category == current( array_slice($unique_categories, $i, 1, true) ))
                                    <button type="button" class="btn btn-outline-success">{{current( array_slice($unique_categories, $i, 1, true) )}}:DONE!</button>
                                @else
                                    <button type="button" class="btn btn-yellow">{{current( array_slice($unique_categories, $i, 1, true) )}}</button>
                                @endif
                            @else
                                <button type="button" class="btn btn-yellow">{{current( array_slice($unique_categories, $i, 1, true) )}}</button>
                            @endif
                            </a>
                        </div>
                        <div class ="col-3">
                            @if($memory_per[$i] == 100)
                                <span>Complete!</span>
                            @elseif($memory_per[$i] >= 90)
                                <span>{{$memory_per[$i]}}<font size="1">% もう少し！</font></span>
                            @else
                                <span>{{$memory_per[$i]}}<font size="1">%　</font></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class ="col-md-6">
                    <div class ="row">
                    <div class ="col-4">
                        <a href="{{action('Admin\CourseController@quiz',['category'=>current( array_slice($unique_categories, $i, 1, true) ), 'question_quantity'=> 5])}}">
                            <button type="button" class="btn btn-orange">5 問Q</button>
                        </a>
                    </div>
                    <div class ="col-4">
                        <a href="{{action('Admin\CourseController@quiz',['category'=>current( array_slice($unique_categories, $i, 1, true) ), 'question_quantity'=> 10])}}">
                            <button type="button" class="btn btn-orange">10 問Q</button>
                        </a>
                    </div>
                    <div class ="col-4">
                        <a href="{{action('Admin\CourseController@quiz',['category'=>current( array_slice($unique_categories, $i, 1, true) ), 'question_quantity'=> 15])}}">
                            <button type="button" class="btn btn-orange">15 問Q</button>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            @endfor
            <br>
    </div>
@endsection