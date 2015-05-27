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
     * Sets a drink in approved mode
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function approve($id){
        if(DB::table('orders')->where('status',1)->count()==0){
            DB::table('orders')->where('id',$id)->update(['status'=>1]);
        }
        return redirect("admin");
    }

    public function waiting(){
        $result=DB::table('orders')->where('status',1)
            ->join('drinks_ingredients','orders.drink_id','=','drinks_ingredients.drink_id')
            ->join('ingredients','drinks_ingredients.ingredient_id','=','ingredients.id')
            ->select('orders.drink_id','drinks_ingredients.needed','ingredients.position')->get();
        if(count($result)==0) return response("none");
        $resp["id"]=$result[0]["drink_id"];
        foreach($result as $r){
            $resp['ingredients'][$r['position']]=$r['needed'];
        }
        $result=DB::table('orders')->where('status',1)->update(['status'=>2]);
        return response()->json($resp);
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
            DB::table("ingredients")->where('id',$i["ingredient_id"])->increment("stock",$i["needed"]);
        }
        DB::table("orders")->where("id",$id)->update(['status'=>4]);
        return redirect("admin");
    }
}