//上传回调函数


function refeedback(scatid,dateid,act,getvalid,imgid)
{	
	var strItems
	  	strItems = $("#"+getvalid).val();
	var arr_value =new Array();
	 	arr_value= strItems.split('@@');
	
	if(strItems=="" && act!="delete")  
	{
		return false;
	}
	
$("#"+getvalid).val(arr_value[0]);
$("#"+getvalid+"id").val(arr_value[1]);
$("#"+getvalid+"show").show();	
	
}


function showbanklogo(did){
  $.fn.colorbox({href:$("#"+did).val()});
  return false;
}

$(function(){
  $(".zone_p").click(function(){
	if(this.checked){
	  $('.zone_son').attr('checked','checked').attr('disabled','disabled');	
	}else{
	  $('.zone_son').removeAttr('checked').removeAttr('disabled');		
	}						  
  });		   
})