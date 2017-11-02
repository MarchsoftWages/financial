<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PayController extends Controller
{
    /**
     * 设定中国时间
     * PayController constructor.
     */
    public function __construct(){
        date_default_timezone_set('PRC');
    }

    /**
     * 保存上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function saveExcel(Request $request){

        $file = $request->file('file');
        $cpyId = $request->cpyId;
        $flag = time();

        if($file->isValid()){	//判断文件是否上传成功
            $originalName = $file->getClientOriginalName(); //源文件名
            $ext = $file->getClientOriginalExtension();    //文件拓展名
            $type = $file->getClientMimeType(); //文件类型
            $isExcel=starts_with($type, 'application/');

            if (!$isExcel)
                return responseToJson(1,"failed","文件类型错误");

            $fileName=explode(".",$originalName)[0];  //文件名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            return responseToJson(0,"success",$flag);
            $bool = Storage::disk('uploads')->put($originalName,file_get_contents($realPath));
            if ($bool){
                $data = $this->getPayArray($this->readExcel($fileName,$ext),$cpyId,$flag);
                if (!$cpyId){
                    $result = Pay::addExcel($data);
                }
            }
        }
	}
    /**
     * 读取表格
     * @param $fileName       文件名
     * @param $fileType       文件拓展名
     * @return array          excel数组
     */
    public function readExcel($fileName,$fileType){
        $filePath = 'storage/app/uploads/'.iconv('UTF-8', 'GBK', $fileName).".".$fileType;
        $data=[];
        Excel::selectSheets('Sheet1')->load($filePath, function($reader) use (&$data){
            $data=$reader->toArray();
        });
        return $data;
    }

    /**
     * 组合Pay表数组
     * @param $datas
     * @param $cpyId
     * @return array
     */
    public function getPayArray($datas,$cpyId,$flag){
        $payArr=[];
        foreach ($datas as $data){
            $year = substr($data['工资年月'],0,4);
            $month = substr($data['工资年月'],4,2);
            $timeStamp = strtotime($year."-".$month."-00 00:00:00");
            $pay=[
                'job_num'=>$data['工号'],
                'pay_year'=> $year,
                'pay_month'=>$month,
                'wages_date'=>$timeStamp,
                'name'=>$data['姓名'],
                "spell"=>$data['拼音码']
            ];
            $jsonArr = [];
            $index = 0;
            foreach($data as $key => $value){
                if ($index>110)
                    $jsonArr[$key] = $value;
                $index++;
            }
            $pay[$cpyId==0?'first_pay':'second_pay'] = json_encode($jsonArr);
            $pay['type'] = $cpyId;
            $pay['flag'] = $flag;
            $payArr[] = $pay;
        }
        return $payArr;
    }

    public function getPayOtherArr($datas) {
        $payOtherArr = [];
        foreach ($datas as $data) {
            $payOther=[];
            $index = 0;
            foreach ($data as $key => $value) {
                $index++;
                if($index>1&&$index<3)
                    $payOther=[$key] = $value;
                if($index>4&&$index<6)
                    $payOther=[$key] = $value;
                if($index>11&&$index<14)
                    $payOther=[$key] = $value;
            }
        }
    }

    /**
     * 替换成键名数组
     * @param $v
     * @param $kname
     */
    public function foo(&$v, $kname) {
        $v = array_combine($kname, array_slice($v, 1, -1));
    }
}