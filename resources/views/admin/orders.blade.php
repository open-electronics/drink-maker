<div class="row">
    <div class="col offset-s1 s10" align="center">
        <h4>Orders:</h4>
        @if(count($orders)!=0)
            <table>
                <thead>
                <tr>
                    <th data-field="name">Name</th>
                    <th data-field="volume">Volume</th>
                    <th data-field="customer">Customer</th>
                    <th data-field="status">Status</th>
                    <th data-field="action">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $o)
                    <tr>
                        <td>{{$o->Drink->name}}</td>
                        <td>{{$o->Drink->volume}}</td>
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
                        @elseif($o["stauts"]==2 || $o["status"]=="2")
                            <td>
                                Making
                            </td>
                            <td>
                                <form action="{{url("orders/".$o["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">
                                    <input name="_method" type="hidden" value="delete"/>
                                    <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>
                                </form>
                            </td>
                        @elseif($o["status"]==5)
                            <td>
                                Waiting start
                            </td>
                            <td>
                            <td>
                                <form action="{{url("orders/".$o["id"])}}" method="post" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Delete">
                                    <input name="_method" type="hidden" value="delete"/>
                                    <i style="cursor: pointer" onclick="$(this).closest('form').submit();" class="mdi-content-clear"></i>
                                </form>
                            </td>
                            </td>
                        @else
                            <td>
                                Timed out
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