$(document).ready(function(){
var value =$("#openshop").val();
var webhidden  = $("#webhidden");  
		if(value=="1")
		{
			$("option",webhidden).remove(); //���ԭ�е�ѡ��
			 option = "<option value='1'>��</option>"; 
		}else{
			$("option",webhidden).remove(); //���ԭ�е�ѡ��
			option = "<option value='0'>��</option>"; 
			option += "<option value='1'>��</option>"; 
		}	                
	     webhidden.append(option);               
});
function show_ishidden(value){
 
	var webhidden  = $("#webhidden");  
		if(value=="1")
		{
			$("option",webhidden).remove(); //���ԭ�е�ѡ��
			 option = "<option value='1'>��</option>"; 
		}else{
			$("option",webhidden).remove(); //���ԭ�е�ѡ��
			option = "<option value='0'>��</option>"; 
			option += "<option value='1'>��</option>"; 
		}	                
	     webhidden.append(option);               
}