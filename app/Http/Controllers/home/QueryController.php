<?php
namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Query;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class QueryController extends Controller
{

    /**
     * 获取全年的工资
     * @param Request $requestj
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_year(Request $request)
    {
        $job_num = $request->job_num;
        $year = $request->year;
        $result = Query::get_year_wages($job_num,$year);
        if($result){
            $data = array();
            foreach ($result as $key => $info) {
                if($info->type == 0){
                    $data[$info->pay_month]['first'] = $info;
                    $data[$info->pay_month]['showContent'] = false;
                    $data[$info->pay_month]['showContent1'] = false;
                    $data[$info->pay_month]['showContent2'] = false;
                }else{
                    $data[$info->pay_month]['second'] = $info;
                    $data[$info->pay_month]['showContent'] = false;
                }
            }
            //ksort($data);
            return responseToJson(0,'success',$data);
        }else{
            return responseToJson(1,'error','没有查询结果');
        }
    }

    public function test()
    {
        /*$data = array();
        $data1 = [
            'code'=>'2015001',
            'name'=>'wangqihang',
            'year'=>'2017',
            'month'=>'11',
            'url'=>'http://cgz.marchsoft.cn/wx#/detail/2015001/11/0'
        ];
        array_push($data,$data1);
        //$result = '[{"code":"2015001","name":"wangqihang","year":"2017","month":"11","url":"http:\/\/cgz.marchsoft.cn\/wx#\/detail\/2015001\/11\/0"}]';
        $client = new Client();
        $response = $client->request('POST', 'http://hist.marchsoft.cn/vendor/salary/send_notify', [
            'form_params' => [
                'detail_list' => json_encode($data),
            ]
        ]);*/
    }
}