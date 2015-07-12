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


use Illuminate\Support\Facades\Session;



$app->get('configure','App\Http\Controllers\SettingsController@showPage');
$app->post('configure','App\Http\Controllers\SettingsController@configure');

$app->group(['middleware'=>'settings'],function() use($app){
    $app->get('/', function()  {
        return view('landing');
    });
    $app->get('/maker' , function() {
        $var = "sudo iwlist wlan0 scan 2>&1 | grep 'ESSID' | grep -oP '\"";
        $var=$var."\\K[^\"\047]+(?=[\"\047])'";
        dd(shell_exec($var));
        return view('landing');
    });

$app->get('admin', 'App\Http\Controllers\UserController@adminIndex');
$app->get('order', 'App\Http\Controllers\UserController@userIndex');

$app->get('login', 'App\Http\Controllers\AuthController@index');
$app->post('login', 'App\Http\Controllers\AuthController@login');

$app->get('orders', 'App\Http\Controllers\OrderController@personal');
$app->get('orders/pending', 'App\Http\Controllers\OrderController@pending');//Admin panel
$app->get('orders/waiting', 'App\Http\Controllers\OrderController@waiting');//python
$app->get('orders/completed', 'App\Http\Controllers\OrderController@completed');//python
$app->post('orders', 'App\Http\Controllers\OrderController@add');
$app->get('orders/{id}', 'App\Http\Controllers\OrderController@show');
$app->get('orders/{id}/async', 'App\Http\Controllers\OrderController@async');
$app->post('orders/{id}/requeue', 'App\Http\Controllers\OrderController@requeue');
$app->patch('orders/{id}', 'App\Http\Controllers\OrderController@approve');
$app->delete('orders/{id}', 'App\Http\Controllers\OrderController@delete');

$app->patch('ingredients', 'App\Http\Controllers\IngredientController@update');
$app->post('ingredients', 'App\Http\Controllers\IngredientController@add');
$app->delete('ingredients/{id}', 'App\Http\Controllers\IngredientController@delete');

$app->post('drinks', 'App\Http\Controllers\DrinkController@add');
$app->delete('drinks/{id}', 'App\Http\Controllers\DrinkController@delete');
});