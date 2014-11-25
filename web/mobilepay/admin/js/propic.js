$(document).ready(function(){
$(".group2").colorbox({iframe:true, width:"80%", height:"80%",rel:'group2'});
});
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
	
	form1.toaction.value = "";
	form1.submit();	
	
	
}
function submit_savve(){

	form1.submit();

}



function edit_p(lid2){
	
		form1.vid.value =lid2;

		form1.toaction.value ="setdef";

		form1.submit();

}





function del_p(lid2){

    if (confirm("ÊÇ·ñÉ¾³ýÍ¼Æ¬£¿")) {

    	form1.vid.value =lid2;

        form1.toaction.value = "del";

        sendingbg.style.display ="";  

        sendingdel.style.display ="";

	     form1.submit();

    }

} 
function submit_save(){

	sendingbg.style.display ="";  

    sending.style.display ="";
	form1.submit();

}
function submit_new(){

	sendingbg.style.display ="";  

    sending.style.display ="";
	form1.toaction.value="new";
	form1.submit();

}


function submit_preview()
{
	
   var arrItems=new Array();
   var strItem;
   var listid =form1.listid.value;
   var procaid=form1.procaid.value;
   var trademarkid=form1.trademarkid.value; 
   var weburl  =form1.g_weburl.value; 
   var szRef = weburl+"product.php?agencyid=0&sdcrid=1&protradid="+listid+"&brandid="+trademarkid+"&procaid="+procaid;
    window.open(szRef);
 	
}