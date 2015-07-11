<div class="row">
    <div class="col s5 offset-s1" align="center">
        <h4>Ingredients:</h4>
        Use numbers from 0 to 9 for positions, and -1 if the bottle is not on the machine
        <form method="post" id="quantities" action="{{url('ingredients')}}">
            <input name="_method" type="hidden" value="patch"/>
            <table>
                <thead>
                <tr>
                    <th data-field="name">Name</th>
                    <th data-field="quantity">Stock</th>
                    <th data-field="position">Position</th>
                    <th data-field="delete">Delete</th>
                </tr>
                </thead>
                <tbody>
                @forelse($ingredients as $i)
                    <tr>
                        <td>{{$i["name"]}}<input value="{{$i["id"]}}" name="id[]" type="hidden"></td>
                        <td><input value="{{$i["stock"]}}" name="stock[]" type="number" class="validate"></td>
                        <td><input value="{{$i["position"]}}" name="position[]" min="-1" max="9" type="number" class="validate"></td>
                        <td align="center">
                            <a href="#{{url("ingredients/".$i->id)}}" class="delete-ingredient">
                                <i class="mdi-content-clear"></i>
                            </a>
                        </td>
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
                    <input id="name" name="name" type="text" class="validate" maxlength="50">
                    <label for="name">Name</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="stock" name="stock" type="number" class="validate" value="0" min="0">
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