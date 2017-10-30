<?php

Route::get('/back_index', function () {
    return view('index');
});
Route::post("/admin/upload", 'admin\PayController@saveExcel');
