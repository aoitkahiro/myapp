@extends('layouts.admin')
@section('title', '単語帳')
@section('content')
<div class="container">
    {{$user->name}}さんの単語帳 <font size="5">「{{$unique_category}}」</font>  記憶率<font size="5">「{{$bunshi_num}} / {{$bunbo_num}}」</font>
</div>
<div class="container">
    @if($message != "")
        <p>{{$message}}</p>
        {{--この行に、ここ以下のコードを実行しない命令を記述するべきと考えられるものの、思いつかないため保留2021.8.13--}}
    @endif
    <div class="margin_bottom_2em">
        <a href="{{ action('Admin\CourseController@index',['has_done'=> 1,'last_category'=>$unique_category]) }}" class="btn btn--circle btn--circle-c btn--shadow"><i class="fas fa-arrow-up">←</i></a>
        <a href="{{action('Admin\CourseController@write',['category'=>$unique_category,'tango_id'=>$post[$tango_id]->id,'page'=>$tango_id])}}" class="btn btn--circle btn--circle-c btn--shadow"><i class="fas fa-arrow-up">✍</i></a>
    </div>
    {{--<div>*ここからデバッグ用の記述です</div>
    <h6>(今のページの)<br>user_idは {{$user->id}}<br>course_idは {{$post[$tango_id]->id}}<br>
    関連する<br>histories_tableのidは 
    @if($value != NULL)
        {{$value->id}}<br>learning_levelは {{$value->learning_level}}
    @else
        ありません（まだレコードなし）
    @endif
    </h6>
    ここまでデバッグ用の記述です*<br>--}}
    <div>
        <a href="{{$google_url}}" target="_blank" rel="noopener noreferrer" class="btn btn-border">
            <span class="stress_roman_letter">{{$post[$tango_id]->front}}</span>
        </a>
        　<a href="{{$google_url_oboekata}}" target="_blank" rel="noopener noreferrer">[覚え方]</a>
        <a href="{{$EtoJ_weblio_url}}" target="_blank" rel="noopener noreferrer">[英和]</a>
    </div>
    <div>
        {{-- JavaScript --}} 
        <input type="button" value="裏面on/off" onclick="clickBtn1()" />
        <p id="p1">
        <a href="{{$google_url_back}}" target="_blank" rel="noopener noreferrer"><font size="5">{{ $post[$tango_id]->back}}</font></a>
        　<span><font size="2"><a href="{{$JtoJ_weblio_url}}" target="_blank" rel="noopener noreferrer">[辞書]</a>
        <a href="{{$JtoN_weblio_url}}" target="_blank" rel="noopener noreferrer">[インドネシア語]</a></font></span>
        </p>
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
    <div>
        <input type="button" value="{{$memo_exists}}" onclick="clickBtn3()" />
            <form id="p3" action="{{ action('Admin\CourseController@update',['category'=>$unique_category,'tango_id' => $tango_id, 'page'=>$tango_id] )}}" method="post" enctype="multipart/form-data">
            @csrf
                <textarea class="form-control" name="memo" rows="3">{{ $post[$tango_id]->memo }}</textarea>
                <input type="hidden" name="course_id" value={{ $post[$tango_id]->id }}>
                <button type="submit" >保存</button>
            </form>
        <script>
            {{--初期表示は非表示--}}
            document.getElementById("p3").style.display ="none";
            
            function clickBtn3(){
              const p3 = document.getElementById("p3");
            
              if(p3.style.display=="block"){
              	{{-- noneで非表示--}}
              	p3.style.display ="none";
              }else{
              	{{--blockで表示--}}
              	p3.style.display ="block";
              }
            }
        </script>
    </div>
    {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}} 
    
    <input type="button" value="{{$hintImage}}" onclick="clickBtn2()" /> {{--onclick 動かす関数を指定している  --}} 
    <div  style="width: 24rem;">
        @if($post[$tango_id]->getImageFileName()){{--boolean--}}
           <img src="{{ secure_asset('storage/tango/' . $post[$tango_id]->getImageFileName()) }}?{{time()}}" id="piyo" class="bd-placeholder-img card-img-top">
        @else
           <img src="{{ secure_asset('image/noimage.jpg')}}" id="piyo" class="bd-placeholder-img card-img-top">
        @endif
    </div>     {{-- asset()でディレクトリを指定、受け取っている値で詳しいファイル名を指定 --}}
    <div class="margin_bottom_2em">
        <script>
            {{--初期表示は非表示--}}
        @if( $user->is_image_displayed == true)
            document.getElementById("piyo").style.display ="block";
        @else
            document.getElementById("piyo").style.display ="none";
        @endif
            function clickBtn2(){
              const p2 = document.getElementById("piyo"); 
            
              if(p2.style.display=="block"){ {{--もしp2が表示されていれば   
              noneで非表示--}}
              	p2.style.display ="none"; {{--  p2のスタイル（CSS）display属性を非表示にする（見えなくなる）--}} 
              }else{
              	 {{--blockで表示--}}
              	p2.style.display ="block";{{--  p2のスタイル（CSS）display属性を表示にする（見えるようにする） --}} 
              }
            }
        </script>
    </div>
    {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}} 
    <div>
        @if($value == NULL or $value->learning_level == 0 or $value->learning_level == 1 ) {{-- もしhistoriesテーブルのtango_id番めのレコードの learning_level が0かNULLなら --}}
            <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
            @csrf
                <p>
                    <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> {{--1ページごとなので、foreachではなく具体的な数値を$tango_idで渡している--}} 
                    <input type="hidden" name="learning_level" value="2"> {{--すでに知っている」をsubmitしたとき、0→1へ切り替える--}}
                    <input type="hidden" name="tango_id" value= {{$tango_id}}>{{--再度同じページにredirectするために、$tango_idを渡す--}}
                    <input type="hidden" name="category" value= {{urlencode($unique_category)}}>
                    <input type="submit" class="btn btn-primary" value="最初から知ってる">
                </p>
            </form>
        @else
            <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}}
            @csrf
                <p>
                    <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
                    <input type="hidden" name="learning_level" value="1"> {{--最初から知っている を解除」をsubmitしたとき、0へ切り替える--}}
                    <input type="hidden" name="tango_id" value= {{$tango_id}}>
                    <input type="hidden" name="category" value= {{urlencode($unique_category)}}>
                    <input type="submit" class="btn btn-secondary" value="最初から知ってる を解除">
                </p>
            </form>
        @endif
    </div>
    <div>
        @if($value == NULL or $value->learning_level == 0 ) {{-- もしhistoriesテーブルのtango_id番めのレコードの learning_level が1,0かNULLなら --}}
            <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
            @csrf
                <p>
                    <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}> {{--1ページごとなので、foreachではなく具体的な数値を$tango_idで渡している--}} 
                    <input type="hidden" name="learning_level" value="1"> {{--覚えたをsubmitしたとき、0→2へ切り替える--}}
                    <input type="hidden" name="tango_id" value= {{$tango_id}}>{{--再度同じページにredirectするために、$tango_idを渡す--}}
                    <input type="hidden" name="category" value= {{urlencode($unique_category)}}>
                    <input type="submit" class="btn btn-primary" value="覚えた">
                </p>
            </form>
        @else
            <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}}
            @csrf
                <p>
                    <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
                    <input type="hidden" name="learning_level" value="0"> {{--最初から知っている を解除」をsubmitしたとき、0へ切り替える--}}
                    <input type="hidden" name="tango_id" value= {{$tango_id}}>
                    <input type="hidden" name="category" value= {{urlencode($unique_category)}}>
                    <input type="submit" class="btn btn-secondary" value="覚えた を解除">
                </p>
            </form>
        @endif
    </div>
    <div class="row justify-content-center">
        <div>
        @if($tango_id == 0)
        @else
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id -1, 'category' => $unique_category]) }}">
            <button type="button" class="btn btn-warning"><font size="1">◀</font></button><br>前へ</a>
        @endif
        </div>
        <div class="col-auto">
            {{--＠今何ページ目か表示--}}{{$tango_id +1}} / {{--＠全何ページか表示--}}{{$post->count()}}
        </div>
        <div class="col col-lg-2">
        @if($tango_id +1 == $post->count())
            <a href="{{action('Admin\CourseController@reward',['unique_category'=>$unique_category])}}">
            <button type="button" class="btn btn-warning"><font size="1">Q</font></button><br>最後の単語です</a><br>
        @else
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 1, 'category' => $unique_category]) }}">
                <button type="button" class="btn btn-warning"><font size="1">▶</font></button><br>次へ
            </a><br>
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 5, 'category' => $unique_category]) }}">5個次へ</a>
        @endif
        </div>
    </div>
