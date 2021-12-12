@extends('layouts.admin')
@section('title', 'showResult')
@section('content')
<div class="text-center">
    <h2 class="text-center">結 果</h2>
    <h1 class="mt-2">{{$correct}}問 / {{$question_quantity}}問中</h1>
    <h1>タイム：{{$running_time}}</h1>
    <p><img class="d-block mx-auto" style="max-width:150px;" src="{{$img}}"></p>
    <p>{{$message}}</p>
      <a href="{{action('Admin\CourseController@index')}}" type="button" id="goIndex" class="btn btn-black col-3"><font size="3">もどる</font></a>
      <a href="{{action('Admin\CourseController@quiz',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="restart" class="btn btn-black col-3"><font size="2">もう一度</font></a>
      <a href="{{action('Admin\CourseController@ranking',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="goRanking" class="btn btn-black col-3">👑ランキング</a>
    <p class="mt-2">{{$ranking_title}}</p>
    <p>今回間違えた単語</p>
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
                  <td class= "d-flex align-items-center justify-content-center"><font size="5">{{$incorrect_fronts[$i]}}</font></td>
                  <td class= "text-center" style="vertical-align: middle"><font size="5">{{$incorrect_backs[$i]}}</font>{{--<img class="gazou" style="max-height:70px;" src="{{ asset('storage/tango/' . $rank["画像"]) }}">--}}</td>
              </tr>
            @endfor
            </tbody>
          </table>
      </div>
</div>
@endsection