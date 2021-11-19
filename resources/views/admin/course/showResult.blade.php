@extends('layouts.admin')
@section('title', 'showResult')
@section('content')
<div class="text-center">
    <h1 class="text-center">showResult　ページ（開発中）</h1>
    
    <br>{{$correct}}問 / {{$question_quantity}}問中
    <br>タイム：{{$running_time}}
    <br><img class="d-block mx-auto" style="max-width:150px;" src="{{$img}}">
    <br>{{$message}}
    <br>
    <br>
      <a href="{{action('Admin\CourseController@index')}}" type="button" id="goIndex" class="btn btn-black col-3"><font size="2">もどる</font></a>
      <a href="{{action('Admin\CourseController@quiz',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="restart" class="btn btn-black col-3"><font size="2">もう一度</font></a>
      <a href="{{action('Admin\CourseController@ranking',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="goRanking" class="btn btn-black col-3">👑ランキング</a>
    <br>
    {{$ranking_title}}
    <br>今回間違えた単語
            <div class="row">
                <table class="table table-dark table-hover">
                <thead>
                      <tr style="vertical-align: middle">
                          <th width="10%" class="text-center"><font size="1">問題</font></th>
                          <th width="35%">答え</th>
                      </tr>
                  </thead>
                  <tbody>
                @foreach($incorrect_fronts as $front)
                      <tr class="Ranking">
                          <td class= "d-flex align-items-center justify-content-center"><font size="7">{{$front}}</font></td>
                          <td class= "text-center" style="vertical-align: middle">答えは準備中{{--<img class="gazou" style="max-height:70px;" src="{{ asset('storage/tango/' . $rank["画像"]) }}">--}}</td>
                          {{--<td style= "vertical-align: middle">{{ \Str::limit($rank["目標"], 15) }}</td>--}}
                      </tr>
                @endforeach
                  </tbody>
                </table>
            </div>
        </div>
    <br>
</div>
@endsection