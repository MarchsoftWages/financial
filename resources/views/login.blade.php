<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">
    <!-- <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"> -->
    <link href="{{ asset('css/login.css') }}"  rel="stylesheet">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
    <title>工资管理登录</title>
</head>
<body>
<div class="top">
	<div class="form">
		<form onsubmit="return toVaild()" enctype="multipart/form-data"  method="post" action="{{url('login')}}">
		 <table>                                                              
		 	<tr><div class="login">用户登录</div></tr>
		 	<tr>
		 		<td ><input type="text" name="user[name]" id="name" mix="6" placeholder ="用户名" class="in"></td>
		 	</tr>
		 	<tr>
		 		<td ><input type="password" name="user[password]" id="password" placeholder="密码" class="in"></td>
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
<script type="text/javascript" src="{{asset('js/login.js')}}"></script>
<!-- <script type="text/javascript" src="{{asset('jquery.js')}}"></script> -->
</body>
</html>