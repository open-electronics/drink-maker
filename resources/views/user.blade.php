
{{--@section('title')--}}
    {{--Order your cocktail!--}}
{{--@endsection--}}

{{--@section('content')--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order your cocktail!!</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="css/materialize.min.css">
    <!-- Jquery -->
    <script src="js/jquery-2.1.4.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="js/materialize.min.js"></script>
    {{--App related--}}
    <link rel="stylesheet" href="css/app.css">
    <script src="js/app.js"></script>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
    <div class="row">
        <div class="col offset-s3 s6" align="center">
            <h4>Order your cocktail!</h4>
        </div>
    </div>
    @include('messages')
    <div class="row">
        <div class="col s12">
            <form method="post" action="{{url("orders")}}">
                <input type="hidden" id="id" name="id" value="">
                <div class="row">
                    <div class="input-field col s12">
                        <input name="name" type="text" maxlength="30" id="na">
                        <label for="na">Insert your name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input value="2" max="200" min="2" name="volume" type="number" id="vol">
                        <label for="vol">Insert the drink volume</label>
                    </div>
                </div>
                <div align="center">Click the cocktail names to see their ingredients!<br>
                Click the bell to order the cocktail!</div>
                <?php $i=0 ?>
                    @forelse($drinks as $drink)
                        <?php $i++ ?>
                        <div data-id="{{$drink->id}}" data-max="{{$drink->maxAvailable}}" class="drink card-panel col s12 m5 {{($i%2==0)?"offset-m2":""}} z-depth-1-half">
                            <div class="row" >
                                <i class="mdi-maps-local-bar small"></i> <span>{{$drink->name}}</span>
                                @if($drink->maxAvailable>2)
                                    <a href="#" class="order" id="{{$drink->id}}" >
                                        <i data-disable="mdi-av-not-interested" data-enable="mdi-social-notifications" class="mdi-social-notifications small right"></i>
                                    </a>
                                @else
                                    <i class="mdi-av-not-interested small right"></i>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col s6 offset-s1">
                                    Ingredients:<ul>@foreach($drink->Ingredients as $ingred)<li>{{$ingred->pivot->needed." parts of ".$ingred->name}}</li>@endforeach</ul>
                                </div>
                                <div class="col s4">
                                    @if($drink->photo!=null)
                                        <img class="responsive-img circle" src="{{'uploads/'.$drink->photo}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
            </form>
        </div>
    </div>
</body>
</html>
{{--@endsection--}}