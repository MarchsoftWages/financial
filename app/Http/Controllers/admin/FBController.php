<?php
/**
 * Created by PhpStorm.
 * User: 海王星的飘雪
 * Date: 2017/12/12
 * Time: 14:52
 */

namespace App\Http\Controllers\admin;


use App\Models\FeedBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FBController
{
    public function feedBack(Request $request){
        $feedArr = $request->all();
        $nameArr = [];
        if ($feedArr['img_path'] != null){
            foreach ($feedArr['img_path'] as $img){
                preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result);
                $type = $result[2];
                $name = date('Y-m-d-h-m-s').'-'.uniqid().".".$type;
                $nameArr[] = $name;
                if(!Storage::disk('images')->put($name,base64_decode(str_replace($result[1], '', $img)))){
                    foreach ($nameArr as $value)
                        Storage::disk('images')->delete($value);
                    return responseToJson('1',"failed","图片上传失败");
                }
            }
        }
        $feedArr['img_path'] = json_encode($nameArr);
        if(FeedBack::saveFB($feedArr))
            return responseToJson('0',"success","问题提交成功");
        else
            return responseToJson('1',"failed","问题提交失败");;

    }

    public function selectFb(Request $request){
        $size = $request->size;
        $dataFb = FeedBack::getFb($size);
        return $dataFb?responseToJson(0,"success",$dataFb):responseToJson(1,"failed","无查询数据");
    }

    public function selConfb(Request $request){
        $size = $request->size;
        $input = $request->input;
        $value = $request->value;
        $setData = FeedBack::setConfb($size,$input,$value);
        return $setData?responseToJson(0,"success",$setData):responseToJson(1,"failed","无查询数据");

    }
}