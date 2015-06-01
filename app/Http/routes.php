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

$app->get('/', function()  {
    if(Session::get('logged')==true){
        return redirect('admin');
    }
    return redirect('user');
});
$app->get('admin','App\Http\Controllers\UserController@adminIndex');
$app->get('user','App\Http\Controllers\UserController@userIndex');

$app->get('login','App\Http\Controllers\AuthController@index');
$app->post('login','App\Http\Controllers\AuthController@login');

$app->get('orders/waiting','App\Http\Controllers\OrderController@waiting');
$app->get('orders/completed','App\Http\Controllers\OrderController@completed');
$app->post('orders','App\Http\Controllers\OrderController@add');
$app->patch('orders/{id}','App\Http\Controllers\OrderController@approve');
$app->delete('orders/{id}','App\Http\Controllers\OrderController@delete');

$app->patch('ingredients','App\Http\Controllers\IngredientController@update');
$app->post('ingredients','App\Http\Controllers\IngredientController@add');
$app->delete('ingredients/{id}','App\Http\Controllers\IngredientController@delete');

$app->post('drinks','App\Http\Controllers\DrinkController@add');
$app->delete('drinks/{id}','App\Http\Controllers\DrinkController@delete');