</div>
<div class="container">
    どの単語を隠しますか？
    <form action="{{ action('Admin\StatusController@levelChange') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
    @csrf
            <input type="radio" name="looking_level" value="0" <?php if($user->looking_level == 0){ echo "checked";} ?>>
            <button type="submit" class="btn btn-primary margin_bottom_2px" name="looking_level" value= 0> 隠さない</button>
            <input type="hidden" name="tango_id" value= {{$tango_id}}>
            <input type="hidden" name="category" value= {{mb_convert_encoding($unique_category, 'UTF-8')}}>
    </form>
    <form action="{{ action('Admin\StatusController@levelChange') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
    @csrf
            <input type="radio" name="looking_level" value="1" <?php if($user->looking_level == 1){ echo "checked";} ?>>
            <button type="submit" class="btn btn-primary margin_bottom_2px" name="looking_level" value= 1>「最初から知ってる」</button> をクリックした単語は隠す
            <input type="hidden" name="tango_id" value= {{$tango_id}}>
            <input type="hidden" name="category" value= {{mb_convert_encoding($unique_category, 'UTF-8')}}>
    </form>
    <form action="{{ action('Admin\StatusController@levelChange') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
    @csrf
            <input type="radio" name="looking_level" value="1" <?php if($user->looking_level == 2){ echo "checked";} ?>>
            <button type="submit" class="btn btn-primary margin_bottom_2px" name="looking_level" value= 2>「最初から知ってる」「覚えた」</button> をクリックした単語は隠す
            <input type="hidden" name="tango_id" value= {{$tango_id}}>
            <input type="hidden" name="category" value= {{mb_convert_encoding($unique_category, 'UTF-8')}}>
    </form>
