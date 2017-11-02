<?php

Route::post('login','admin\LoginController@get_vaild');
Route::group(['middleware'=>['login']],function(){
    Route::get('/back_index', function () {
        return view('index');
    });
    Route::get('/getlogs', 'admin\PayController@selectLog');
    Route::post("/admin/upload", 'admin\PayController@saveExcel');
});


