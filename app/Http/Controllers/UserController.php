<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 26/05/15
 * Time: 17:49
 */
use App\Drink;
use App\Http\Controllers\Controller;
use App\flasher;
use App\Ingredient;
use App\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class UserController extends Controller {
    /**
     * Return the admin index with a list of the drinks, orders and the ingredients with the relative info
     * @return $this
     */
    public function adminIndex(){
        if(Session::get('logged')!=true){
            flasher::warning('Login to access the control panel');
            return redirect('login');
        }
        $drinks=Drink::all();
        $ingredients=Ingredient::all();
        $orders=Order::all();
        $status=false;
        foreach($orders as $o){
            if($o['status']==2) $status=true;
            break;
        }
        return view('admin')->with('ingredients',$ingredients)
            ->with('orders',$orders)->with('status',$status)->with('drinks',$drinks);
    }

    /**
     * Return a view to the user with all the cocktails, their ingredients and their availability
     * @return $this
     */
    public function userIndex(){
        return view('user')->with("drinks",Drink::all());
    }
}