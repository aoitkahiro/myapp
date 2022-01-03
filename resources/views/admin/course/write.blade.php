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
                        <span class="small">画像を追加／変更</span>
                            <input type="file" class="form-control-file" name="image" id="myImage" accept="image/*">
                                <img id="preview" class="image_width">
                            <input type="hidden" name="course_id" value="{{$tango_id_for_write}}">  {{--前のアクションからidを送って→value=に $tango_id_for_writeとして設定  --}} 
                        <p><button type="submit" >保存</button><p><span class="per200_font">.jpg か.png</span><small> 形式の画像ファイルが保存できます</small></p></p>
                    </span>
                </div>
            </div>
      　</form>
    </div>
</div>
<script>
    $('#myImage').on('change', function (e) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endsection
