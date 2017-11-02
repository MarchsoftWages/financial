<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function addExcel($data)
    {
        DB::beginTransaction();
        try{
            if(DB::table('pay')->insert($data)){
                DB::commit();
                return 0;
            }
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return 1;
        }

    }


}