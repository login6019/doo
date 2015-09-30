<h3>인덱스 세팅</h3>
<section>
<article>
<form method="post" name="indexForm" action="/Process/settingIndex">
<table border="1" cellpadding="3" cellspacing="0" >
<tr>
	<td>인덱스명</td>
	<td><input type="text" name="indexName" id="indexName" value="<?= $index?>"/></td>
</tr>
<tr>
	<td>타입명</td>
	<td><input type="text" name="typeName" id="typeName" value="<?=  (!empty($index))?$index."Type": ""; ?>"/></td>
</tr>
<tr>
	<td colspan= "2"><button type="submit" id="submit">인덱스 세팅</button></td>
</tr>
</table>
</form>
</article>
</section>
<div id="buttonArea">
	<button type="button" id ="photo"class="bggreen">사진[photo]</button>
	<button type="button" id ="stay" class="bggreen">숙박[stay]</button>
	<button type="button" id ="travel" class="bggreen">여행[travel]</button>
	<button type="button" id ="cast" class="bggreen">케스트[cast]</button>	
	<button type="button" id ="doo" class="bggreen">테스트[doo]</button><p id="space"></p>
	<button type="button" id ="photolog"class="bgblue">사진[photolog]</button>
	<button type="button" id ="staylog" class="bgblue">숙박[staylog]</button>
	<button type="button" id ="travellog" class="bgblue">여행[travellog]</button>
	<button type="button" id ="castlog" class="bgblue">케스트[castlog]</button>	
</div>
