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
                        <div class="col offset-s1 s10">
                            Ordered by: {{$o->name}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col offset-s1 s10">
                            Ordered at: {{$o->created_at}}
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

        </div>
    </div>
@endsection
