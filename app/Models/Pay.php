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
            }else{
                throw new \Exception("失败");
            }
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return 1; //失败
        }

    }

    /**
     * 覆盖录入
     * @param $payArr
     * @param $payOtherArr
     * @param $logArr
     * @return int
     */
    public static function updateExcel($payArr,$payOtherArr,$logArr){
        DB::beginTransaction();
        try{
            if(
                self::updateBatch('pay','flag',$payArr)
                &&self::updateBatch('pay_other','flag',$payOtherArr)
                &&self::updateBatch('operation_log','mark',$logArr)
            ){
                DB::commit();
                return 0;    //成功
            }else{
                throw new \Exception("失败");
            }
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return 2; //数据相同
        }

    }

    public static function deleteExcel($flag){
        DB::beginTransaction();
        try {
            if (
                DB::table('operation_log')->where('mark', $flag)->update(['state' => 1])
                && DB::table('pay')->where('flag', $flag)->delete()
                && DB::table('pay_other')->where('flag', $flag)->delete()
            ) {
                DB::commit();
                return 0;    //成功
            } else {
                throw new \Exception("失败");
            }
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return 3; //删除失败
        }
    }

    /**
     * 获取日志
     * @return mixed
     */
    public static function getLogs(){
        return DB::table('operation_log')->orderBy('upload_time','desc')
            ->select('file_name','operater','upload_time','mark','type','state')
            ->paginate(5);
    }

    /**
     * 批量更新
     * @param $tableName   //表名
     * @param null $condition  //更新依据字段
     * @param array $multipleData  //更新数组
     * @return bool     //返回结果
     */
    public static function updateBatch($tableName,$condition = null,$multipleData = []){
        try {
            if (empty($multipleData)) {
                throw new \Exception("数据不能为空");
            }
            $firstRow  = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默认以id为条件更新，如果没有ID则以第一个字段为条件
            $referenceColumn = $condition==null ? 'id' : $condition;
            unset($updateColumn[0]);
            // 拼接sql语句
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets      = [];
            $bindings  = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn   = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings  = array_merge($bindings, $whereIn);
            $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            // 传入预处理sql语句和对应绑定数据
            return DB::update($updateSql, $bindings);
        } catch (\Exception $e) {
            return false;
        }
    }

}