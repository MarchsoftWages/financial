<?php
/**
 * Created by PhpStorm.
 * User: 海王星的飘雪
 * Date: 2017/12/12
 * Time: 14:52
 */

namespace App\Http\Controllers\admin;


use Illuminate\Http\Request;

class FBController
{
    public function feedBack(Request $request){
        $feedArr = $request->all();
        if ($feedArr['img_path'] != null){
            $nameArr = [];
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
            $feedArr['img_path'] = json_encode($nameArr);
        }
        $feedArr['phone_model'] = json_encode($feedArr['phone_model']);

        return responseToJson('0',"success",$feedArr);
    }
}