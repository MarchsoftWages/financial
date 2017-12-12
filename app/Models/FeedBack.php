<?php
/**
 * Created by PhpStorm.
 * User: 海王星的飘雪
 * Date: 2017/12/12
 * Time: 14:45
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class FeedBack
{
    public static function saveFB($fbArr){
        if(DB::table('feed_back')->insert($fbArr))
            return 1;                  //插入成功
        else
            return 0;                  //插入失败
    }
}