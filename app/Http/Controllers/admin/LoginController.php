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
    public function get_vaild(Request $request){
        $user= Admin::get_user();
        foreach ($user as $key ) {
           $name= $key->name;
           $password=$key->password;
        }
        if ($request->isMethod('post')) {    
            $this ->validate($request,[
                'user.name'=>'required|digits:6|regex:/^\d{6}$/',
                'user.password'=>'required|min:6|regex:/^\w{6,}$/',
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
                session(['login' => 'true']);
                return redirect('/back_index');
            }else{
                 return  redirect()->back()->with('wrro',"验证码输入错误")->withInput();
            }
        }else{
            return  redirect()->back()->with('wrro',"用户名或密码输入错误")->withInput();
        }
	}
}