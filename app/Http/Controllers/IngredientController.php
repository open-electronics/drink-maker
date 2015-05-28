<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 28/05/15
 * Time: 15:09
 */




class IngredientController extends Controller {
    public function update(Request $r){
        $ids=$r->input('id');
        $stocks=$r->input('stock');
        $positions=$r->input('position');
        for($i=0;$i<count($ids);$i++){
            DB::table('ingredients')->where('id',$ids[$i])
                ->update(['stock'=>$stocks[$i],'position'=>$positions[$i]]);
        }
        return redirect('admin');
    }
}