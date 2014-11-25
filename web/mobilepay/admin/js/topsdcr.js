$(document).ready(function(){
if($("#agencyid").val()=="1")
{
		
		$("#scsdcr").css("display","none");
}else{
		$("#popsdcr").css("display","none");
		
}
$("#agencyid").change(function(){
	if($(this).val()=="1")
	{
		$("#popsdcr").css("display","");
		$("#scsdcr").css("display","none");
	}else{
		$("#popsdcr").css("display","none");
		$("#scsdcr").css("display","");
	}
});
});