</div>
<div class="container">
    <form action="{{ action('Admin\StatusController@changeIsImageDisplayed') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
    @csrf
    @if( $user->is_image_displayed == true)
            <button type="submit" class="btn btn-secondary margin_bottom_2px" name="is_image_displayed" value="false">image画像を最初から表示しない</button>に切りかえる
    @else    
            <button type="submit" class="btn btn-primary margin_bottom_2px" name="is_image_displayed" value="true">image画像を最初から表示する</button>に切りかえる
    @endif
            <input type="hidden" name="tango_id" value= {{$tango_id}}>
            <input type="hidden" name="category" value= {{mb_convert_encoding($unique_category, 'UTF-8')}}>
    </form>
<button onclick="location.href='mailto:wordquizmaster&#64;outlook.jp?subject=Request for deletion（削除依頼）&amp;body=To master(T.Aoi)%0d%0aPlese delete category:{{$unique_category}}%0d%0afrom {{$user->name}}%0d%0a%0d%0acategory:{{$unique_category}}を消して欲しいです。%0d%0a{{$user->name}}より。'">この科目の削除依頼</button>
@if($user->id == 3){{--user_idが管理者なら--}}
    <button onclick="location.href='{{--{{App\Course::deleteCategory($unique_category)}}--}}'">この科目を消すボタン</button>
@endif
</div>
@endsection