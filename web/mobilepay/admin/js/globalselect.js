$(document).ready(function(){
var value =$("#openshop").val();
var webhidden  = $("#webhidden");  
		if(value=="1")
		{
			$("option",webhidden).remove(); //清空原有的选项
			 option = "<option value='1'>是</option>"; 
		}else{
			$("option",webhidden).remove(); //清空原有的选项
			option = "<option value='0'>否</option>"; 
			option += "<option value='1'>是</option>"; 
		}	                
	     webhidden.append(option);               
});
function show_ishidden(value){
 
	var webhidden  = $("#webhidden");  
		if(value=="1")
		{
			$("option",webhidden).remove(); //清空原有的选项
			 option = "<option value='1'>是</option>"; 
		}else{
			$("option",webhidden).remove(); //清空原有的选项
			option = "<option value='0'>否</option>"; 
			option += "<option value='1'>是</option>"; 
		}	                
	     webhidden.append(option);               
}