@extends('layouts.admin')
@section('title', 'R')
@section('content')

<div class="container">
  <p id="">"{{$courses[0]->category}}"に挑戦した人たち</p>
  <ol>
  @foreach($rankings as $rank)
    <li class="Ranking"><img class="gazou" style="max-width:20px;" src="{{ asset('storage/tango/' . $rank["画像"]) }}"> {{$rank["name"]}} さん　正解数：{{$rank["正解回数"]}}　タイム：{{$rank["タイム"]}}　挑戦日：{{$rank["挑戦日"]}}　今の目標：{{$rank["目標"]}}</li>
  @endforeach
  </ol>
</div>
  <p class="margin_bottom_2"></p>
<div class="container">
  <p class="margin_bottom_2"></p>
  <a href="{{action('Admin\CourseController@quiz')}}" type="button" id="restart" class="btn btn-black"><h2>↺</h2><br><h8>もう一度</h8></a>
  <a href="{{action('Admin\CourseController@index')}}" type="button" id="goIndex" class="btn btn-black"><h2>↩</h2><br><h8>もどる</h8></a>

</div>

  
@endsection
@section('js')

  <script>
  </script>

@endsection