<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gregwar\Captcha\CaptchaBuilder;
use Session;

class codeController extends Controller{
	/*
     获取验证码
	*/
	public function get_captcha($temp){
		$builder=new CaptchaBuilder();
		$builder ->build(100,32);
		$phrase = $builder->getPhrase();  // 获取验证码内容
		Session::flash('milkcaptcha',$phrase);
		ob_clean();
		return response($builder->output())->header('Content-type','image/jpeg');
	}
}
