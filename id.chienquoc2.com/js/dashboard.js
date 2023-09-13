var Dashboard = function ()
{
	var chartColors = ['#F90','#333', '#555', '#111','#002646','#999','#bbb','#ccc','#eee'];

	return { init: init };
	
	function init ()
	{		
		$('*[rel=facebox]').facebox ();
		$('.datatable').dataTable ({
			"aoColumns": [
			
				{ "sWidth": "8%" , "sType": 'numeric' },
				{ "sWidth": "22%" },
				{ "sWidth": "15%" },
				{ "sWidth": "15%" },
				{ "sWidth": "15%" },
				{ "sWidth": "25%" },
			],
			"aaSorting": [[0, 'asc']],
		});
		$('.datatableknb').dataTable ({
			"aoColumns": [
				
				{ "sWidth": "8%" , "sType": 'numeric' },
				{ "sWidth": "15%" },
				{ "sWidth": "15%" },
				{ "sWidth": "23%" },
				{ "sWidth": "15%" },
				{ "sWidth": "25%" },
			],
			"aaSorting": [[0, 'asc']],
		});		
		$('.uniform').find ('input, select').uniform ();
		$('input, textarea').placeholder ();
		
		$('table.stats').each(function() 
		{		
			var chartType = '';
			
			if ( $(this).attr('data-chart') ) 
			{ 
				chartType = $(this).attr('data-chart'); 				
			}
			else 
			{ 
				chartType = 'area'; 
			}
			
			var chart_width = $(this).parent ().width () * .92;
					
			$(this).hide ().visualize({		
				type: chartType,	// 'bar', 'area', 'pie', 'line'
				width: chart_width,
				height: '240px',
				colors: chartColors
			});				
		});
	}	
}();
$(function() {
	$( "#datepicker" ).datepicker({
	dateFormat: 'dd/mm/yy',
	changeMonth: true,
	changeYear: true,
	yearRange: '1970:1996',
	monthNamesShort: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12']
	});
});

		
//VALIDATION FORM//
var validator = $("#formtest").validate({ 
rules: {
//	fieldGiftcode: {
//		required: true, 
//		minlength: 6
//	},
	fieldAcount: {
		required: true, 
		minlength: 5
	},
	fieldPassword: {
		required: true, 
		minlength: 6
	},
	fieldRePassword: { 
		required: true, 
		minlength: 6,
		equalTo: "#fieldPassword" 
	}, 
	fieldEmail: { 
		required: true, 
		email: true
	},
	txtCaptcha: {
		required: true, 
		minlength: 4
	},				
}, 
messages: {}, 
errorPlacement: function(error, element) { 
	if ( element.is(":radio") ) 
		error.appendTo( element.parent().prev() ); 
	else if ( element.is(":checkbox") ) 
		error.appendTo ( element.parent().prev() ); 
	else 
		error.appendTo( element.prev() ); 
}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["formtest"].submit();
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
}); 
$("#fieldEmail").focus(function() { 
	var username = $("#fieldAcount").val(); 
	if(username && !this.value) { 
		this.value = username + "@"; 
	} 
}); 
$("#reset").click (function(){
	$("#formtest .medium").val ("");
});



//LOGIN FORM//
var validator = $("#loginform").validate({ 
rules: { 
	fieldAcount: {
		required: true, 
		minlength: 4
	},
	fieldPassword: {
		required: true, 
		minlength: 6
	},			
}, 
messages: {}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["loginform"].submit();
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
});

//NAPTHE FORM//
var validator = $("#depform").validate({ 
rules: { 
	fieldSerial: {
		required: true, 
		minlength: 9
	},
	fieldCode: {
		required: true, 
		minlength: 9
	},	
	txtCaptcha: {
		required: true, 
		minlength: 4
	},			
}, 
messages: {}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["depform"].submit();
	document.getElementById("buttonNap").style.display='none';
	document.getElementById("loadingNap").style.display='block';
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
}); 
$("#reset").click (function(){
	$("#depform .medium").val ("");
});


//UPDATE INFO FORM//
var validator = $("#updateinfo").validate({ 
rules: { 
	fieldPassword: {
		required: false, 
		minlength: 6
	},
	fieldRePassword: { 
		required: false, 
		minlength: 6,
		equalTo: "#fieldPassword" 
	},
	fieldSocialNumber: { 
		required: true, 
		minlength: 9,
		equalTo: "#fieldSocialNumber" 
	},
	fieldPhoneNumber: { 
		required: true, 
		minlength: 10,
		equalTo: "#fieldPhoneNumber" 
	},	
	txtCaptcha: {
		required: true, 
		minlength: 4
	},			
}, 
messages: {}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["updateinfo"].submit();
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
}); 


//FORGETPASS FORM//
var validator = $("#formpass").validate({ 
rules: { 
	fieldAcount: {
		required: true, 
		minlength: 5
	},
	fieldEmail: { 
		required: true, 
		minlength: 6,
	},	
	txtCaptcha: {
		required: true, 
		minlength: 4
	},			
}, 
messages: {}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["formpass"].submit();
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
}); 

//UPDATEPASS FORM//
var validator = $("#formpass2").validate({ 
rules: { 
	fieldPasswordNew: {
		required: true, 
		minlength: 6
	},
	fieldRePassword: { 
		required: true, 
		minlength: 6,
		equalTo: "#fieldPasswordNew" 
	},	
	txtCaptcha: {
		required: true, 
		minlength: 4
	},			
}, 
messages: {}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["formpass2"].submit();
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
}); 
function findNewUser(id, type){

	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.open("GET",'/CheckAvailableAccount.php?id='+id+'&type='+type,false);
	xmlhttp.send(null);
		if (xmlhttp.responseText.length==0)
			{
			//	$('#fieldAcount').addClass('valid_small');
			}
		else{
				if (type=='id'){
					alert(xmlhttp.responseText);
					document.formtest.fieldAcount.value = "";
					$('#fieldAcount').addClass('error_small');
				}
				else{
					alert(xmlhttp.responseText);
					document.formtest.fieldEmail.value = "";
					$('#fieldEmail').addClass('error_small');				
				}
			}
}
function checkGiftcode(id){

	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.open("GET",'/checkGiftcode.asp?id='+id,false);
	xmlhttp.send(null);
		if (xmlhttp.responseText.length==0)
			{
			//	$('#fieldAcount').addClass('valid_small');
			}
		else{
				alert(xmlhttp.responseText);
				document.formtest.fieldGiftcode.value = "";
				$('#fieldGiftcode').addClass('error_small');
			}
}

//XU2KNB FORM//
var validator = $("#Xu2KNB").validate({ 
rules: { 
	fieldServer: {
		required: true, 
		minlength: 1
	},
	txtCaptcha: {
		required: true, 
		minlength: 5
	},			
}, 
messages: {}, 
submitHandler: function() { 
	//alert("Validate!"); 
	document.forms["Xu2KNB"].submit();
}, 
success: function(label) { 
	label.html("&nbsp;").addClass("valid_small"); 
} 
}); 