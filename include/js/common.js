$(function(){

	$("#buttonArea>button").click(function(){
		var index = $(this).attr("id");
		$("#indexName").val(index);
		$("#typeName").val(index+"Type");
		$("input").attr("readonly", true);
	});


});