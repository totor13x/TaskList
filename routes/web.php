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
Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'AdminController@login');
    Route::post('login/validate', 'Auth\LoginController@login')->name('admin.login.validate');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('upload', 'AdminController@index');
    Route::post('upload', 'Admin\FileController@create');
    Route::get('/', 'AdminController@index');
});

Route::get('/{any}', 'TaskController@index')->where('any', '.*');