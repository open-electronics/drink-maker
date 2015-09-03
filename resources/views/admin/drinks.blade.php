<div class="row">
    <div class="col s4 offset-s1" align="center">
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
    <div class="col offset-s1 s5" align="center">
        <h4>Add drink:</h4>
        <form id="newDrink" method="post" enctype="multipart/form-data" action="{{url('drinks')}}">
            <div class="row">
                <div class="input-field col s8">
                    <input type="text" id="dname" name="name" maxlength="50">
                    <label for="dname">Drink name</label>
                </div>
                <div class="col s3 file-field input-field">
                    <div class="btn waves-effect waves-light">
                        <i class="mdi-editor-insert-photo"></i>
                        <input type="file" name="photo" />
                    </div>
                </div>
            </div>
            @for($i=0;$i<5;$i++)
                <div class="row">
                    <div class="input-field col s6">
                        <select name="ingredients[]" class="browser-default">
                            <option value="0" selected>None</option>
                            @foreach($ingredients as $in)
                                <option value="{{$in["id"]}}">{{$in["name"]}}</option>
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
                <div class="col s10 offset-s1">
                    <input type="number" value="2" name="volume" min="2" max="500">
                    <label for="volume">Drink volume(cL)</label>
                </div>
            </div>
            <div class="row">
                <div class="col s7 offset-s1">
                    <button id="drinkSubmit" type="submit" onclick="event.preventDefault(); save()" class="btn waves-effect waves-light" name="action">Save
                        <i class="mdi-content-add right"></i>
                    </button>
                </div>
            </div>
            <script>
                function save(){
                    //if($('input[name="quantity"]'))
                    var l = $("input[name='parts[]']");
                    var c = $("select[name='ingredients[]']");
                    var tot= 0;
                    for(var i = 0; i< l.length;i++){
                        if(c[i]["value"]!=0) {
                            tot += parseInt(l[i]["value"]);
                        }
                    }
                    if($("input[name='volume']").val()%(2*tot)==0) {
                        $('#newDrink').trigger('submit');
                    }else{
                        Materialize.toast('Volume must be an even multiple of the total parts',5000);
                    }
                }
            </script>
        </form>
    </div>
</div>