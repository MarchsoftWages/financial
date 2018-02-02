<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pay;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PayController extends Controller
{
    /**
     * 参数
     * @var array
     * @var bool
     */
    public static $payPost = [];
    public static $addExcels = true;
    /**
     * 保存上传文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function uploadExcel(Request $request){
        $fileInfo = $this->getFiles($request->file('file'));
        $cpyId = $request->cpyId;
        $flag=($request->updateType==1?uniqid():$request->flag);
        $step = 1000;
        if(!($fileInfo['isValid']&&$fileInfo['isExcel'] &&Storage::disk('uploads')
                ->put($fileInfo['fileRealName'].'.'.$fileInfo['filExtension'],file_get_contents($fileInfo['fileRealpath']))))
            //判断文件是否上传成功
            return responseToJson(1,"failed","文件上传失败");
        $result = $this->readExcel($fileInfo['fileRealName'],$fileInfo['filExtension'],$step,$request->updateType,$cpyId,$flag);
        $logArr = $this->getLogArr([$fileInfo['fileName'],$fileInfo['fileRealName']],$flag,$cpyId);
        if ($result&&($request->updateType==1)&&Pay::addLogs($logArr)){
            $this->postInfo(PayController::$payPost);
            return responseToJson(0,"success","上传成功");
        }
        if ($result&&Pay::updatedLogs($logArr)){
            $code = 0;
            $msg = "success";
            $paras = "重新录入成功";
        }else{
            $code = 2;
            $msg = "failed";
            $paras = "录入数据相同";
        }
        return responseToJson($code,$msg,$paras);
	}

    /**
     * 读取表格
     * @param $fileRealName  文件名
     * @param $fileType      文件类型
     * @param $step          分块长度
     * @param $type          插入更新
     * @param $cpyId         id
     * @param $flag          标记
     * @return bool          返回bool
     */
    public function readExcel($fileRealName,$fileType,$step,$type,$cpyId,$flag){
        $filePath = storage_path().'/app/uploads/'.iconv('UTF-8','GBK',$fileRealName).".".$fileType;
        Excel::selectSheetsByIndex(0)->filter('chunk')->load($filePath)
            ->chunk($step , function($results) use($type,$cpyId,$flag){
                $array = $results->toArray();
                end($array)["工号"] == null?array_pop($array):$array;
                $pArr = PayController::getPayArray($array,$cpyId,$flag);
                $otherArr = PayController::getPayOtherArr($array,$flag);
                // if (($type==1?Pay::addExcel($pArr[0], $otherArr):Pay::updateExcel($pArr[0],$otherArr))) {
                //     PayController::$addExcels = false;
                //     return;
                // }
                // PayController::$payPost[] = $pArr[1];
            });
        exit();
        return PayController::$addExcels;
    }

    /**
     * 删除日志
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
     * 组合Pay表数组
     * @param $datas
     * @param $cpyId
     * @return array
     */
    public static function getPayArray($datas,$cpyId,$flag){
        $payArr=[];
        $postArr=[];
        foreach ($datas as $data){
            $year = substr($data['工资年月'],0,4);
            $month = substr($data['工资年月'],4,2);
            $timeStamp = strtotime($year."-".$month."-01 00:00:00");
            $pay=[
                'job_num'=>$data['工号'],
                'pay_year'=> $year,
                'pay_month'=>$month,
                'wages_date'=>$timeStamp,
                'name'=>$data['姓名'],
                "spell"=>$data['拼音码']
            ];
            $post=[
                'code'=>$data['工号'],
                'name'=>$data['姓名'],
                'year'=>$year,
                'month'=>$month,
                'url'=>getenv('TEMPLATE_URL').$data['工号'].'/'.$year

            ];
            $jsonArr = [];
            $index = 0;
            foreach($data as $key => $value){
                if ($index>110)
                    $jsonArr[$key] = $value;
                $index++;
            }
            $pay['wages'] = json_encode($jsonArr);
            $pay['type'] = $cpyId;
            $pay['flag'] = $flag;
            $payArr[] = $pay;
            $postArr[] = $post;
        }
        return [$payArr,$postArr];
    }

    /**
     * 组合PayOther表数组
     * @param $datas
     * @param $flag
     * @return array
     */
    public static function getPayOtherArr($datas,$flag) {
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
    public static function getLogArr($fileName,$flag,$cypId){
        $operationLog = [];
        $operation = [
            'operater' => Session::get('checkLogin'),
            'file_name' => $fileName[0],
            'real_name' => $fileName[1],
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
        $fileRealName = date('Y-m-d-h-m-s').'-'.uniqid();
        $realPath = $file->getRealPath();   //临时文件的绝对路径
        return [
            'isValid'=>$isValid,
            'fileName'=>$fileName,
            'fileRealName'=>$fileRealName,
            'fileRealpath'=>$realPath,
            'isExcel'=>$isExcel,
            'filExtension'=>$ext
        ];
    }
    /**
     * 模板下载
     * @param Request $request
     */
    public function downloadFile(Request $request){
        $downloadFileName = $request->downloadType==0?'first.xlsx':'second.xlsx';
        $file = storage_path().'/app/public/'.$downloadFileName;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    /**
     * 模板推送
     * @param $data
     */
    public function postInfo($data){
        $dataArr = [];
        for ($i=0;$i<count($data);$i++){
            $dataArr = array_merge_recursive($dataArr,$data[$i]);
        }
//        $client = new Client();
//        $response = $client->request('POST', getenv('SEND_URL'), [
//            'form_params' => [
//                'detail_list' => json_encode($dataArr),
//            ]
//        ]);
    }
}

