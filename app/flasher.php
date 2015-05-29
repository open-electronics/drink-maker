<?php

namespace App;
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 28/05/15
 * Time: 19:05
 */

class flasher {
    public static function __callStatic($name, $arguments)
    {
        $icon=[
            'error'=>'alert-error',
            'warning'=>'alert-warning',
            'info'=>'action-info',
            'success'=>'action-done'
        ];

        Session::flash('flash_notification.message',$arguments[0]);
        Session::flash('flash_notification.level',$name);
        Session::flash('flash_notification.icon',$icon[$name]);
    }
}