<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
class Query extends Model
{
    /**
     * wx端查询当月的工资
     * @param $job_num  工号
     * @param $month    月份
     * @return int
     */
    public static function get_current_wages($job_num,$month)
    {
        $year = date('Y',time());
        $result = DB::table('pay')->where(['job_num'=>$job_num,'pay_year'=>$year,'pay_month'=>$month,'status'=>0])->first();
        return $result ? $result : 0;
    }

    /**
     * 查询最近三个月的工资
     * @param $job_num 工号
     * @param $month    近三个月
     * @return int
     */
    public static function get_three($job_num,$month)
    {
        $year = date('Y',time());
        $result = DB::table('pay')->where(['job_num'=>$job_num,'pay_year'=>$year,'status'=>0])->whereIn('pay_month',$month)->get();
        return !$result->isEmpty() ? $result : 0;
    }

    /**
     * 获取全年的工资列表
     * @param $job_num  工号
     * @return int
     */
    public static function get_year_wages($job_num)
    {
        $year = date('Y',time());
        $result = DB::table('pay')->where(['job_num'=>$job_num,'pay_year'=>$year,'status'=>0])->get();
        return !$result->isEmpty() ? $result : 0;
    }

    /**
     * 自定义查询工资列表
     * @param $job_num  工号
     * @param $start    开始时间
     * @param $end      结束时间
     * @return int
     */
    public static function get_query($job_num,$start,$end)
    {
        $result = DB::table('pay')
            ->where(['job_num'=>$job_num,'status'=>0])
            ->where('wages_date','>=',$start)
            ->where('wages_date','<=',$end)
            ->get();
        return !$result->isEmpty() ? $result : 0;
    }
}