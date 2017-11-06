<?php
namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    /**
     * 查询当前月和上个月的工资，1：当前月，2：上月,
     * $type 0:第一批工资，1：第二批工资
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_current(Request $request)
    {
        $job_num = $request->job_num;
        $mobile = $request->mobile;
        $flag = $request->flag;
        $type = $request->type;
        if($flag == 1){
            $month = date('m',time());
        }else {
            $month = date('m',time()) - 1;
        }
        if(strlen($month) == 1){
            $month = '0'.$month;
        }
        $result = Query::get_current_wages($job_num, $month,$type);
        return $result ? responseToJson(0,'success',$result) : responseToJson(1,'error','没有查询结果');
    }

    /**
     * $data 近三个月份
     * 查询最近三个月的工资
     */
    public function get_three(Request $request)
    {
        $job_num = $request->job_num;
        $mobile = $request->mobile;
        $type = $request->type;
        $month = $month = date('m',time());
        $array = [$month,$month-1,$month-2];
        foreach ($array as $key => $value){
            if(strlen($value) == 1){
                $data[] = '0'.$value;
            }else {
                $data[] = $value.'';
            }
        }
        $result = Query::get_three($job_num,$data,$type);
        return $result ? responseToJson(0,'success',$result) : responseToJson(1,'error','没有查询结果');
    }

    /**
     * 查询某个月工资详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_detail(Request $request)
    {
        $job_num = $request->job_num;
        $month = $request->month;
        $type = $request->type;
        $result = Query::get_current_wages($job_num, $month,$type);
        return $result ? responseToJson(0,'success',$result) : responseToJson(1,'error','没有查询结果');
    }

    /**
     * 获取全年的工资
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_year(Request $request)
    {
        $job_num = $request->job_num;
        $month = $request->month;
        $type = $request->type;
        $result = Query::get_year_wages($job_num,$type);
        return $result ? responseToJson(0,'success',$result) : responseToJson(1,'error','没有查询结果');
    }

    /**
     * 自定义查询工资列表
     */
    public function get_query(Request $request)
    {
        $job_num = $request->job_num;
        $start = strtotime($request->start);
        $end = strtotime($request->end);
        $type = $request->type;
        $result = Query::get_query($job_num,$start,$end,$type);
        return $result ? responseToJson(0,'success',$result) : responseToJson(1,'error','没有查询结果');
    }
    public function test()
    {

    }
}