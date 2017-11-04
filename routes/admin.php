<?php

Route::post('login','admin\LoginController@get_vaild');
Route::group(['middleware'=>['login']],function(){
	 Route::post("modify_password", 'admin\LoginController@modify');
    Route::get('/back_index', function () {
        return view('index');
    });
    Route::get('/getlogs', 'admin\PayController@selectLog');
    Route::post("/admin/upload", 'admin\PayController@saveExcel');
<<<<<<< HEAD
    Route::post("/loginout",'admin\LoginController@loginout');
=======
    Route::post("/admin/update", 'admin\PayController@updateExcel');
>>>>>>> 6d07cfd8f5c87add9e6f1180158d2c91e5156d59
});


