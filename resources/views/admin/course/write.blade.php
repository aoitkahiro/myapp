{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'カード書き込み画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
  <div class="container">
    <div class="row justify-content-around">
      <div class="col-4">
        <a href="">次のカード</a>
      </div>
      <div class="col-4">
        <a href="{{action('Admin\CourseController@wordbook', ['category'=>$unique_category,'tango_id' => $page])}}">戻る</a>
         <br></br>
      </div>
    </div>
    <div class="row">
      <form action="{{ action('Admin\CourseController@update',['category'=>$unique_category,'page' => $page] )}}" method="post" enctype="multipart/form-data">
        <div class="col-6 offset-3">
          @csrf
          <label class="col-md-8">表 面</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="front" value={{ $a_course->front }}> {{--"{{ old('title') }}">--}}
          </div>
          <br>
          <label class="col-md-8">裏 面</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="back" value={{ $a_course->back }}>
          </div>
          <br>
          <button type="button" class="btn btn-warning"><font size="1">画像を追加／変更</font></button>
          <div class="col-md-10">
            <input type="file" class="form-control-file" name="image">
            <input type="hidden" name="course_id" value="{{$tango_id_for_write}}">  {{--前のアクションからidを送って→value=に $tango_id_for_writeとして設定  --}} 
          </div>
        </div>
          <br><small> 　　　　　　　.jpg 形式の画像ファイルのみ保存できます</small><br>
        <div class="col-4">
          <button type="submit" class="btn btn-success btn-block">保存</button>
          <img src="{{ asset('storage/tango/' . $tango_id_for_write . "." . "jpg") }}"> {{--表示用 asse()でディレクトリを指定、受け取っている値で詳しいファイル名を指定 --}}
        </div>
    　</form>
    </div>
  </div> {{-- まずcourseの情報を渡して、course->で情報を表示させる --}} 
@endsection