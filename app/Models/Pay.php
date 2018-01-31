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
     * @return int
     */
    public static function addExcel($payArr,$payOtherArr)
    {
        DB::beginTransaction();
        try{
            if(DB::table('pay')->insert($payArr)&&DB::table('pay_other')->insert($payOtherArr)){
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
     * 写入日志
     * @param $logArr
     * @return mixed
     */
    public static function addLogs($logArr){
        return DB::table('operation_log')->insert($logArr);
    }

    /**
     * 覆盖录入
     * @param $payArr
     * @param $payOtherArr
     * @return int
     */
    public static function updateExcel($payArr,$payOtherArr){
        DB::beginTransaction();
        try{
            if(self::updateBatch('pay','flag',$payArr)
                &&self::updateBatch('pay_other','flag',$payOtherArr)
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

    /**
     * 更新日志
     * @param $logArr
     * @return bool
     */
    public static function updatedLogs($logArr){
        return self::updateBatch('operation_log','mark',$logArr);
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
     * @param $condition  //更新依据字段
     * @param array $multipleData  //更新数组
     * @return bool     //返回结果
     */
    public static function updateBatch($tableName = "", $condition = "" ,$multipleData = []){
        if( $tableName && !empty($multipleData) && $condition) {
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0];
            unset($updateColumn[0]);
            array_pop($updateColumn);
            $whereIn = "";
            $whereAnd = "";
            $q = "UPDATE ".$tableName." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  $uColumn." = CASE ";

                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ".$uColumn." END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
                $whereAnd .= "'".$data[$condition]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".rtrim($whereIn, ', ').") AND ".$condition." IN(".rtrim($whereAnd, ', ').")"  ;
            return DB::update(DB::raw($q));

        } else {
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