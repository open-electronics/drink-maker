@extends('master')
@section('title')
Homepage
@endsection

@section('content')
<div class="row">
    <div class="col offset-s3 s6" align="center">
        <h3>Benvenuto</h3>
    </div>
</div>
<div class="row">
    <div class="col offset-s2 s3" align="center">
        <a class="btn waves-effect waves-light" href="{{url('/admin')}}">Admin</a>
    </div>
    <div class="col offset-s2 s3" align="center">
        <a class="btn waves-effect waves-light" href="{{url('/user')}}">User</a>
    </div>
</div>
@endsection