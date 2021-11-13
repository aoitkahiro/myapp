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
    <br>
</div>
@endsection