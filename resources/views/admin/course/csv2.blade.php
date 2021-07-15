{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'最初の画面'を埋め込む --}}
@section('title', 'csv取り込み画面')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')





        <div class="container">
              <div class="col-3 offset-9">
                <a href="">戻る</a>
                 <br></br>
              </div>
            <div class="row">
              <div class="col-6 offset-3">
                <form action="{{ action('Admin\CourseController@csv2') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <label class="col-1 text-right" for="form-file-1">File:</label>
                        <div class="col-11">
                            <div class="custom-file">
                                <input type="file" name="csv" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile" data-browse="参照">ファイル選択...</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">送信</button>
                </form>
                @if(Session::has('done')) {{-- フラッシュメッセージ --}} 
                                {{ session('done') }}
                @endif
                 <button type="button" class="btn btn-warning"><font size="1">作ったcsvデータを取り込む</font></button>
                 <br><br>
                 <button type="button" class="btn btn-warning"><font size="1">データのひな形をダウンロード　　→</font></button>
              </div>
            </div>
        </div>
@endsection
