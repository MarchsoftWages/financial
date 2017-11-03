<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{

    public static function addExcel($payArr,$payOtherArr,$logArr)
    {
        DB::beginTransaction();
        try{
            if(
                DB::table('pay')->insert($payArr)
                &&DB::table('pay_other')->insert($payOtherArr)
                &&DB::table('operation_log')->insert($logArr)
            ){
                DB::commit();
                return 0;    //成功
            }
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return 1; //失败
        }

    }

    public static function getLogs(){
        return DB::table('operation_log')->select('file_name','operater','upload_time', 'type','state')->paginate(1);
    }
}