$(document).ready(function(){

$(".navname").focus(function(){
$(this).keydown(function(event){  
     if(event.which== 13){                   
		$("#shopid").attr("value",$(this).attr("rel"));
		$("#storgename").attr("value",$(this).attr("value"));
		$("#action").attr("value","updatenav");
		form1.submit();
		}
	  });   
});
});
function submit_search(){
  form1.search.value="search";
  form1.submit();
}