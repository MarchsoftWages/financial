<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public static function get_user()
    {
        $user = DB::table('user')->get();
        return !$user->isEmpty() ? $user : 0;
    }
}