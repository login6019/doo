<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>[<?=$zone ?>] Elastic 관리자</title>
	<link rel="stylesheet" href="/include/css/reset.css">
	<link rel="stylesheet" href="/include/css/common.css">
	<script type="text/javascript" src="/include/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="/include/js/common.js"></script>
 </head>
 <body>
 <header>
 <h1 style="color:<?= SERVER_COLOR?>;"><a href="/manage">[<?=$zone ?>] Elastic 관리자 (<?= $httpHost ?>)</a></h1>
	<div id="banner">
		<ul>
			<li><a href="/manage/create">index 생성</a></li>
			<li><a href="/manage/delete">index 삭제</a></li>
			<li><a href="/manage/setting">index 세팅</a></li>
		</ul>
	</div>
	<nav>
	<table border="0">
		<tr>
			<td><a href='/manage/castInsert'><button type="button">케스트 데이터 입력</button></a></td>
			<td><a href='/manage/photoInsert'><button type="button">사진 데이터 입력</button></a></td>
			<td><a href='/manage/stayInsert'><button type="button">숙박 데이터 입력</button></a></td>
			<td><a href='/manage/travelInsert'><button type="button">여행 데이터 입력</button></a></td>
			<td><a href='/manage/dooInsert'><button type="button">테스트 입력</button></a></td>
		</tr>
	</table>
	</nav>
 </header>
 