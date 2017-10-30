<?php

Route::post("/current/get", 'home\QueryController@get_current');
Route::post("/three/get", 'home\QueryController@get_three');
Route::post("/detail/get", 'home\QueryController@get_detail');
Route::post("/year/get", 'home\QueryController@get_year');
Route::post("/query/get", 'home\QueryController@get_query');
Route::get("/test", 'home\QueryController@test');
