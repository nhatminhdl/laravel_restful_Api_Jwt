<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'middleware' =>'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
    
],function($router){
    
    Route::post('login','AuthController@login');
    Route::post('register','AuthController@register');
    Route::post('logout','AuthController@logout');
    Route::get('profile','AuthController@profile');
    Route::post('refresh','AuthController@refresh');
});


Route::group([
    'middleware' =>'api',
    'namespace' => 'App\Http\Controllers',
    // 'prefix' => 'todos'
    
],function($router){
    Route::resource('todos','TodoController');

});