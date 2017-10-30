<?php
<<<<<<< HEAD
Route::post('login','admin\LoginController@get_vaild');
Route::group(['middleware'=>['login']],function(){
=======

Route::get('/back_index', function () {
    return view('index');
});
>>>>>>> 73b58d27de450a8e4837d2dfc213323eca44efc9
Route::post("/admin/upload", 'admin\PayController@saveExcel');

});


