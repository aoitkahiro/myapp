@extends('layouts.admin')

@section('title', 'プロフィール編集画面')

@section('content')
<div class="container cardPropaty">
    <div class="row justify-content-center">
        <div style="width: 28rem;">
            <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}"> 
            <div class="card-body cardPropaty">
                <form action="{{ action('Admin\CourseController@profileUpdate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <p class="col-md-10">
                       好きな画像を登録して下さい（登録は任意） <input type="file" class="form-control-file" name="image"id="myImage" accept="image">
                        <img id="preview" class="image_width" >
                    </p>
                    <label class="col-md-4">ニックネーム</label>
                        <input type="text" class="form-control" name="name" value="{{ $a_user->name }}">
                    <label class="col-md-4">目標を宣言</label>
                        <input type="text" class="form-control" name="mygoal" value="{{ $a_user->mygoal }}">
                    
                        {{-- FIX ME:--}}
                    <button type="submit" class="btn btn-warning btn-block mt-5">設定完了！</button>
                </form>
                <form action="{{ action('Admin\StatusController@changeIsImageDisplayed') }}" method="post" enctype="multipart/form-data">  {{--  ActionタグにURLを書く--}} 
                @csrf
                @if( Auth::user()->is_image_displayed == true)
                    <button type="submit" class="btn btn-secondary my-5 primaryBtnWidth" name="is_image_displayed" value=0 >画像を最初は表示しない</button>
                @else    
                    <button type="submit" class="btn btn-primary my-5 primaryBtnWidth" name="is_image_displayed" value=1>画像を最初から表示する</button>
                @endif
                </form>
            </div>
        </div>
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
