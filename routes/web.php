<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/auth', 'Auth\TokenRegisterController@create');
Route::post('/task/send', 'TaskController@create');
Route::post('/task/update', 'TaskController@update');
Route::get('/task/show', 
    [
        'middleware' => ['token'],
        'uses'=>'TaskController@show'
    ]
);
Route::get('/admin/upload', 'AdminController@index');
Route::get('/{any}', 'TaskController@index')->where('any', '.*');