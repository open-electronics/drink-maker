<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 26/05/15
 * Time: 17:49
 */
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller {
    /**
     * Get the drink with the specified name and add it to the orders queue, decrease stock of items
     * @param $name
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function add($name){
        $result=DB::table("drinks")
            ->join("drinks_ingredients","drinks.id","=","drinks_ingredients.drink_id")
            ->select("drinks.id","drinks_ingredients.needed","drinks_ingredients.ingredient_id")
            ->where("drinks.name","=",$name)->get();
        $id=$result[0]["id"];
        foreach($result as $r){
            DB::table("ingredients")->where("id","=",$r["ingredient_id"])->decrement("stock",$r["needed"]);
        }
        DB::table("orders")->insert(['drink_id'=>$id,'status'=>0]);
        return redirect("user");
    }

    /**
     * Find order with given id, put back in stock the various ingredients and delete the order
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function delete($id){
        $ingredients=DB::table("orders")->where("orders.id",$id)
            ->join("drinks_ingredients","orders.drink_id","=","drinks_ingredients.drink_id")
            ->select("ingredient_id","needed")->get();
        foreach($ingredients as $i){
            DB::table("ingredients")->increment("stock",$i["needed"]);
        }
        DB::table("orders")->where("id",$id)->delete();
        return redirect("admin");
    }
}