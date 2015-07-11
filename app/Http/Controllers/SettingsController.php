<?php namespace App\Http\Controllers;
use App\Settings;
use App\flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 28/05/15
 * Time: 15:09
 */

class SettingsController extends Controller {
    public function showPage(){
        if(Settings::exists()){
            flasher::warning('The machine has already been configured');
            return redirect('maker');
        }
        //TODO WIFI
        return view('configure')->with('settings',Settings::all());
    }
    public function configure(Request $r){
        if($r->has('new_psw')){
            if(Hash::check($r->input('old_psw'),Settings::password())){
                if($r->input('new_psw')==$r->input('re_new_psw')){
                    Settings::password(Hash::make($r->input('new_psw')));
                }else{
                    flasher::error('The new password doesn\'t match its replicate');
                    return redirect()->back();
                }
            }else{
                flasher::error('The old password is incorrect');
                return redirect()->back();
            }
        }
        Settings::username($r->input('username'));
        Settings::volume($r->input('volume'));
        Settings::start_method($r->input('start_method'));
        Settings::initial_status($r->input('initial_status'));
        Settings::timeout_time($r->input('timeout_time'));

        flasher::success('Your settings have been saved correctly');
        if(Session::has('logged')){
            return redirect('admin#settings');
        }else{
            return redirect('maker');
        }
    }
}