{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'プロフィール編集画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
        <div class="container">
            <br>
            <div class="row">
              <div class="col-8 offset-2">
               <div class="card" class="row" style="width: 28rem;">
                      <img class="d-block mx-auto" style="max-width:150px;" src="{{ asset('storage/tango/' . Auth::user()->image_path) }}"> {{-- asset()でディレクトリを指定、受け取っている値で詳しいファイル名を指定 --}} 
                 <div class="card-body">
                <form action="{{ action('Admin\CourseController@profileUpdate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <br>
                    <div class= "row" >
                        <label class="col-md-2">画像</label>
                        <div class="col-md-10">
                            <input type="file" class="form-control-file" name="image">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">ニックネーム</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="name" value="{{ $a_user->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">目標を宣言</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="mygoal" value="{{ $a_user->mygoal }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">カードはどのレベルまで表示しますか？</label>
                        <div class="col-md-12">
                            <input type="radio" name="has_known" value="0" <?php if($a_user->has_known == 1){ echo "checked";} ?>>look_level == 2 / 全部表示<br>
                            <input type="radio" name="has_known" value="1" <?php if($a_user->has_known == 0){ echo "checked";} ?>>look_level == 1 / [最初から知ってる] のカードを隠す<br>
                            <input type="radio" name="has_known" value="1" <?php if($a_user->has_known == NULL){ echo "checked";} ?>>look_level == 0 / [最初から知ってる] [覚えた]のカードを隠す<br>
                        </div>
                    </div>
                <div class="col-3 offset-0">
                <button type="submit" class="btn btn-success btn-block">変更</button>
                    @if(Session::has('done')) {{-- フラッシュメッセージ --}} 
                                    {{ session('done') }}
                    @endif
                </div>
                </form>
               </div>
                <div class="col-3 offset-9">
                  <a href="">戻る</a>
                  <br><br><br>
                </div>
               </div>
             </div>
            </div>
            <br>
        </div>
@endsection
