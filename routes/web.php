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

Route::get('files/show', 'FileController@show');
Route::get('files/download/{link}', 'FileController@download');

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'AdminController@login')->name('admin.login')->middleware('guest');
    Route::post('login/validate', 'Auth\LoginController@login')->name('admin.login.validate')->middleware('guest');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout')->middleware('guest');

    Route::get('file/upload', 'AdminController@index')->middleware('auth');
    Route::post('file/upload', 'Admin\FileController@create')->middleware('auth');
    Route::post('file/update', 'Admin\FileController@update')->middleware('auth');
    Route::get('file/delete/{id}', 'Admin\FileController@delete')->middleware('auth');
    Route::get('file/show', 'Admin\FileController@show')->middleware('auth');
    
    Route::get('/', 'AdminController@index')->middleware('auth');
});

Route::get('/', 'TaskController@index');