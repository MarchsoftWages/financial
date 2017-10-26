<?php
namespace app\Models;

use DB;
class Admin
{
    public static function get_user()
    {
        $user = DB::table('user')->get();
        return !$user->isEmpty() ? $user : 0;
    }
}