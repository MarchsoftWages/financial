<?php
namespace app\Models;

use DB;
class Admin extends Models
{
    public static function get_user()
    {
        $user = DB::table('user')->get();
        return !$user->isEmpty() ? $user : 0;
    }
}