<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	账号激活失败，请重新注册！<a href="{{ url('/register') }}">点击返回</a>
	{{--<a href="{{ url('/register') }}"--}}
	   {{--onclick="event.preventDefault();document.getElementById('logout-form').submit();">--}}
		{{--<i class="fa fa-sign-out fa-icon-lg"></i>点击返回--}}
	{{--</a>--}}
	{{--<form id="logout-form" action="{{ url('/logout') }}" method="POST"--}}
		  {{--style="display: none;">--}}
		{{--{{ csrf_field() }}--}}
	{{--</form>--}}
</body>
</html>