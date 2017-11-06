<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
	public function uploadExcel(Request $request){

        $fileInfo = $this->getFiles($request->file('file'));
        $cpyId = $request->cpyId;
        $flag=($request->updateType==1?uniqid():$request->flag);
        $code = 1;
        $msg = "failed";
        $paras = "上传失败";
        if($fileInfo['isValid']){	//判断文件是否上传成功
            if (!$fileInfo['isExcel'])
                return responseToJson(1,"failed","文件类型错误");
            $bool = Storage::disk('uploads')->put($fileInfo['originalName'],file_get_contents($fileInfo['fileRealpath']));
            if ($bool){
                $excel = $this->readExcel($fileInfo['fileName'],$fileInfo['filExtension']);
                $payArr = $this->getPayArray($excel,$cpyId,$flag);
                $payOtherArr = $this->getPayOtherArr($excel,$flag);
                $logArr = $this->getLogArr($fileInfo['fileName'],$flag,$cpyId);
                if($request->updateType==1){
                    $result = Pay::addExcel($payArr,$payOtherArr,$logArr);
                    if (!$result){
                        $code = 0;
                        $msg = "success";
                        $paras = "上传成功";
                    }
                }else{
                    $result = Pay::updateExcel($payArr,$payOtherArr,$logArr);
                    if (!$result){
                        $code = 0;
                        $msg = "success";
                        $paras = "重新录入成功";
                    }else{
                        $code = 2;
                        $paras = "录入数据相同";
                    }
                }

            }
        }
        return responseToJson($code,$msg,$paras);
	}

	public function deleteExcel(Request $request){
        $result = Pay::deleteExcel($request->flag);
        if (!$result){
            $code = 0;
            $msg = "success";
            $paras = "删除成功";
        }else{
            $code = 3;
            $msg = "failed";
            $paras = "删除失败";
        }
        return responseToJson($code,$msg,$paras);
    }

    /**
     * 显示日志列表
     * @return \Illuminate\Http\JsonResponse
     */
	public function selectLogs(Request $request){
        $size = $request->size?$request->size:5;
        $lists = Pay::getLogs($request->type,$size);
        return $lists?responseToJson(0,"success",$lists):responseToJson(0,"failed","没有查询结果");
    }

    public function selectLog(Request $request){
        if($request->value!=""){
            $dateArr = explode("-",$request->value);
            $request->value=[mktime(0,0,0,intval($dateArr[1]),0,intval($dateArr[0])),mktime(0,0,0,intval($dateArr[1])+1,0,intval($dateArr[0]))];
        }else
            $request->value;
        $list = Pay::getLog($request->type,$request->input,$request->value);
        return $list?responseToJson(0,"success",$list):responseToJson(0,"failed","没有查询结果");
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
        array_pop($data);   //删除总计
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

    /**
     * 组合PayOther表数组
     * @param $datas
     * @param $flag
     * @return array
     */
    public function getPayOtherArr($datas,$flag) {
        $payOtherArr = [];
        foreach ($datas as $data) {
            $payOther=[
                'job_id'=>$data['工号'],
                'sex'=>$data['性别']=='男'?0:1,
                'department_id'=>$data['部门号'],
                'department'=>$data['部门'],
                'team_number'=>$data['班组号'],
                'team'=>$data['班组'],
                'team_number2'=>$data['班组2号'],
                'team2'=>$data['班组2'],
                'rank'=>$data['职称'],
                'duties'=>$data['职务'],
                'school'=>$data['校区'],
                'duty_free'=>$data['免税'],
                'dues_free'=>$data['免会费'],
                'whether_payroll'=>$data['是否打印工资单'],
                'fund_account'=>$data['公积金帐号'],
                'id_number'=>$data['身份证号'],
                'credit_id1'=>$data['卡号1'],
                'credit_id1_type'=>$data['卡1类型'],
                'credit_id2'=>$data['卡号2'],
                'credit_id2_type'=>$data['卡2类型'],
                'credit_id3'=>$data['卡号3'],
                'credit_id3_type'=>$data['卡3类型'],
                'credit_id4'=>$data['卡号4'],
                'credit_id4_type'=>$data['卡4类型'],
                'additive_attribute1'=>$data['属性1'],
                'additive_attribute2'=>$data['属性2'],
                'additive_attribute3'=>$data['属性3'],
                'additive_attribute4'=>$data['属性4'],
                'additive_attribute5'=>$data['属性5'],
                'additive_attribute6'=>$data['属性6'],
                'additive_attribute7'=>$data['属性7'],
                'additive_attribute8'=>$data['属性8'],
                'flag'=>$flag
            ];
            $payOtherArr[] = $payOther;
        }
        return $payOtherArr;
    }

    /**
     * 组合operation_log表数组
     * @param $fileName
     * @param $flag
     * @param $cypId
     * @return array
     */
    public function getLogArr($fileName,$flag,$cypId){
        $operationLog = [];
        $operation = [
            'operater' => Session::get('checkLogin'),
            'file_name' => $fileName,
            'upload_time' => time(),
            'mark' => $flag,
            'type' => $cypId
        ];
        $operationLog [] = $operation;
        return $operationLog;
    }

    /**
     * 文件信息
     * @param $file
     * @return array
     */
    public function getFiles($file){
        $isValid = $file->isValid();
        $originalName= $file->getClientOriginalName(); //源文件名
        $ext = $file->getClientOriginalExtension();    //文件拓展名
        $isExcel=starts_with($file->getClientMimeType(), 'application/');  //文件类型正确错误
        $fileName=explode(".",$originalName)[0];  //文件名
        $realPath = $file->getRealPath();   //临时文件的绝对路径
        return [
            'isValid'=>$isValid,
            'originalName'=>$originalName,
            'fileName'=>$fileName,
            'fileRealpath'=>$realPath,
            'isExcel'=>$isExcel,
            'filExtension'=>$ext
        ];
    }
}