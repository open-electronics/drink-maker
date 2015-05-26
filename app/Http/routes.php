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
$app->get('admin','UserController@adminIndex');
$app->get('user','UserController@userIndex');
$app->get('orders/{name}','OrderController@add');
$app->delete('orders/{id}','OrderController@delete');
