{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')
@section('title', '単語帳')
@section('content')
<div class="container justify-content-around">
    @if($message != "")
        <p>{{$message}}</p>
        {{--この行に、ここ以下のコードを実行しない命令を記述するべきと考えられるものの、思いつかないため保留2021.8.13--}}
    @endif
    <div class="row">
        <form action="{{-- action('Admin\StatusController@store') --}}" method="post" enctype="multipart/form-data"> {{-- multipart/form-data は複数データ送信用 --}} 
        @csrf
            <input type="submit" class="btn btn-primary" value="覚えた">
        </form>
    </div>
    <div>
        @if($history == NULL or $history->hide_known == 0) {{-- もしhistoriesテーブルの hide_known が0かNULLなら --}}  
        <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
        @csrf
        <p>
            <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> {{--1ページごとなので、foreachではなく具体的な数値を$tango_idで渡している--}} 
            <input type="hidden" name="hide_learned" value="1">
            <input type="hidden" name="hide_known" value="1"> {{--すでに知っている」をsubmitしたとき、0→1へ切り替える--}}
            <input type="hidden" name="tango_id" value= {{$tango_id}}>{{--再度同じページにredirectするために、$tango_idを渡す--}}
            <input type="submit" class="btn btn-primary" value="すでに知っている">
          </p>
        </form> 
        @else
        <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
          @csrf
          <p>
            <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> {{--1ページごとなので、foreachではなく具体的な数値を$tango_idで渡している--}} 
            <input type="hidden" name="hide_known" value="0"> {{--すでに知っている を解除」をsubmitしたとき、0へ切り替える--}}
            <input type="hidden" name="tango_id" value= {{$tango_id}}>
            <input type="submit" class="btn btn-primary" value="すでに知っている を解除">
          </p>
        </form>
        @endif
    </div>
       {{-- ★☆ --}} <div class="col-4">
        <a href="{{action('Admin\CourseController@write',['tango_id'=>$post[$tango_id]->id])}}">編集</a> {{--  URL：?tango_id=1が生成される（URLにおいて?で送られる数値をgetパラメータという--}} 
      </div>
    </div>
    <br>
    <br>
    <div class="row">
       {{-- ★☆ --}} <div class="col-6 offset-3">
        <button type="button" class="btn btn-warning"><font size="8">{{ $post[$tango_id]->front }}</font></button>
         <br><br>          {{-- JavaScript --}} 
          <p id="p1">{{ $post[$tango_id]->back }}</p>
          <input type="button" value="裏面on/off" onclick="clickBtn1()" />
          <script>
          //初期表示は非表示
          document.getElementById("p1").style.display ="none";
          
          function clickBtn1(){
          	const p1 = document.getElementById("p1");
          
          	if(p1.style.display=="block"){
          		// noneで非表示
          		p1.style.display ="none";
          	}else{
          		// blockで表示
          		p1.style.display ="block";
          	}
          }
          </script>
         <br><br>

            <input type="button" value="ヒント画像on/off" onclick="clickBtn2()" /> {{--onclick 動かす関数を指定している  --}} 
        <br><br>
        <div class="card" style="width: 18rem;">
          <img src="{{ asset('storage/tango/' . $post[$tango_id]->id . "." . 'jpg') }}" id="piyo" class="bd-placeholder-img card-img-top" width="100%" height="180"> 
        </div>     {{-- asset()でディレクトリを指定、受け取っている値で詳しいファイル名を指定 --}}
          <script>
          //初期表示は非表示
          document.getElementById("piyo").style.display ="none";
          
          function clickBtn2(){
          	const p2 = document.getElementById("piyo"); 
          
          	if(p2.style.display=="block"){ {{--もしp2が表示されていれば  --}} 
          		// noneで非表示
          		p2.style.display ="none"; {{--  p2のスタイル（CSS）display属性を非表示にする（見えなくなる） --}} 
          	}else{
          		// blockで表示
          		p2.style.display ="block";{{--  p2のスタイル（CSS）display属性を表示にする（見えるようにする） --}} 
          	}
          }
          </script>
      </div>
    </div>
    <br>
    <div class="row justify-content-center">
         {{-- ★☆ --}} <div class="col col-lg-2">
          @if($tango_id == 0)
          @else
          <button type="button" class="btn btn-warning"><font size="1">◀</font></button><br>
          <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id -1]) }}">前へ</a>
          @endif
        </div>
         {{-- ★☆ --}} <div class="col-auto">
          {{-- {{--＠今何ページ目か表示--}}{{$tango_id +1}} / {{--＠全何ページか表示--}}{{$post->count()}}
        </div>
         {{-- ★☆ --}} <div class="col col-lg-2">
          @if($tango_id +1 == $post->count())
          <button type="button" class="btn btn-warning"><font size="1">　</font></button><br>
          <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 1]) }}">最後の単語です</a><br>
          @else
          <button type="button" class="btn btn-warning"><font size="1">▶</font></button><br>
          <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 1]) }}">次へ</a><br>
          <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 2]) }}">２個次へ</a>
          @endif
        </div>
    </div>
  </div>
<br> {{$user->name}}
<br> {{$user->email}}
<br> {{$users[0]->email}}
@endsection