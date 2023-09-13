function PostbackData(url, param){
   $.ajax({
        type: "GET",
        url: url,
        cache: false,
        data: param ,

        beforeSend: function(){
           // $("#charactername").html("wait");
        },
        complete: function(){
        },
        error: function(xmlHttp, textStatus, errorThrown){
            $("#charactername").html(xmlHttp.responseText);
        },
        success: function(result){
			if (document.getElementById('fieldCharacter') != null) {
				if (result.length > 1) {
					$("#charactername").css("display", "none");
					$("#selectcharacter").css("display", "block");
					$("#fieldCharacter").html(result);
				} else {
					$("#selectcharacter").css("display", "none");
				}
			} else {
				$("#charactername").html(result);
			}
			document.getElementById("submitbutton").style.display = 'block';
			if (result=='')
			{
				document.getElementById("submitbutton").style.display = 'none';
				$("#charactername").html('Bạn chưa có nhân vật trên Server này');
				alert('Bạn chưa có nhân vật trên Server này');
			}else if (result=='offline')
			{
				document.getElementById("submitbutton").style.display = 'none';
				$("#charactername").html('Máy chủ này đang bảo trì');
				alert('Máy chủ này đang bảo trì');
			}else if (result==' ')
			{
				document.getElementById("submitbutton").style.display = 'none';
			}
        }
    });
}
function fnSubmitRegis(id, page){
		var param = {
			"acc": $("#Account").val(),
			"page": page,
			"server": id
		};
		var url = 'userinfo.php';		
		PostbackData(url, param);
}
