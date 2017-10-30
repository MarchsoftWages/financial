<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{

    public static function addExcel($data)
    {

        DB::table('pay')->insert($data);
    }


}