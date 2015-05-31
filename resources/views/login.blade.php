@extends('master')
@section('title')
    Login
@endsection

@section('content')
    <div class="row">
        <div class="col offset-s3 s6" align="center">
            <h3>Login</h3>
        </div>
    </div>
    @include('messages')
    <div class="row">
        <form class="col s10 offset-s1" method="post" action="{{'login'}}">
            <div class="row">
                <div class="input-field col s10 offset-s1">
                    <input name="name" type="text" class="validate">
                    <label for="name">Name</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s10 offset-s1">
                    <input name="password" type="password" class="validate">
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="row">
                <div class="col offset-s8 s3">
                    <button class="btn waves-light waves-effect" type="submit" name="action">
                        Submit <i class="mdi-content-send right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection