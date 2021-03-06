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

Route::get('/login', function () {
    return view('login');
});
Route::get('/wx', function () {
    return view('wx_index');
});
Route::get('captcha/{tmp}','admin\codeController@get_captcha');
Route::get('test','admin\TestController@test');

Route::post('checkLogin','admin\LoginController@get_vaild');
include('admin.php');
include('wx.php');
