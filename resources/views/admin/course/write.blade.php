@extends('layouts.admin')

@section('title', 'カード書き込み画面')

@section('content')
<div class="container">
    <div class="row my-4">
        <a class="col-11 offset-1" href="{{action('Admin\CourseController@wordbook', ['category'=>$unique_category,'tango_id' => $page])}}">戻る</a>
    </div>
    <div class="row">
        <form action="{{ action('Admin\CourseController@update',['category'=>$unique_category,'page' => $page] )}}" method="post" enctype="multipart/form-data">
            <div class="col-11 offset-1">
            @csrf
                <label class="col-md-8">表 面</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="front" value="{{ $a_course->front }}">
                </div>
                <label class="col-md-8 mt-2">裏 面</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="back" value="{{ $a_course->back }}">
                </div>
                <div class="mt-2">
                    <span style="display:inline">
                        <span class="small">画像を追加／変更</span><input type="file" class="form-control-file" name="image">
                            <input type="hidden" name="course_id" value="{{$tango_id_for_write}}">  {{--前のアクションからidを送って→value=に $tango_id_for_writeとして設定  --}} 
                        <button type="submit" >保存</button>.jpg か.png<small> 形式の画像ファイルが保存できます</small>
                    </span>
                </div>
            </div>
            <div class="col card" style="width: 24rem;">
                <img src="{{ asset('storage/tango/' . $tango_id_for_write . "." . "jpg") }}">
            </div>
      　</form>
    </div>
</div>
@endsection
