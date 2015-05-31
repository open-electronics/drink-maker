<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\flasher;

/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 28/05/15
 * Time: 15:09
 */

class AuthController extends Controller {
    /**
     * Returns the admin page to logged users, the login page to non logged ones
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Laravel\Lumen\Http\Redirector
     */
    public function index(){
        if(Session::has('logged')){
            return redirect('admin');
        }
        return view('login');
    }

    /**
     * Logs in an user
     * @param Request $r
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function login(Request $r){
        if(!($r->has('name')&&$r->has('password'))){
            flasher::error('Compile both fields!');
            return redirect('login');
        }
        if($r->input('name')=='barobot' && $r->input('password')=='futura_barobot'){
            Session::put('logged',true);
            return redirect('admin');
        }
        flasher::error('Wrong data');
        return redirect('login');
    }
}