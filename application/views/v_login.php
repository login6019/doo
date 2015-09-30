<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>관리자 메인</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/include/css/login.css">
	<script type="text/javascript" src="/include/js/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script type="text/javascript" src="/include/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/include/js/login.js"></script>
 </head>
 <body>
 <div id="login">
	<form action="http://www.naver.com" method="post" onsubmit="return false();">
	<table>
		<tr>
			<td colspan = "2">로그인</td>
		</tr>
		<tr>
			<td>ID : </td><td><input type="text" id="id"></td>
		</tr>
		<tr>
			<td>PW : </td><td><input type="text" id="pw"></td>
		</tr>
		<tr>
			<td colspan = "2"><button type="button" id="loginButton">로그인</button></td>
		</tr>
	</table>
	</form>
	<!--
	<div id="progressbar"></div>
	-->
 </div>
 </body>
</html>
