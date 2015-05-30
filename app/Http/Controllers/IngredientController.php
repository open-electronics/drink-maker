<?php namespace App\Http\Controllers;
use App\flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 28/05/15
 * Time: 15:09
 */




class IngredientController extends Controller {

    /**
     * Updates ingredients positions and stock quantities
     * @param Request $r
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function update(Request $r){
        $ids=$r->input('id');
        $stocks=$r->input('stock');
        $positions=$r->input('position');
        for($i=0;$i<count($ids);$i++){
            DB::table('ingredients')->where('id',$ids[$i])
                ->update(['stock'=>$stocks[$i],'position'=>$positions[$i]]);
        }
        flasher::success('Ingredients updated correctly');
        return redirect('admin');
    }
}