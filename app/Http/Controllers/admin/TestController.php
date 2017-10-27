<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

class TestController extends Controller
{
    public function test()
    {
        $result = Admin::get_user();
        return responseToJson(0,'success',$result);
    }
}