@extends('layouts.admin')

@section('title', 'end')

@section('content')
<div class="container">
    {{$unique_category}}
    <div class="row justify-content-center">
        <div class="card" style="width: rem;">
            <p><img class="d-block mx-auto" style="max-width:300px;" src="{{ secure_asset('image/' . 'admireLady.png') }}"></p>
            <p><a href="{{ action('Admin\CourseController@index', ['has_done' =>$has_done,'last_category'=>$unique_category]) }}" class="btn btn-success btn-block">科目一覧へ戻る</a></p>
                                
        </div>
    </div>
</div>
@endsection