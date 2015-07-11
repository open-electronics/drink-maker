@extends('mobile')
@section('title')
Configure your machine!
@endsection

@section('content')
    <div class="card-panel">
        @include('messages')
        @include('admin.settings')
    </div>
@endsection