@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!
            <a href="{{ action('Admin\CourseController@profile')}}"　class="btn btn-dark">Click here</a>
            </div>
        </div>
    </div>
</div>
@endsection
