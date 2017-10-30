<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PayController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function saveExcel(Request $request){
        $file = $request->file('file');	//接文件
        //文件是否上传成功
        if($file->isValid()){	//判断文件是否上传成功
            $originalName = $file->getClientOriginalName(); //源文件名
            $ext = $file->getClientOriginalExtension();    //文件拓展名
            $type = $file->getClientMimeType(); //文件类型
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $bool = Storage::disk('uploads')->put($originalName,file_get_contents($realPath));   //传成功返回bool值
            if ($bool){
                $strArr=explode(".",$originalName);
                $data = $this->readExcel($strArr[0],$strArr[1]);
                return responseToJson(0,"success",$data);
            }
        }

	}

    /**
     * @param $fileName
     * @param $fileType
     * @return array
     */
    public function readExcel($fileName,$fileType){
        $filePath = 'storage/app/uploads/'.iconv('UTF-8', 'GBK', $fileName).".".$fileType;
        $data=[];
        Excel::load($filePath, function($reader) use (&$data){
            $data=$reader->all()->toArray();
        });
        return $data;
    }
}