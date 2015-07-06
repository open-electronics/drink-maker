<?php namespace App\Http\Controllers;
use App\Drink;
use App\flasher;
use App\Ingredient;
use App\Order;
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
            Ingredient::find($ids[$i])
                ->update(['stock'=>$stocks[$i],'position'=>$positions[$i]]);
        }
        flasher::success('Ingredients updated correctly');
        return redirect('admin#ingredients');
    }


    /**
     * Adds an ingredient with a specified name and stock quantity
     * @param Request $r
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function add(Request $r){
        if(!($r->has('stock')&& $r->has('name'))){
            flasher::error('Fill in both fields');
            return redirect('admin#ingredients');
        }
        Order::create(['name'=>$r->input('name'),'stock'=>$r->input('stock'),'position'=>'-1');
        flasher::success('Ingredient added correctly');
        return redirect('admin#ingredients');
    }

    /**
     * Delete an ingredient
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function delete($id){
        Ingredient::find($id)->update('position','-2');
        flasher::success('Ingredient deleted correctly');
        return redirect('admin#ingredients');
    }
}