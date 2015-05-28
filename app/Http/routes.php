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



$app->get('/', function()  {

    return view('landing');
});
$app->get('admin','App\Http\Controllers\UserController@adminIndex');
$app->get('user','App\Http\Controllers\UserController@userIndex');

$app->get('orders/waiting','App\Http\Controllers\OrderController@waiting');
$app->get('orders/completed/{id}','App\Http\Controllers\OrderController@completed');
$app->post('orders/{name}','App\Http\Controllers\OrderController@add');
$app->patch('orders/{id}','App\Http\Controllers\OrderController@approve');
$app->delete('orders/{id}','App\Http\Controllers\OrderController@delete');

$app->patch('ingredients','App\Http\Controllers\IngredientController@update');