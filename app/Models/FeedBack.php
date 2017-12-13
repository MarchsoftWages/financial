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

    /**
     * 获取反馈意见
     * @return mixed
     */
    public static function getFb($size=5){
        $datas = DB::table('feed_back')->orderBy('id','desc')
            ->select('id','job_num','name','phone','phone_model','qu_type','qu_detail', 'img_path');
        return $datas->paginate($size);
    }

    public static function setConfb($size,$input,$value){
        $datas = DB::table('feed_back')->orderBy('id','desc')
            ->select('id','job_num','name','phone','phone_model','qu_type','qu_detail', 'img_path');
        if ($value!=null)
            $datas = $datas->where('qu_type',$value);
        if ($input!=null)
            $datas = $datas->where('job_num',$input)->orWhere('name',$input)->orWhere('phone',$input);
        return $datas->paginate($size);
    }
}