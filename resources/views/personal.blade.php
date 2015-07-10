@extends('mobile')
@section('title')
    Your orders
@endsection

@section('content')
    @forelse($orders as $o)

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
@endsection
