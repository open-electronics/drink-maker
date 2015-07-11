@extends('master')
@section('title')
Drink Maker
@endsection

@section('content')
<div class="row">
    <div class="col offset-s3 s6" align="center">
        <h3>Welcome!!</h3>
    </div>
</div>
@include('messages')
<div class="row" align="center">
    <div class="col offset-s3 s6" align="center">
        <a class="btn waves-effect waves-light" href="{{url('order')}}">
            <i class=" left mdi-maps-local-bar"></i>
            Order something!
            <i class=" right mdi-maps-local-bar"></i>
        </a>
    </div>
</div>
<div class="row">
    <div class="col offset-s3 s6" align="center">
        <a class="btn waves-effect waves-light" href="{{url('orders')}}">
            <i class=" left mdi-action-history"></i>
            View your orders!
            <i class=" right mdi-action-history"></i>
        </a>
    </div>
</div>
<div class="row">
    <div class="col offset-s3 s6" align="center">
        <a class="btn waves-effect waves-light" href="{{url('admin')}}">Login as an administrator</a>
    </div>
</div>
@endsection