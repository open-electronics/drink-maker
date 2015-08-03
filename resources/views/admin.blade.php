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
                <li class="tab col s3"><a href="#ingredients">Ingredients</a></li>
                <li class="tab col s3"><a href="#drinks">Drinks</a></li>
                <li class="tab col s3"><a href="#settings">Settings</a></li>
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
    <script>
        function doOrderPool(){
            $.get("orders/pending", function(response){
                $("#orders").html(response);
                setTimeout(doOrderPool, 5000);
            });
        };
        doOrderPool();
    </script>
    <div id="drinks">
        @include('admin.drinks')
    </div>
    <div id="settings">
        @include('admin.settings')
        <div class="card-panel">
            <form action="{{url('shutdown')}}" method="post">
                <div class="row">
                    <div class="input-field col s6 offset-s1">
                        <input type="password" name="password" id="password" >
                        <label for="password">Password for shutdown</label>
                    </div>
                    <div class="input-field offset-s1 col s3">
                        <button type="submit" class="btn waves-light red waves-effect">
                            <i class="mdi-content-send right"></i>Shutdown!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection