<?php

Route::post('login','admin\LoginController@get_vaild');
Route::group(['middleware'=>['login']],function(){
    Route::post("modify_password", 'admin\LoginController@modify');
    Route::get('/back_index', function () {
        return view('index');
    });
    Route::get('/getlogs', 'admin\PayController@selectLog');
    Route::post("/admin/upload", 'admin\PayController@saveExcel');

    Route::post("/loginout",'admin\LoginController@loginout');
    Route::post("/admin/update", 'admin\PayController@updateExcel');
    Route::post("/admin/upload", 'admin\PayController@uploadExcel');
    Route::get("/admin/delete", 'admin\PayController@deleteExcel');
});


