@extends('layouts.admin')
@section('title', 'クイズ')
@section('content')

前提知識
<input type="hidden" name="score" value="100">
サーバーでは$request->scoreで100が取得できる
<div class="container">
  
  <form name="recordtime" action="{{ HogeController@save}}" method="post">
    <input type="hidden" name="score" id="score">
    <input type="hidden" name="running_time" id="running_time">
    <button type="button" id="save_button">記録を送信する</button>
  </form>
     
  <script>
    document.getElementById('save_button').addEventListener('click', function(e){
    document.getElementById('running_time').value = ;{{-- 実際にかかった時間　//idがtimeのタグ（inputタグ）のvalue属性に「実際にかかった時間を」代入する --}} 
    document.getElementById('score').value = ; {{--正解数とか？ --}} 
    document.forms['recordtime'].submit();  {{--このフォームの送信ボタンを押した時と同じ挙動をする <input type="submit" value="送信ボタン">のsubmitと同じ意味 --}} 
    })
  </script>
  
</div>
  
  <script src="app.js"></script>
  <script src="index.js"></script>
@endsection