<?php

Route::post("/admin/upload", 'admin\PayController@saveExcel');
Route::get("/home/test", 'home\QueryController@test');