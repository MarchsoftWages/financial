<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{

    /**
     * 上传工资记录
     * @param $payArr
     * @param $payOtherArr
     * @param $logArr
     * @return int
     */
    public static function addExcel($payArr,$payOtherArr,$logArr)
    {
        DB::beginTransaction();
        try{
            $flag = false;
            for ($i=0;$i<count($payArr);$i++){
                if(!DB::table('pay')->insert($payArr[$i])||!DB::table('pay_other')->insert($payOtherArr[$i])){
                    $flag = true;
                    throw new \Exception("失败");
                    break;
                }
            }
            if (!$flag) {
                if (DB::table('operation_log')->insert($logArr)) {
                    DB::commit();
                    return 0;    //成功
                }
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
            $flag = false;
            for ($i=0;$i<count($payArr);$i++){
                if(!self::updateBatch('pay','flag',$payArr[$i])
                    ||!self::updateBatch('pay_other','flag',$payOtherArr[$i])
                ){
                    $flag = true;
                    throw new \Exception("失败");
                    break;
                }
            }
            if (!$flag){
                if(self::updateBatch('operation_log','mark',$logArr)){
                    DB::commit();
                    return 0;    //成功
                }
            }
        }catch (\Exception $e){
            DB::rollback();//事务回滚
            return 2; //数据相同
        }

    }

    /**
     * 删除日志
     * @param $flag
     * @return int
     */
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
    public static function getLogs($type,$size){
        $datas = DB::table('operation_log')
            ->select('file_name','operater','upload_time','mark','type','state')
            ->orderBy('upload_time','desc');
        if ($type==1)
            return $datas->where('type',0)->paginate($size);
        if ($type==2)
            return $datas->where('type',1)->paginate($size);
        return $datas->paginate($size);
    }

    /**
     * 条件查找日志
     * @param $type
     * @param $input
     * @param $value
     * @return bool
     */
    public static function getLog($type,$input,$value){
        $data = DB::table('operation_log')
            ->select('file_name','operater','upload_time','mark','type','state')
            ->orderBy('upload_time','desc');
        if ($input==""&&$value=="")
            return false;
        if ($input!=""&&$value==""){
            if ($type==0)
                return $data->where('file_name','like','%'.$input.'%')->get();
            else{
                $type=$type-1;
                return $data->where('type',$type)->where('file_name','like','%'.$input.'%')->get();
            }
        }
        if ($input==""&&$value!=""){
            if ($type==0)
                return $data->whereBetween('upload_time',$value)->get();
            else{
                $type=$type-1;
                return $data->where('type',$type)->whereBetween('upload_time',$value)->get();
            }
        }
        if ($input!=""&&$value!=""){
            if ($type==0)
                return $data->where('file_name','like','%'.$input.'%')->whereBetween('upload_time',$value)->get();
            else{
                $type=$type-1;
                return $data->where('type',$type)->where('file_name','like','%'.$input.'%')->whereBetween('upload_time',$value)->get();
            }
        }
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

    /**
     * 返回数据条数
     * @return int
     */
    public static function countData(){
        return DB::table('pay')->count();
    }

}