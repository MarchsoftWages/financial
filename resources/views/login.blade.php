<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="token" content="{{csrf_token()}}">
    <!-- <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"> -->
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
    <title>工资管理登录</title>
    <style type="text/css">
    	@charset "UTF-8";

body{
  margin: 0px;
  padding: 0px;
  font-family: "Segoe UI", "Lucida Grande", Helvetica, Arial, "Microsoft YaHei", FreeSans, Arimo, "Droid Sans", "wenquanyi micro hei", "Hiragino Sans GB", "Hiragino Sans GB W3", Arial, sans-serif;
}

.top{
	position: fixed;
	height: 100%;
	width: 100%;
}
.in{
	margin: 0;
	height: 30px;
	margin: 5px;
	width: 90%;
	padding-left: 5px;

}
.captcha{
	margin: 0;
	height: 30px;
	margin: 5px;
	width: 52%;
}
.form{
	height: 285px;
	width: 375px;
	background-color:#FFE4C4;
	border-radius: 6px;
	margin: 0 auto;
	margin-top: 10%;
}
.login{
	 padding: 10px;
	 border-bottom: 1px solid #6699FF;
	 color: #6699FF;
}
table{
	margin: 0 auto;
}
#submit{
	background-color: #6699FF;
	border: none;
}
    </style>
</head>
<body>
<div class="top">
	<div class="form">
		<form onsubmit="return toVaild()" enctype="multipart/form-data"  method="post" action="{{url('login')}}">
		{{csrf_field()}}
		 <table>                                                              
		 	<tr><div class="login">用户登录</div></tr>
		 	<tr>
		 		<td ><input type="text" name="user[name]" id="name" mix="6" placeholder ="用户名" class="in" value="{{old('user')['name']}}" ></td>
		 	</tr>
		 	<tr>
		 		<td ><input type="password" name="user[password]" id="password" placeholder="密码" class="in" value="{{old('user')['password']}}"></td>
		 	</tr>
		 	<tr>
		 		<td><input type="text" name="user[Captcha]" class="captcha" id="Captcha"><img        src="{{ url('captcha/1') }}" onclick="this.src='{{url('captcha/1')}}?'+Math.random();"  style="margin-bottom: -12px;"></td>
		 	</tr>
		 	<tr><td><input type="submit" name="submit" value="登录" class="in" id="submit"></td></tr>
		 	</tr>
		 	<!-- <tr><td ><a href="" aligin="right">忘记密码</a></td></tr> -->
		 	 
		 </table>

		</form>
	</div>
</div>
<script type="text/javascript">
	function toVaild(){
	let name = $('#name').val();
	let password = $('#password').val();
	let Captcha =$('#Captcha').val();
 	let reg = /^\d{6}$/;
	let pass=/^\w{6,}$/;
    let capt=/^[0-9a-zA_Z]{5}$/;

	 if(!reg.test(name)){
      $("#name").val("");
      $("#name").attr('placeholder','用户名错误');
      return false;
    }
    else if(!pass.test(password)){
     $("#password").val("");
     $("#password").attr('placeholder','密码输入错误');
     return false;
    }
    else if(!capt.test(Captcha)) {
      $("#Captcha").val("");
      $("#Captcha").attr('placeholder','验证码输入错误');
      return false;
    }else{
    	return true;
    }
    
}
</script>
</body>
</html>