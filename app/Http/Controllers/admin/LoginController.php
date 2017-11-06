<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Admin;
use Session;
use Input;


class LoginController extends Controller
{

  /**
  登录验证
  **/
    public function get_vaild(Request $request){
        $user= Admin::get_user();
        foreach ($user as $key ) {
           $name= $key->name;
           $password=$key->password;
        }
        if ($request->isMethod('post')) {    
            $this ->validate($request,[
                'user.name'=>'required|digits:6|regex:/^\d{6}$/',
                'user.password'=>'required|min:6|regex:/^[.\_0-9a-z]{6,}$/',
                'user.Captcha'=>'required|min:5| regex:/^[0-9a-zA_Z]{5}$/',
                ],[
                'required' => ':attribute输入错误',
                 'min' => ':attribute输入错误',
                 'user.name.regex' => ':attribute输入错误',
                 'user.name.digits'=>':attribute输入错误',
                ],[
                  'user.name'=>'用户名',
                  'user.password'=>'密码',
                  'user.Captcha'=>'验证码',
                ]);

            $data=$request->input('user');
            $user_name=$data['name'];
            $user_password=$data['password'];
            $user_captcha=$data['Captcha'];
        }

        if(($name==$user_name)&&($password==md5(md5($user_password)))){
            if(Session::get('milkcaptcha')==$user_captcha){
                session(['checkLogin' => $name]);
                return redirect('/back_index');
            }else{
                 return  $this->returnError('wrro',"验证码输入错误");
            }
        }else{
            return  $this->returnError('wrro',"用户名或密码输入错误");
        }
	}
    function returnError($messge,$info){
        return  redirect()->back()->with($messge,$info)->withInput();
    }

    function modify(Request $request){
          if ($request->isMethod('post')) {
         
            $oldpass=$request->oldpass;
            $password=$request->pass;
            $newpass=$request->newpass;
            $regx='/^[.\_0-9a-z]{6,}$/';
            if(preg_match($regx,$newpass)){
            $user= Admin::get_user();
              foreach ($user as $key ) {
                 $checkpass=$key->password;
              }
              if ($checkpass==md5(md5($oldpass))) {
                 if ($password==$newpass) {
                  $name=Session::get('checkLogin');
                  $bool= Admin::set_password($name,md5(md5($newpass)));
                  return responseToJson(0,'success',"密码修改成功");
                 }else{
                  return responseToJson(1,"failed","两次密码输入不一致");
                 }
              }else{
                return responseToJson(1,"failed","原密码输入错误");
              }
             
          }else{
            return responseToJson(1,"failed","密码格式不对");
          }

        }
    }

    function loginout(){
         session(['checkLogin' =>null]);
         return responseToJson(0,'success','/');
    }

}