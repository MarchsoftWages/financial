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
    public static function saveFb($fbArr){
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
        $datas = DB::table('feed_back')
            ->select('id','job_num','name','phone','phone_model','qu_type','qu_detail','submit_time','img_path','is_look')
            ->orderBy('submit_time','desc');
        return $datas->paginate($size);
    }

    public static function setConfb($size,$input,$value,$timeData){
        $datas = DB::table('feed_back')
            ->select('id','job_num','name','phone','phone_model','qu_type','qu_detail','submit_time','img_path','is_look')
            ->orderBy('submit_time','desc');
        if ($value!=null)
            $datas = $datas->where('qu_type',$value);
        if ($input!=null)
            $datas = $datas->where('job_num','like','%'.$input.'%')->orWhere('name','like','%'.$input.'%')->orWhere('phone','like','%'.$input.'%');
        if ($timeData!=null)
            $datas = $datas->whereBetween('submit_time',$timeData);
        return $datas->paginate($size);
    }

    public static function updateFb($id,$updateArr){
        if(DB::table('feed_back')->where('id',$id)->update($updateArr))
            return 1;                  //更新成功
        else
            return 0;
    }

    public static function deleteFb($id){
        if(DB::table('feed_back')->where('id',$id)->delete())
            return 1;                  //删除成功
        else
            return 0;
    }
}