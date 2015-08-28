
{{--@section('title')--}}
    {{--Order your cocktail!--}}
{{--@endsection--}}

{{--@section('content')--}}
@extends('mobile')
@section('title')
Order your cocktail!
@endsection
@section('content')

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
                        <input value="10" max="{{$max}}" min="10" name="volume" type="number" id="vol">
                        <label for="vol">Insert the drink volume</label>
                    </div>
                </div>
                <div align="center">Click the cocktail names to see their ingredients!<br>
                Click the bell to order the cocktail!</div>
                    @forelse($drinks as $drink)
                        <div data-id="{{$drink->id}}" data-max="{{$drink->maxAvailable}}" class="drink card-panel col s12 m6 z-depth-1-half">
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
                                        <a href="#" class="order" id="{{$drink->id}}" >
                                            <img class="responsive-img circle" src="{{'uploads/'.$drink->photo}}">
                                        </a>
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
@endsection