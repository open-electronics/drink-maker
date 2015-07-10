<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order number {{$order->id}}</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="../css/materialize.min.css">
    <!-- Jquery -->
    <script src="../js/jquery-2.1.4.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="../js/materialize.min.js"></script>
    {{--App related--}}
    <link rel="stylesheet" href="../css/app.css">
    <script src="../js/app.js"></script>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
<br>
<div class="card-panel">
    <div class="row">
        <div id="title" data-id="{{$order->id}}" class="col offset-s3 s6" align="center">
            <h3>Order number {{$order->id}}</h3>
        </div>
    </div>
    @include('messages')
    <div class="row">
        <div class="col offset-s1 s10">
            <i class=" small mdi-social-person-outline"></i>
            Ordered by: {{$order->name}}
        </div>
    </div>
    <div class="row">
        <div class="col offset-s1 s10">
            <i class=" small mdi-maps-local-bar"></i>
            Order: {{$order->Drink->name}} {{$order->volume}} mL
        </div>
    </div>
    <div class="row">
        <div class="col offset-s1 s10">
            <i class=" small mdi-av-timer"></i>
            Ordered at: {{$order->created_at}}
        </div>
    </div>
    <script>
        function doPool(){
            var id= $("#title").attr("data-id");
            $.get(id+"/async", function(response){
                $(".target").html(response);
                setTimeout(doPool, 5000);
            });
        };
        doPool();
    </script>
    <div class="row">
        <div class="col offset-s1 s10 target">
            @include('order.status')
        </div>
    </div>
    <div class="row">
        <div class="col offset-s1 s10 offset-m1 m4">
            <a class="btn waves-effect waves-light" href="{{url('order')}}">
                <i class="mdi-content-undo"></i>Go back
            </a>
            <br>
        </div>
        @if(in_array([0,1,5,6],$order->status))
        <div class="col offset-s1 s10 offset-m2 m4">
            <form>
                <button type="submit" class="btn waves-effect waves-light red" href="{{url('order')}}">
                    <input type="hidden" name="_method" value="delete">
                    <i class="mdi-content-clear right"></i>Delete order
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
</body>