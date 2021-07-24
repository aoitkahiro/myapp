{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', '単語帳')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
          <br><br>
            <div class="row justify-content-around">
              <div class="col-4">
                <a href=""></a>
              </div>
             <form action="{{-- action('Admin\StatusController@store') --}}" method="post" enctype="multipart/form-data">
               @csrf  {{--  セキュリティに関係するもので、必要--}} 
              <div class="col-4">
                <input type="submit" class="btn btn-primary" value="覚えた">{{-- bool値 trueを送りたい--}}
                 {{-- hiddenタグをinputタグより前に置く --}} 
             </form>
                <a href="">最初から知ってる</a>
              </div>
              <div class="col-4">
                <a href="">編集</a>
              </div>
            </div>
           <br>
           <br>
            <div class="row">
              <div class="col-6 offset-3">
                 <button type="button" class="btn btn-warning"><font size="4">@front_text</font></button>
                 <br>
                 <br>
                 <h1>{{ $post->front }}</h1>
                  {{-- JavaScript --}} 
                                <p id="p1">{{ $post->back }}</p>
                  <input type="button" value={{$post->front}} onclick="clickBtn1()" />
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
                 <button type="button" class="btn btn-warning"><font size="3">@image をヒントとして表示するボタン</font></button>
              </div>
            </div>
          <br><br>
            <div class="row justify-content-center">
                <div class="col col-lg-2">
                  <button type="button" class="btn btn-warning"><font size="1">◀</font></button><br>
                 {{-- <a href="{{ action('Admin\CourseController@wordbook', ['abc' =>$post->id -1]) }}">前へ</a> --}}
                </div>
                <div class="col-auto">
                 {{-- {{--＠今何ページ目か表示--}}{{$page_num}} / {{--＠全何ページか表示--}}{{$all_courses_count}} --}}
                </div>
                <div class="col col-lg-2">
                  <button type="button" class="btn btn-warning"><font size="1">▶</font></button><br>
                 {{-- <a href="{{ action('Admin\CourseController@wordbook', ['abc' =>$post->id + 1]) }}">次へ</a><br> --}}
                 {{-- <a href="{{ action('Admin\CourseController@wordbook', ['abc' =>$post->id + 2]) }}">２個次へ</a> --}}
                </div>
            </div>
        </div>
 {{$user->id}}
 {{$user->name}}
 {{$hoge}}
@endsection