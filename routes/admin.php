<?php

Route::post('login','admin\LoginController@get_vaild');
Route::group(['middleware'=>['login']],function(){
    Route::post("modify_password", 'admin\LoginController@modify');
    Route::get('/', function () {
        return view('index');
    });
    Route::get('/getname', 'admin\LoginController@getPersonInfo');
    Route::get('/getlogs', 'admin\PayController@selectLogs');
    Route::get('/getlog', 'admin\PayController@selectLog');
    Route::get('/searFbinfo', 'admin\FBController@selConfb');
    Route::get('/getimg/{imgName}','admin\FBController@getImg');
    Route::post("/update_type", 'admin\FBController@updateType');
    Route::post("/delete_Fb", 'admin\FBController@deleteFb');
    Route::get('/download', 'admin\PayController@downloadFile');
    Route::post("/admin/upload", 'admin\PayController@uploadExcel');
    Route::post("/admin/delete", 'admin\PayController@deleteExcel');
    Route::post("/loginout",'admin\LoginController@loginout');
});
