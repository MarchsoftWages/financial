<?php
Route::post('login','admin\LoginController@get_vaild');
Route::group(['middleware'=>['login']],function(){
Route::post("/admin/upload", 'admin\PayController@saveExcel');

});


