@extends('master')
@section('title')
    User
@endsection

@section('content')
    <div class="row">
        <div class="col offset-s3 s6" align="center">
            <h3>User</h3>
        </div>
    </div>
    <div class="row">
        <div class="col s8 offset-s2">
            <ul class="collapsible" data-collapsible="accordion">
                @forelse($drinks as $n=>$d)
                    <li>
                        <div class="collapsible-header">
                            <i class="mdi-maps-local-bar medium"></i> {{$n}}
                            @if($d["available"])
                            <a href="{{url("orders/".$n)}}">
                                <i class="mdi-social-notifications medium right"></i>
                            </a>
                            @else
                                <i class="mdi-av-not-interested medium right"></i>
                            @endif
                        </div>
                        <div class="collapsible-body">
                            <div class="row">
                                <div class="col s10 offset-s1">
                                    Ingredients:<ul>@foreach($d["ingredients"] as $ingred=>$data)<li>{{$data["needed"]." parts of ".$ingred}}</li>@endforeach</ul>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>

@endsection