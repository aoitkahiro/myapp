@extends('layouts.admin')
@section('title', '単語帳')
@section('content')
<div class="container">
    @if($message != "")
        <p>{{$message}}</p>
        {{--この行に、ここ以下のコードを実行しない命令を記述するべきと考えられるものの、思いつかないため保留2021.8.13--}}
    @endif
    <a href="{{action('Admin\CourseController@write',['tango_id'=>$post[$tango_id]->id])}}">編集</a> {{--  URL：?tango_id=1が生成される（URLにおいて?で送られる数値をgetパラメータという--}} 
    <br>*ここからデバッグ用の記述です<br><br>
    <h2>今のページの<br>user_idは{{$user->id}}<br>course_idは{{$post[$tango_id]->id}}<br>
    関連する<br>histories_tableのidは 
    @if($value != NULL)
        {{$value->id}}<br>learning_levelは{{$value->learning_level}}
    @else
        ありません（まだレコードなし）
    @endif
    </h2>
    <br>ここまでデバッグ用の記述です*<br>
    <div class="col">
        @if($value == NULL or $value->learning_level == 0 ) {{-- もしhistoriesテーブルのtango_id番めのレコードの learning_level が0かNULLなら --}}
            <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
            @csrf
                <p>
                    <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> {{--1ページごとなので、foreachではなく具体的な数値を$tango_idで渡している--}} 
                    <input type="hidden" name="learning_level" value="1"> {{--すでに知っている」をsubmitしたとき、0→1へ切り替える--}}
                    <input type="hidden" name="tango_id" value= {{$tango_id}}>{{--再度同じページにredirectするために、$tango_idを渡す--}}
                    <input type="submit" class="btn btn-primary" value="最初から知ってる">
                </p>
            </form>
        @else
            <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}}
            @csrf
                <p>
                    <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
                    <input type="hidden" name="learning_level" value="0"> {{--最初から知っている を解除」をsubmitしたとき、0へ切り替える--}}
                    <input type="hidden" name="tango_id" value= {{$tango_id}}>
                    <input type="submit" class="btn btn-secondary" value="最初から知ってる を解除">
                </p>
            </form>
        @endif
    </div>

    <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data"> 
        @csrf
        <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> 
        <input type="hidden" name="tango_id" value= {{$tango_id}}>
        <input type="radio" <?php if(empty($history) or $history->learning_level == 0){ echo "checked";} ?>> 未記憶
        
        <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
        <input type="hidden" name="tango_id" value= {{$tango_id}}>
        <input type="radio" name="learning_level" value="1"<?php if(isset($history) and $history->learning_level == 1){ echo "checked";} ?>> 覚えた
        
        <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
        <input type="hidden" name="tango_id" value= {{$tango_id}}>
        <input type="radio"  name="learning_level" value="2"<?php if(isset($history) and $history->learning_level == 2){ echo "checked";} ?>> 最初から知ってる
        
        <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> 
        <input type="hidden" name="tango_id" value= {{$tango_id}}>
        <input type="radio" name="learning_level" value="0"> 未記憶に戻す
        
        <input type="submit" value="更新">
    </form>
    
    <div class="row">
        <div class="text-center">
            <button type="button" class="btn btn-warning"><font size="8">{{ $post[$tango_id]->front }} ...course_id:{{$post[$tango_id]->id}}</font></button>
        </div>
    </div>
    <div>
        {{-- JavaScript --}} 
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
    </div>
        <input type="button" value="ヒント画像on/off" onclick="clickBtn2()" /> {{--onclick 動かす関数を指定している  --}} 
    <div class="card" style="width: 18rem;">
        <img src="{{ asset('storage/tango/' . $post[$tango_id]->id . "." . 'jpg') }}" id="piyo" class="bd-placeholder-img card-img-top" width="100%" height="180"> 
    </div>     {{-- asset()でディレクトリを指定、受け取っている値で詳しいファイル名を指定 --}}
    <div>
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
    <div class="row justify-content-center">
        <div>
        @if($tango_id == 0)
        @else
            <button type="button" class="btn btn-warning"><font size="1">◀</font></button><br>
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id -1]) }}">前へ</a>
        @endif
        </div>
        <div class="col-auto">
            {{--＠今何ページ目か表示--}}{{$tango_id +1}} / {{--＠全何ページか表示--}}{{$post->count()}}
        </div>
        <div class="col col-lg-2">
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
<div class="text-center">
    {{$user->name}}さん
    e-mail: {{$users[0]->email}}
</div>
@endsection