<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="token" content="{{csrf_token()}}">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
    <title>工资管理登录</title>
	<style type="text/css">
    	@charset "UTF-8";

<<<<<<< HEAD
body{
  font-family: "Segoe UI", "Lucida Grande", Helvetica, Arial, "Microsoft YaHei", FreeSans, Arimo, "Droid Sans", "wenquanyi micro hei", "Hiragino Sans GB", "Hiragino Sans GB W3", Arial, sans-serif;
}

.top{
	position: fixed;
	height: 100%;
	width: 100%;
	z-index: 999;
}
.in{
	height: 30px;
	margin: 5px;
	width: 95%;
	padding-left: 5px;
	border-radius: 5px;
	border: 1px solid #cccccc;

}
.login{
	height: 60px;
}
.captcha{
	margin: 0;
	height: 30px;
	margin: 5px;
	width: 55%;
	border-radius: 5px;
	border: 1px solid #cccccc;
}
.form{
	height: auto;
	width: 400px;
	background-color:#fff;
	border-radius: 6px;
	margin: 0 auto;
	margin-top: 10%;
	box-shadow: 1px 1px 3px 3px #cccccc;
}
form{
	padding: 10px 0 30px 0;
}
h2{
	margin-left: 35%;
}
table{
	margin: 0 auto;
}
#submit{
	background-color: #6699FF;
	border: none;
}
=======
	body{
	  margin: 0px;
	  padding: 0px;
	  font-family: "Segoe UI", "Lucida Grande", Helvetica, Arial, "Microsoft YaHei", FreeSans, Arimo, "Droid Sans", "wenquanyi micro hei", "Hiragino Sans GB", "Hiragino Sans GB W3", Arial, sans-serif;
	}

	.top{
		position: fixed;
		height: 100%;
		width: 100%;
		z-index: 999;
	}
	.in{
		height: 40px;
		margin: 5px;
		width: 100%;
		padding-left: 5px;
		border-radius: 5px;
		border: 1px solid #cccccc;

	}
	.captcha{
		margin: 0;
		height: 30px;
		margin: 5px;
		width: 55%;
		border-radius: 5px;
		border: 1px solid #cccccc;
	}
	.form{
		height: 330px;
		width: 500px;
		background-color:#fff;
		border-radius: 6px;
		margin: 0 auto;
		margin-top: 8%;
		box-shadow: 1px 1px 3px 3px #cccccc;
	}
	form{
		padding: 10px 0;
	}
	h2{
		margin-left: 40%;
	}
	table{
		margin: 0 auto;
	}
	#submit{
		background-color: #6699FF;
		border: none;
	}
>>>>>>> 6d07cfd8f5c87add9e6f1180158d2c91e5156d59

/**************登陆背景效果*************/
		*{margin: 0;padding: 0;}
		#container {
			position: absolute;
			height: 100%;
			width: 100%;
		}
		#output {
			width: 100%;
			height: 100%;
		}
.error{
	margin: 0 auto;
	width: 400px;
	margin-top: 5%;
	background-color:rgb(242,222,222);
	border-radius: 6px;
	color: rgb(185,74,138);
	z-index: 99;
}
.alert{
	padding: 10px;
	font-family: "楷体";
}
.close{
	float: right;
	font-size: 20px;
	cursor: pointer;
}
.wrong{
	font-size: 12px;
	margin-left: 10px;
	color: red;
	width: auto;
	 display: inherit;
}

 </style>
</head>
<body>
<div class="top">
	@if(count($errors))
	<div class="error" id="errors" >
	  <div class="alert alert-error">
	    <div class="close" data-dismiss="alert" onclick="closes()">×</div>
	    @foreach($errors as $error)
	      <strong>Error!</strong>{{$error->first()}}
	    @endforeach  
	   </div>
	</div>
	 @endif
	 @if(Session::has('wrro'))
	 <div class="error" id="errors" >
	  <div class="alert alert-error">
	    <div class="close" data-dismiss="alert" onclick="closes()">×</div>
	      <strong>Error!</strong>{{Session::get('wrro')}}
	   </div>
	</div>
	  @endif
	<div class="form">
		<form onsubmit="return toVaild()" enctype="multipart/form-data"  method="post" action="{{url('checkLogin')}}">
		{{csrf_field()}}
		<div class="login"><h2>系统登录</h2></div>
		 <table>                                                              
		 	<tr>
		 		<td ><input type="text" name="user[name]" id="name" mix="6" placeholder ="用户名" class="in" value="{{old('user')['name']}}"></td>

		 	</tr>
		 	<tr>
		 		<td ><p id="user" class="wrong"></p></td>
		 	</tr>
		 	<tr>
		 		<td ><input type="password" name="user[password]" id="password" placeholder="密码" class="in" value="{{old('user')['password']}}"></td>
		 	</tr>
		 	<tr>
		 		<td ><p id="pass" class="wrong"></p></td>
		 	</tr>
		 	<tr>
		 		<td><input type="text" name="user[Captcha]" class="captcha" id="Captcha"><img        src="{{ url('captcha/1') }}" onclick="this.src='{{url('captcha/1')}}?'+Math.random();"  style="margin-bottom: -12px;"></td>
		 	</tr>
		 	<tr>
		 		<td ><p id="cap" class="wrong"></p></td>
		 	</tr>
		 	<tr><td><input type="submit" name="submit" value="登录" class="in" id="submit"></td></tr>
		 	</tr>
		 </table>
		</form>
	</div>
</div>
<div id="container"><div id="output"></div></div>
<script src="/js/unit/jquery.js"></script>
<script src="/js/unit/vector.js"></script>
<script>
    $(function(){
        // 初始化 传入dom id
        var victor = new Victor("container", "output");
        var theme = [
            ["#002c4a", "#005584"],
            ["#35ac03", "#3f4303"],
            ["#ac0908", "#cd5726"],
            ["#18bbff", "#00486b"]
        ]
        $(".color li").each(function(index, val) {
            var color = theme[index];
            $(this).mouseover(function(){
                victor(color).set();
            })
        });
    });
</script>
<script type="text/javascript">
	function toVaild(){
		let name = $('#name').val();
		let password = $('#password').val();
		let Captcha =$('#Captcha').val();
	 	let reg = /^\d{6}$/;
		let pass=/^[.\_0-9a-z]{6,}$/;

	    let capt=/^[0-9a-zA_Z]{5}$/;

		if(!reg.test(name)){
			$("#name").val("");
			$("#user").html('用户名错误');
			return false;
	    }
	    else if(!pass.test(password)){
	    	$("#user").html('');
			$("#password").val("");
			$("#pass").html('密码输入错误');
			return false;
	    }
		else if(!capt.test(Captcha)) {
			$("#user").html('');
			$("#pass").html('');
			$("#Captcha").val("");
			$("#cap").html('验证码输入错误');
			return false;
		}else{
			return true;
		} 
	}

	function closes(){
		$("#errors").remove();
	} 

</script>

</body>
</html>