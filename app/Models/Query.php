<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
class Query extends Model
{
    /**
     * wx端查询当月的工资
     * @param $job_num
     * @param $month
     * @return int
     */
    public static function get_current_wages($job_num,$month)
    {
        $result = DB::table('pay')->where(['job_num'=>$job_num,'pay_month'=>$month,'status'=>0])->first();
        return $result ? $result : 0;
    }

    /**
     * 查询最近三个月的工资
     * @param $job_num
     */
    public static function get_three($job_num)
    {

    }
}