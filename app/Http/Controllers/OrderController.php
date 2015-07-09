<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 26/05/15
 * Time: 17:49
 */
use App\Drink;
use App\flasher;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class OrderController extends Controller {
    public function requeue($id){
        $order= Order::find($id);
        if(!$order || $order->status!=5){
            flasher::error('Error!This order cannot be reordered');
            return redirect()->back();
        }
        $order->status=1;
        $order->save();
        flasher::success('Your drink has been reordered!');
        return redirect('orders/'.$id);
    }
    public function pending(Request $r){
        if(!$r->ajax())return redirect()->back();
        $orders=Order::whereIn('status',[0,1,2,5])->get();
        return response(view('admin.orders')->with('orders',$orders)->render());
    }
    public function show($id){
        $order=Order::find($id);
        if(!$order){
            flasher::error('We\'re sorry, we can\t find this order');
            return redirect()->back();
        }
        return view('order')->with('order',$order);
    }
    public function async($id,Request $r){
        if(!$r->ajax())return redirect()->back();
        $order=Order::find($id);
        if(!$order){
            flasher::error('We\'re sorry, we can\t find this order');
            return redirect()->back();
        }
        return response(view('order.status')->with('order',$order)->render());
    }
    /**
     * Get the drink with the specified name and add it to the orders queue, decrease stock of items
     * @param $req
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function add(Request $req){
        $id=$req->input('id');
        $name=$req->input('name');
        $volume=$req->input('volume');
        $drink = Drink::find($id);

        if($drink->getAvailable() <$volume){//If we have all
            flasher::error('An error occured, please retry later');
            return redirect("order");
        }
        $id=$drink->orderDrink($name,$volume);
        flasher::success('We\'re taking care of your order!');
        return redirect('orders/'.$id);
    }

    /**
     * Sets a drink in approved mode
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function approve($id){
        if(DB::table('orders')->where('status',1)->count()==0){
            $order=Order::find($id)->update(['status'=>1]);
            flasher::success('Order set waiting to checkout');
        }else{
            flasher::error('An order has already been taken in charge');
        }
        return redirect("admin#orders");
    }

    /**
     * Gets the cocktail queued for Python
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function waiting(){
        $order= Order::where('status',1)->orderBy('id','asc')->firstOrFail();
        if(!$order) return response("none");
        //Create json
        $resp["id"]=$order->id;
        foreach($order->Drink()->Ingredients() as $i){
            $resp['ingredients'][$i->position]=$i->needed;
        }
        $order->status=2;
        $order->save();
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
        Order::find($id)->deleteOrder();
        flasher::success('Order deleted correctly');
        return redirect("admin#orders");
    }
}