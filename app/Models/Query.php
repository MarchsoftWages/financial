<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
class Query extends Model
{
    /**
     * 获取全年的工资列表
     * @param $job_num  工号
     * @param $year 年份
     * @return int
     */
    public static function get_year_wages($job_num,$year)
    {
        $result = DB::table('pay')
            ->where(['job_num'=>$job_num,'pay_year'=>$year,'status'=>0])
            ->orderBy('wages_date','dsc')
            ->get();
        return !$result->isEmpty() ? $result : 0;
    }



    /**
     * wx端查询当月的工资
     * @param $job_num  工号
     * @param $month    月份
     * @return int
     * @internal param int $type 0:第一批，1：第二批
     */
    public static function get_current_wages($job_num,$year, $month,$type)
    {
        $result = DB::table('pay')->where(['job_num'=>$job_num,'pay_year'=>$year,'pay_month'=>$month,'status'=>0,'type'=>$type])->first();
        return $result ? $result : 0;
    }

    /**
     * 查询最近三个月的工资+
     * @param $job_num 工号
     * @param $month    近三个月
     * @return int
     */
    public static function get_three($job_num,$month,$type = 0)
    {
        $year = date('Y',time());
        $result = DB::table('pay')
            ->where(['job_num'=>$job_num,'pay_year'=>$year,'status'=>0,'type'=>$type])
            ->whereIn('pay_month',$month)
            ->orderBy('wages_date','desc')
            ->get();
        return !$result->isEmpty() ? $result : 0;
    }

    /**
     * 自定义查询工资列表
     * @param $job_num  工号
     * @param $start    开始时间
     * @param $end      结束时间
     * @return int
     */
    public static function get_query($job_num,$start,$end,$type = 0)
    {
        $result = DB::table('pay')
            ->where(['job_num'=>$job_num,'status'=>0,'type'=>$type])
            ->where('wages_date','>=',$start)
            ->where('wages_date','<=',$end)
            ->get();
        return !$result->isEmpty() ? $result : 0;
    }
}