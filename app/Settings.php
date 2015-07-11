<?php
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 11/07/15
 * Time: 09:04
 */

namespace App;


use Illuminate\Support\Facades\DB;

class Settings {
    public static function __callStatic($name, $arguments)
    {
        switch($name){
            case 'all':
                $settings=[];
                $settings['username']=Settings::username();
                $settings['volume']=Settings::volume();
                $settings['start_method'] = Settings::start_method();
                $settings['initial_status'] = Settings::initial_status();
                $settings['timeout_time'] = Settings::timeout_time();
                return $settings;
                break;
            case 'multiple':
                return DB::table('settings')->where('id', '1')->select($arguments)->first();
                break;
            case 'exists':
                    return !is_null(DB::table('settings')->where('id', '1')->select('volume')->first()["volume"]);
                break;
            default:
                if($arguments==[]) {
                    return DB::table('settings')->where('id', '1')->select($name)->first()[$name];
                }else{
                    DB::table('settings')->where('id', '1')->update([$name => $arguments[0]]);
                }
                break;
        }
    }

}