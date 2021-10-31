@extends('layouts.admin')
@section('title', '単語帳')
@section('content')
<script>
document.getElementById("p1").style.display ="none";
function clickBtn1(){
    const p1 = document.getElementById("p1");
    // jqueryの場合
    // $('#p1').hide();
    // $('#p1').show();
    if (p1.style.display=="block"){
        p1.style.display ="none";
    } else {
        p1.style.display ="block";
    }
}
document.getElementById("p3").style.display ="none";
function clickBtn3(){
    const p3 = document.getElementById("p3");
    if(p3.style.display=="block"){
        p3.style.display ="none";
    }else{
        p3.style.display ="block";
    }
}
document.getElementById("piyo").style.display ="none";
function clickBtn2(){
    const p2 = document.getElementById("piyo");
    if(p2.style.display=="block"){
        p2.style.display ="none";
    }else{
        p2.style.display ="block";
    }
}
</script>
{{$user->name}}さんの単語帳 「{{$unique_category}}」 記憶率「{{$bunshi_num}} / {{$bunbo_num}}」
<div class="container">
    @if($message != "")
        <p>{{$message}}</p>
    @endif
    <div class="col">
        <a class="btn btn-warning" href="{{ action('Admin\CourseController@index') }}">戻る</a><a> </a>
        <a class="btn btn-warning" href="{{action('Admin\CourseController@write',['category'=>$unique_category,'tango_id'=>$post[$tango_id]->id,'page'=>$tango_id])}}">編集</a>
    </div>
    <div>
        <span>{{ $post[$tango_id]->front }}</span>
    </div>
    <div>
        {{-- JavaScript --}}
        <input type="button" class="btn btn-secondary" value="裏面on/off" onclick="cardToggle()" />
        <p id="p1">{{ $post[$tango_id]->back }}</p>
        <input type="button"  class="btn btn-secondary" value="メモ" onclick="memo()" />
        <p id="p3">{{ $post[$tango_id]->memo }}</p>
        <input type="button"  class="btn btn-secondary"  value="{{$hintImage}}" onclick="clickBtn2()" />
    </div>
    <div class="col card" style="width: 24rem;">
        <img src="{{ secure_asset('storage/tango/' . $post[$tango_id]->getImageFileName()) }}" id="piyo" class="bd-placeholder-img card-img-top" width="100%" height="180">
    </div>
    <div class="col">
        <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <p>
            <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
            <input type="hidden" name="tango_id" value= {{$tango_id}}>
            <input type="hidden" name="category" value= {{urlencode($unique_category)}}>
            @if($value == NULL or $value->learning_level < 2 )
                <input type="hidden" name="learning_level" value="2">
                <input type="submit" class="btn btn-primary" value="最初から知ってる">
            @else
                <input type="hidden" name="learning_level" value="1">
                <input type="submit" class="btn btn-secondary" value="最初から知ってる を解除">
            @endif
        </p>
        </form>
    </div>
    <div class="col">
        <form action="{{ action('Admin\StatusController@store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <p>
                <input type="hidden" name="course_id" value={{$post[$tango_id]->id}}>
                <input type="hidden" name="tango_id" value= {{$tango_id}}>
                <input type="hidden" name="category" value= {{urlencode($unique_category)}}>
                @if($value == NULL or $value->learning_level == 0 )
                    <input type="hidden" name="learning_level" value="1">
                    <input type="submit" class="btn btn-primary" value="覚えた">
                @else
                    <input type="hidden" name="learning_level" value="0">
                    <input type="submit" class="btn btn-secondary" value="覚えた を解除">
                @endif
            </p>
        </form>
    </div>
    <div class="row justify-content-center">
        <div>
        @if($tango_id != 0)
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id -1, 'category' => $unique_category]) }}">
            <button type="button" class="btn btn-warning"><font size="1">◀</font></button><br>前へ</a>
        @endif
        </div>
        <div class="col-auto">
            {{--＠今何ページ目か表示--}}{{$tango_id +1}} / {{--＠全何ページか表示--}}{{$post->count()}}
        </div>
        <div class="col col-lg-2">
        @if($tango_id +1 == $post->count())
            <a href="{{action('Admin\CourseController@reward',['category'=>$unique_category])}}">
            <button type="button" class="btn btn-warning"><font size="1">Q</font></button><br>最後の単語です</a><br>
        @else
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 1, 'category' => $unique_category]) }}">
            <button type="button" class="btn btn-warning"><font size="1">▶</font></button><br>次へ</a><br>
            <a href="{{ action('Admin\CourseController@wordbook', ['tango_id' =>$tango_id + 5, 'category' => $unique_category]) }}">5個次へ</a>
        @endif
        </div>
    </div>
</div>
<div class="col-md-8 offset-md-1">
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
@endsection