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
                <li class="tab col s4"><a href="#ingredients">Ingredients</a></li>
                <li class="tab col s3"><a href="#drinks">Drinks</a></li>
            </ul>
        </div>
    </div>
    @include('messages')
    <div id="ingredients">
        <div class="row">
            <div class="col s5 offset-s1" align="center">
                <h4>Ingredients:</h4>
                <form method="post" id="quantities" action="{{url('ingredients')}}">
                <input name="_method" type="hidden" value="patch"/>
                <table>
                    <thead>
                        <tr>
                            <th data-field="name">Name</th>
                            <th data-field="quantity">Stock</th>
                            <th data-field="position">Position</th>
                            {{--<th data-field="delete">Delete</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ingredients as $i)
                            <tr>
                                <td>{{$i["ingredient"]}}<input value="{{$i["id"]}}" name="id[]" type="hidden"></td>
                                <td><input value="{{$i["stock"]}}" name="stock[]" type="number" class="validate"></td>
                                <td><input value="{{$i["position"]}}" name="position[]" min="-1" max="9" type="number" class="validate"></td>
                                {{--<td>--}}
                                    {{--<form action="{{url("ingredients/".$i["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">--}}
                                        {{--<input name="_method" type="hidden" value="delete"/>--}}
                                        {{--<i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>--}}
                                    {{--</form>--}}
                                {{--</td>--}}
                            </tr>
                        @empty
                            There are no ingredients yet!
                        @endforelse
                    </tbody>
                </table>
                    <button type="submit" class="btn waves-effect waves-light" name="action">Save
                        <i class="mdi-content-send right"></i>
                    </button>
                </form>
            </div>
            <div class="col offset-s1 s4" align="center">
                <h4>Add ingredient:</h4>
                <form method="post" action="{{url('ingredients')}}">
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="name" type="text" class="validate" maxlength="50">
                            <label for="name">Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input name="stock" type="number" class="validate" value="0" min="0">
                            <label for="stock">Stock quantity:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col offset-s7 s5">
                            <button type="submit" class="btn waves-effect waves-light" name="action">Add
                                <i class="mdi-content-add right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="orders">
        <div class="row">
            <div class="col offset-s1 s10" align="center">
                <h4>Orders:</h4>
                @if(count($orders)!=0)
                    <table>
                        <thead>
                        <tr>
                            <th data-field="name">Name</th>
                            <th data-field="customer">Customer</th>
                            <th data-field="status">Status</th>
                            <th data-field="action">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $o)
                            <tr>
                                <td>{{$o["name"]}}</td>
                                <td>{{$o["customer"]}}</td>
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
                                        <form action="{{url("orders/".$o["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">
                                            <input name="_method" type="hidden" value="delete"/>
                                            <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>
                                        </form>
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
    </div>
    <div id="drinks">
        <div class="row">
            <div class="col s5 offset-s1" align="center">
                <h4>Drinks:</h4>
                <table>
                    <thead>
                    <tr>
                        <th data-field="name">Name</th>
                        <th data-field="delete">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($drinks as $d)
                        <tr>
                            <td>{{$d["name"]}}<input value="{{$d["id"]}}" name="id" type="hidden"></td>
                            <td>
                                <form action="{{url("drinks/".$d["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">
                                    <input name="_method" type="hidden" value="delete"/>
                                    <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>
                                </form>
                            </td>
                        </tr>
                    @empty
                    There are no drinks yet!
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col offset-s1 s4" align="center">
                <h4>Add drink:</h4>
                <form method="post" action="{{url('drinks')}}">
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="name" maxlength="50">
                            <label for="name">Drink name</label>
                        </div>
                    </div>
                    @for($i=0;$i<5;$i++)
                        <div class="row">
                            <div class="input-field col s6">
                                <select name="ingredients[]" class="browser-default">
                                    <option value="0" selected>None</option>
                                    @foreach($ingredients as $in)
                                        <option value="{{$in["id"]}}">{{$in["ingredient"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-field col offset-s1 s4">
                                <input type="number" value="1" name="parts[]" min="1" max="9">
                                <label for="parts">Parts</label>
                            </div>
                        </div>
                    @endfor
                    <div class="row">
                        <button type="submit" class="btn waves-effect waves-light" name="action">Add
                            <i class="mdi-content-add right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection