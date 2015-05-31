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




class DrinkController extends Controller {

    public function add(Request $r){

    }


    /**
     * Deletes a drink with a specified id if it doesn't have any orders
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function delete($id){
        if(DB::table('orders')->where('drink_id',$id)->count()!=0){
            flasher::error('There are some orders relative to this drink, delete them first');
            return redirect('admin');
        }
        DB::table('drinks')->where('id',$id)->delete();
        flasher::success('Drink deleted correctly');
        return redirect('admin');
    }
}