<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 26/05/15
 * Time: 17:49
 */
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class UserController extends Controller {
    /**
     * Return the admin index with a list of the orders and the ingredients with the relative info
     * @return $this
     */
    public function adminIndex(){
        $ingredients=DB::table("ingredients")->select("ingredient","stock","position")->get();
        $orders=DB::table("orders")->join("drinks","drinks.id","=","orders.drink_id")
            ->select("drinks.name","orders.status","orders.id")->whereIn("orders.status",[0,1,2])->get();
        $status=false;
        foreach($orders as $o){
            if($o['status']==2) $status=true;
        }
        return view('admin')->with('ingredients',$ingredients)->with('orders',$orders)->with('status',$status);
    }

    /**
     * Return a view to the user with all the cocktails, their ingredients and their availability
     * @return $this
     */
    public function userIndex(){
        $result=DB::table("drinks")
            ->join("drinks_ingredients","drinks.id","=","drinks_ingredients.drink_id")
            ->join("ingredients","ingredients.id","=","drinks_ingredients.ingredient_id")
            ->select("drinks.name","ingredients.ingredient","ingredients.stock","drinks_ingredients.needed")
            ->where("ingredients.position","<>","null")
            ->get();
        $drinks=[];
        foreach($result as  $r){
            $drinks[$r["name"]]["ingredients"][$r["ingredient"]]["needed"]=$r["needed"];
            $drinks[$r["name"]]["ingredients"][$r["ingredient"]]["stock"]=$r["stock"];
        }
        $avab=[];
        foreach($drinks as $name=>$drink){
            $score=0;
            foreach($drink["ingredients"] as $ingredients){
                if($ingredients["needed"]<=$ingredients["stock"]) $score++;
            }
            $avab[$name]=$drink;
            $avab[$name]["available"]=($score==count($drink["ingredients"]));
        }
        return view('user')->with("drinks",$avab);
    }
}