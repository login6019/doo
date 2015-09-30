<style type="text/css">
#testData {	margin:10px auto; }
#testData td{ padding:5px; }


</style>

<h3>데이터 입력[TEST]</h3>
<section>
<article>
<form method="post" name="indexForm" action="/Process_test/insert">
	<table border="1" cellpadding="3" cellspacing="0" >
	<tr>
		<td>인덱스명</td>
		<td><input type="text" name="indexName" value="doo"/></td>
	</tr>
	<tr>
		<td>타입명</td>
		<td><input type="text" name="typeName" value="dooType"/></td>
	</tr>
	<tr>
		<td>타입명</td>
		<td align="center"><h3>데이터 입력</h3>
			<table id="testData" border="1">
				<tr>
					<td style="width:20%;">No.</td><td><input type="text" name = "no"></td>
				</tr>
				<tr>
					<td>네임[macab test]</td><td><input type="text" name = "name"></td>
				</tr>
				
				<tr>
					<td>searchKeyword[object]</td><td id="filterArea"><input type="text" name = "filter[]"><br/><input type="text" name = "filter[]"><br/><input type="text" name = "filter[]"><br/><input type="text" name = "filter[]"><br/></td>
				</tr>
				<tr>
					<td>위치</td><td>위도 : <input type="text" name = "lat" value="37.512367" style="width:30%;"> 경도 : <input type="text" name = "lon" style="width:30%;" value="127.025137"></td>
				</tr>
			</table>
		</td>	
	</tr>
	<tr>
		<td colspan= "2"><button type="submit" id="submit">데이터 입력</button></td>
	</tr>
	</table>
</form>
</article>
</section>
