@extends('layouts.admin')
@section('title', 'showResult')
@section('content')
<div class="text-center">
    <h2 class="text-center">結 果</h2>
    <h1 class="mt-2">{{$correct}}問 / {{$question_quantity}}問中</h1>
    <h1>タイム：{{$running_time}}</h1>
    <p><img class="d-block mx-auto" style="max-width:150px;" src="{{$img}}"></p>
    <p>{{$message}}</p>
        <a href="{{action('Admin\CourseController@index')}}" type="button" id="goIndex" class="btn btn-black col-3">もどる</a>
        <a href="{{action('Admin\CourseController@quiz',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="restart" class="btn btn-black col-3">もう一度</a>
        <a href="{{action('Admin\CourseController@ranking',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="goRanking" class="btn btn-black col-3">👑ランキング</a>
    <p class="mt-2">{{$ranking_title}}</p>
    <p class="eye_catching_word">今回間違えた単語</p>
    <div style="max-width:98%" class="row">
        <table style="max-width:98%" class="col-md-8 mx-auto table table-dark table-hover">
            <thead>
                <tr style="vertical-align: middle">
                    <th width="40%" class="text-center">問題</th>
                    <th width="40%">答え</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < $list_length; $i++)
                <tr class="Ranking">
                    <td class= "d-flex align-items-center justify-content-center eye_catching_word">{{$incorrect_fronts[$i]}}</td>
                    <td class= "text-center eye_catching_word" style="vertical-align: middle">{{$incorrect_backs[$i]}}</td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection