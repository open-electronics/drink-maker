@extends('mobile')
@section('title')
    Your orders
@endsection

@section('content')
    <div class="row">
        <div class="col offset-s1 s10">

    @forelse($orders as $o)
        <a href="{{url('orders/'.$o->id)}}">
            <div class="card-panel">
                <div class="row">
                    <div class="col s3">
                        @if($o->Drink->photo!=null)
                        <img class="responsive-img circle" src="{{'uploads/'.$o->Drink->photo}}">
                        @endif
                    </div>
                    <div class="col offset-s1 s6">
                        <div class="row">
                            <div class="col s12">
                                {{$o->Drink->name}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                Ordered by: {{$o->name}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                Ordered at: {{$o->created_at}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="row">
            <div class="col offset-s2 s8" align="center">
                You don't have any orders yet!
            </div>
        </div>
        <div class="row"align="center">
            <div class="col offset-s4 s4">
                <a href="{{url('order')}}" class="btn waves-effect waves-light">
                    Order one!
                </a>
            </div>
        </div>
    @endforelse
        <div class="row">
                <a class=" col s4 btn waves-effect waves-light" href="{{url('maker')}}">
                    <i class="mdi-content-undo"></i>Go back
                </a>
        </div>
        </div>
    </div>
@endsection
