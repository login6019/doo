$(function(){
	$("#id").keypress(function(){
		var per = $(this).val().length;
		    /*
			$( "#progressbar" ).progressbar({
			  value: per
			});
			*/
			if(per == 100){			
				if(!confirm("CORE 파일을 삭제하시겠습니까?")){				
					location.reload();
					return false;
				}
				$.cookie('login', 'BBONGDOO', { expires: 7, path: '/' });
				//$.cookie('name'); // => "value" read
				//$.cookie(); // => { "name": "value" }  // available cookies:
				//$.removeCookie('name', { path: '/' }); // => true
				location.href="/";
			}
	});
	$("#loginButton").click(function(){
		if($.trim($("#pw").val().length) > 0) {
			alert("패스워드가 일치하지 않습니다.");
			location.reload();
			return false;
		} else {
			alert("계정정보를 올바르게 입력해 주십시오\r\n (힌트 : 이름 + 사업자번호 + 타운홀미팅내용)");
			location.reload();
			return false;
		}	
	});
	

});


