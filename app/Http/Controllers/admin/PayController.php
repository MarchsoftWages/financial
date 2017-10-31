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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function saveExcel(Request $request){

        $file = $request->file('file');

        if($file->isValid()){	//判断文件是否上传成功
            $originalName = $file->getClientOriginalName(); //源文件名
            $ext = $file->getClientOriginalExtension();    //文件拓展名
            $type = $file->getClientMimeType(); //文件类型
            $isExcel=starts_with($type, 'application/');

            if (!$isExcel)
                return responseToJson(1,"failed","文件类型错误");

            $fileName=explode(".",$originalName)[0];  //文件名
            $realPath = $file->getRealPath();   //临时文件的绝对路径

            $bool = Storage::disk('uploads')->put($originalName,file_get_contents($realPath));
            if ($bool){
                $data = $this->readExcel($fileName,$ext);
                $this->getArray($data);
                return responseToJson(0,"success",$data);
            }
        }

	}
    /**
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

    public function getArray($data){
        foreach ($data as $key=>$val){

        }
    }

    /**
     * @param $v
     * @param $k
     * @param $kname   替换成的键名数组
     */
    public function foo(&$v, $k, $kname) {
        $v = array_combine($kname, array_slice($v, 1, -1));
    }
}