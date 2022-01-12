@extends('layouts.admin')

@section('title', 'csv取り込み画面')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-6 offset-4">
            <form action="{{ action('Admin\CourseController@csv2') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-9 csvBox">
                        <div class="custom-file">
                            <input type="file" name="csv" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile" data-browse="参照">作ったcsvをこちらへ...</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-block" style="width:70%">登録</button>
            </form>
            @if(Session::has('done')) {{-- フラッシュメッセージ --}} 
                            {{ session('done') }}
            @endif
            <a href="{{secure_asset('csv/' . 'form.csv')}}" download>
                <button type="button" onclick="clickBtn2()" class="btn btn-success mt-4" style="width:70%">まずはひな型をGETする (Get form_template)</button>
            </a>
        </div>
            <div class="mt-1">
                <img src="{{ secure_asset('image/csvExam.png')}}" id="example" class="bd-placeholder-img card-img-top">
            </div>
            <div class="margin_bottom_2em example">
                <script>
                    {{--初期表示は非表示--}}
                    document.getElementById("example").style.display = "none";
                    
                    function clickBtn2(){
                      const ex = document.getElementById("example"); 
                    
                      if(ex.style.display == "block"){
                      	ex.style.display = "none";
                      }else{
                      	ex.style.display = "block";
                      }
                    }
                </script>
            </div>
    </div>
</div>
@endsection
