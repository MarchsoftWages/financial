<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class PayController extends Controller
{
	public function saveExcel(Request $request){
        $file = $request->file('file');	//接文件

        //文件是否上传成功
        if($file->isValid()){	//判断文件是否上传成功
            $originalName = $file->getClientOriginalName(); //源文件名
            $ext = $file->getClientOriginalExtension();    //文件拓展名
            $type = $file->getClientMimeType(); //文件类型
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $bool = Storage::disk('app/public')->put($originalName,file_get_contents($realPath));   //传成功返回bool值
        }
	}
}