{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')

@section('title', 'CSS練習場')

@section('content')
<div class="container">
    <div class="row justify-content-around">
        <p>class container</p>
    </div>
    <div class="d-flex justify-content-around">
        <img style="max-width:150px;" src="{{ asset('storage/tango/4.jpg') }}">
    </div>
    <div class="row justify-content-around">
        <img class="img-fluid" class="col-1 offset-2" style="max-width:150px;" src="{{ asset('storage/tango/4.jpg') }}">
    </div>
    <div class="text-center">
        <p>hogehoge</p>
        <div><img src="hoge"></div>
    </div>
</div>

@endsection