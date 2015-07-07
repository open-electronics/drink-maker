@extends('master')
@section('title')
    Admin
@endsection

@section('content')
    <div class="row">
        <div class="col offset-s3 s6" align="center">
            <h3>Admin</h3>
        </div>
    </div>
    <div class="row">
        <div class="col s10 offset-s1">
            <ul class="tabs">
                <li class="tab col s3"><a href="#orders">Orders</a></li>
                <li class="tab col s4"><a href="#ingredients">Ingredients</a></li>
                <li class="tab col s3"><a href="#drinks">Drinks</a></li>
            </ul>
        </div>
    </div>
    @include('messages')
    <div id="ingredients">
        @include('admin.ingredients')
    </div>
    <div id="orders">
        @include('admin.orders')
    </div>
    <div id="drinks">
        @include('admin.drinks')
    </div>
@endsection