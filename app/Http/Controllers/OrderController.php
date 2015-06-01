<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 26/05/15
 * Time: 17:49
 */
use App\flasher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class OrderController extends Controller {
    /**
     * Get the drink with the specified name and add it to the orders queue, decrease stock of items
     * @param $req
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function add(Request $req){
        $id=$req->input('id');
        $name=$req->input('name');
        $result=DB::table("drinks")
            ->join("drinks_ingredients","drinks.id","=","drinks_ingredients.drink_id")
            ->join("ingredients","ingredients.id","=","drinks_ingredients.ingredient_id")
            ->select("drinks.id","drinks_ingredients.needed","ingredients.stock","drinks_ingredients.ingredient_id")
            ->where("drinks.id","=",$id)->get();
        $score=0;
        foreach($result as $r){//Count in stock ingredients
            if($r["needed"]<=$r["stock"])$score++;
        }
        if($score!=count($result)){//If we have all
            flasher::error('An error occured, please retry later');
            return redirect("user");
        }

        foreach($result as $r){//Decrement stock quantities
            DB::table("ingredients")->where("id","=",$r["ingredient_id"])->decrement("stock",$r["needed"]);
        }
        DB::table("orders")->insert(['drink_id'=>$id,'status'=>env('default_status',1),'name'=>$name]);//Insert order
        $number=DB::table('orders')->whereIn('status',[0,1,2])->count();
        flasher::success('We\'re taking care of your order!(number '.$number.')');
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
            flasher::success('Order set waiting to checkout');
        }else{
            flasher::error('An order has already been taken in charge');
        }
        return redirect("admin");
    }

    /**
     * Gets the cocktail queued for Python
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function waiting(){
        $id=DB::table('orders')->select('id')->where('status',1)->orderBy('id','asc')->take(1)->get();
        $id=$id[0]["id"];
        $result=DB::table('orders')->where('orders.id',$id)
            ->join('drinks_ingredients','orders.drink_id','=','drinks_ingredients.drink_id')
            ->join('ingredients','drinks_ingredients.ingredient_id','=','ingredients.id')
            ->select('orders.drink_id','drinks_ingredients.needed','ingredients.position')
            ->orderBy('ingredients.position','asc')->get();
        if(count($result)==0) return response("none");
        $resp["id"]=$result[0]["drink_id"];
        foreach($result as $r){
            $resp['ingredients'][$r['position']]=$r['needed'];
        }
        DB::table('orders')->where('id',$id)->update(['status'=>2]);
        return response()->json($resp);
    }

    /**
     * Sets an order status to complete
     *
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function completed(){
        DB::table('orders')->where('status','2')->update(['status'=>3]);
        return response('200');
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
        flasher::success('Order deleted correctly');
        return redirect("admin");
    }
}