@extends('layouts.admin')
@section('title', 'R')
@section('content')

<div class="container">
  <p id="">"{{$courses[0]->category}}"に挑戦した人たち</p>
  {{--@foreach($hoge as $h)--}}
    <li class="Ranking"> {{$result->user_id}} さん 記録：???秒challengeidごとの最大秒</li>
  {{--@endforeach  --}}
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