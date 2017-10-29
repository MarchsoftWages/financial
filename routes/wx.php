<?php

Route::post("/current/get", 'home\QueryController@get_current');
Route::post("//three/get", 'home\QueryController@get_three');
Route::get("/test", 'home\QueryController@test');
