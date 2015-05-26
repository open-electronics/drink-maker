<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


use Illuminate\Support\Facades\DB;

$app->get('/', function()  {

    return view('landing');
});
$app->get('admin',function(){
    $ingredients=DB::table("ingredients")->select("ingredient","stock")->get();
    $orders=DB::table("orders")->join("drinks","drinks.id","=","orders.drink_id")
        ->select("drinks.name","orders.status","orders.id")->where("orders.status",0)->get();
    return view('admin')->with('ingredients',$ingredients)->with('orders',$orders);
});
$app->get('orders/{name}',function($name){
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
});
$app->delete('orders/{id}',function($id){
    $ingredients=DB::table("orders")->where("orders.id",$id)
        ->join("drinks_ingredients","orders.drink_id","=","drinks_ingredients.drink_id")
        ->select("ingredient_id","needed")->get();
    foreach($ingredients as $i){
        DB::table("ingredients")->increment("stock",$i["needed"]);
    }
    DB::table("orders")->where("id",$id)->delete();
    return redirect("admin");
});
$app->get('user',function(){
    $result=DB::table("drinks")
        ->join("drinks_ingredients","drinks.id","=","drinks_ingredients.drink_id")
        ->join("ingredients","ingredients.id","=","drinks_ingredients.ingredient_id")
        ->select("drinks.name","ingredients.ingredient","ingredients.stock","drinks_ingredients.needed")
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
});
