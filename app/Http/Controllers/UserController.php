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
use App\Settings;
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
        $ingredients=Ingredient::where('position','<>','-2')->get();
        $orders=Order::whereIn('status',[0,1,2,5,6])->get();
        $status=false;
        foreach($orders as $o){
            if($o['status']==1){
                $status=true;
                break;
            }
        }
        return view('admin')->with('ingredients',$ingredients)->with('settings',Settings::all())
            ->with('orders',$orders)->with('status',$status)->with('drinks',$drinks)->with('wifi',Settings::wifi());
    }

    /**
     * Return a view to the user with all the cocktails, their ingredients and their availability
     * @return $this
     */
    public function userIndex(){
        $drinks=[];
        foreach(Drink::all() as $drink){
            if($drink->getAvailable())array_push($drinks,$drink);
        }
        $max = Settings::volume();
        return view('user')->with("drinks",$drinks)->with('max',$max);
    }
}