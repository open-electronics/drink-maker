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
        <div class="col s6" align="center">
            <h4>Ingredients:</h4>
            <table>
                <thead>
                    <tr>
                        <th data-field="name">Name</th>
                        <th data-field="quantity">Stock</th>
                        <th data-field="position">Position</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $i)
                            <td>{{$i["ingredient"]}}</td>
                            <td>{{$i["stock"]}}</td>
                            <td>{{$i["position"]}}</td>
                        </tr>
                    @empty
                        There are no ingredients yet!
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="col s6" align="center">
            <h4>Orders:</h4>
            @if(count($orders)!=0)
            <table>
                <thead>
                <tr>
                    <th data-field="name">Name</th>
                    <th data-field="status">Status</th>
                    <th data-field="action">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $o)
                    <tr>
                    <td>{{$o["name"]}}</td>
                        @if($o["status"]==0)
                            <td>
                            Waiting approval
                            </td>
                            <td>
                                <form action="{{url("orders/".$o["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">
                                    <input name="_method" type="hidden" value="delete"/>
                                    <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>
                                </form>
                                @if($status==false)
                                <form action="{{url("orders/".$o["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Produce">
                                    <input name="_method" type="hidden" value="patch"/>
                                    <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-send"></i>
                                </form>
                                @endif
                            </td>
                        @elseif($o["status"]==1)
                            <td>
                            Approved
                            </td>
                            <td>
                                <form action="{{url("orders/".$o["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">
                                    <input name="_method" type="hidden" value="delete"/>
                                    <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>
                                </form>
                            </td>
                        @else
                            <td>
                                Making
                            </td>
                            <td>

                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                There are no orders yet!
            @endif
        </div>
    </div>
@endsection