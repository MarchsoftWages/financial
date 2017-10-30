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

Route::get('/', function () {
    return view('login');
});
Route::get('/wx', function () {
    return view('wx_index');
});
Route::get('captcha/{tmp}','admin\codeController@get_captcha');
<<<<<<< HEAD
// Route::post('login','admin\LoginController@get_vaild');

=======
Route::get('login','admin\codeController@get_vaild');
>>>>>>> 73b58d27de450a8e4837d2dfc213323eca44efc9
include('admin.php');
include('wx.php');
