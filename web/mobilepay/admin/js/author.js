


function submit_save(){
	$("#cardregform_action").val("zc");	
    cardregform.submit(); 	
}

function InitAjax()
{
　var ajax=false; 
　try { 
　　ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
　} catch (e) { 
　　try { 
　　　ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
　　} catch (E) { 
　　　ajax = false; 
　　} 
　}
　if (!ajax && typeof XMLHttpRequest!='undefined') { 
　　ajax = new XMLHttpRequest(); 
　} 
　return ajax;
}

function show_nbb(){
	if($("#show_nbb").css('display') == "none"){
	  $("#show_nbb").show();
	  $("#zccolorbox").show();
	  $("#qccolorbox").show();
	}else{
	  $("#show_nbb").hide();
	  $("#zccolorbox").hide();
	  $("#qccolorbox").hide();
    }
}

function submit_clear(){
    for(i=1; i <=12; i++){
		$("#salemoney_"+i).val("");
		$("#lirun_"+i).val("");
		$("#lirunpre_"+i).val("");
		$("#income_"+i).val("");
		$("#free_"+i).val("");
		$("#jingli_"+i).val("");
		$("#jinglipre_"+i).val("");			
	}
	
	$("#all_salemoney").html("0.00");
    $("#all_lirun").html("0.00");
    $("#all_lirunpre").html("0.00");
	$("#all_income").html("0.00");
	$("#all_free").html("0.00");
	$("#all_jingli").html("0.00");
	$("#all_jinglipre").html("0.00");
}


//貧勧指距痕方
function refeedback(scatid,act,getvalid,imgid)
{

	var strItems
	  	strItems = $("#"+getvalid).val();
	var arr_value =new Array();
	 	arr_value= strItems.split('@@');
		alert(arr_value[1]);
	if(strItems=="" && act!="delete")  
	{
		return false;
	}
	
		
	$("#"+getvalid).attr("value",arr_value[0]);
	$("#"+getvalid+"id").attr("value",arr_value[1]);
	$("#"+getvalid+"_pic").html("<img  height=60 width=60 src='"+arr_value[0]+"'  style='border-bottom: 1px solid #000000;border-top: 1px solid #000000;border-right: 1px solid #000000;border-left: 1px solid #000000;'/>");
	$("#"+getvalid+"colorbox").attr("value","嶷勧");
	$("#"+getvalid+"colorbox").next(".jrljsqscipt").remove();
	$("#"+getvalid+"colorbox").after($('&nbsp;&nbsp;<input class="jrljsqscipt" name="" type="button" value="臥心" onclick="showbanklogo(\''+getvalid+'\');return false;" />'));
	$("#"+getvalid).change();
	$("#"+getvalid).focus();
	$("#"+getvalid).blur();
}

function cardregchang(dthis,did){
  if($(dthis).val()=='Y'){
	$("#"+did).attr("datatype","n").attr("nullmsg","萩補秘").removeAttr("readonly").focus().siblings(".valid").show();  
  }else{
	$("#"+did).removeAttr("datatype").removeAttr("nullmsg").attr("readonly","readonly").focus().siblings(".valid").hide();   
  }  	
}

function cardty(dthis){
  if(dthis.checked){
    $("#tyxy").val("Y");
  }else{
    $("#tyxy").val("");
	
  }
}

function showbanklogo(did,obj){
	
  $(obj).colorbox({href:$("#"+did).val(),  width:"50%", height: "200", slideshowAuto: true});
  return false;
}


function show_pre(month){
    var salemoney = $("#salemoney_"+month).val();
	var lirun = $("#lirun_"+month).val();
	var income = $("#income_"+month).val();
	var free = $("#free_"+month).val();
	
	if(salemoney == ""){
	  salemoney = 0;
	}
	
	if(lirun == ""){
	  lirun = 0;
	}
	
	if(income == ""){
	  income = 0;
	}
	
	if(free == ""){
	  free = 0;
	}
	
	if(salemoney != 0 && salemoney != ""){
	   var lirunpre = parseFloat(lirun)*100/parseFloat(salemoney);
	}else{
	   var lirunpre = 0;
	}
	
	var jingli = parseFloat(lirun)+parseFloat(income)-parseFloat(free);
	
	if(parseFloat(lirun)+parseFloat(income) != 0){
	  var jinglipre = parseFloat(jingli)*100/(parseFloat(salemoney)+parseFloat(income));
	}else{
	  var jinglipre = 0;
	}
	
	lirunpre = FormatNumber(lirunpre,2);
	jingli = FormatNumber(jingli,2);
	jinglipre = FormatNumber(jinglipre,2);
    
	$("#lirunpre_"+month).val(lirunpre);
	$("#jingli_"+month).val(jingli);
	$("#jinglipre_"+month).val(jinglipre);
	
	countallmoney();
}

function countallmoney(){
     var salemoney = 0;
	 var lirun = 0;
	 var income = 0;
	 var free = 0;
	 var jingli = 0;
	 var lirunpre = 0;
	 var jinglipre = 0;
	 
	 var all_salemoney = 0;
	 var all_lirun = 0;
	 var all_income = 0;
	 var all_free = 0;
	 var all_jingli = 0;
	 var all_lirunpre = 0;
	 var all_jinglipre = 0;
	 
	 for(i=1; i<=12; i++){
		 salemoney = $("#salemoney_"+i).val();
	     lirun = $("#lirun_"+i).val();
	     income = $("#income_"+i).val();
	     free = $("#free_"+i).val();
		 
		 if(salemoney == ""){
			salemoney = 0;
		 }
		 
		 if(lirun == ""){
			lirun = 0;
		 }
		 
		 if(income == ""){
			income = 0;
		 }
		 
		 if(free == ""){
			free = 0;
		 }
		 
		 all_salemoney = parseFloat(salemoney)+parseFloat(all_salemoney);
		 all_lirun = parseFloat(lirun)+parseFloat(all_lirun);
		 all_income = parseFloat(income)+parseFloat(all_income);
		 all_free = parseFloat(free)+parseFloat(all_free);

	 }
	 
	 var all_jingli = parseFloat(all_lirun)+parseFloat(all_income)-parseFloat(all_free);
	 
	 if(all_salemoney != 0 && all_salemoney != ""){
	    var all_lirunpre = parseFloat(all_lirun)*100/parseFloat(all_salemoney);
	 }else{
	    var all_lirunpre = 0;
	 }
	 
	 if(parseFloat(all_lirun)+parseFloat(all_income) != 0){
	   var all_jinglipre = parseFloat(all_jingli)*100/(parseFloat(all_salemoney)+parseFloat(all_income));
	 }else{
	   var all_jinglipre = 0;
	 }
		 
	 all_salemoney = FormatNumber(all_salemoney,2);
	 all_lirun = FormatNumber(all_lirun,2);
	 all_income = FormatNumber(all_income,2);
	 all_free = FormatNumber(all_free,2);
	 
	 all_lirunpre = FormatNumber(all_lirunpre,2);
	 all_jingli = FormatNumber(all_jingli,2);
	 all_jinglipre = FormatNumber(all_jinglipre,2);
	 
	 $("#all_salemoney").html(all_salemoney);  
	 $("#all_lirun").html(all_lirun);  
	 $("#all_income").html(all_income);  
	 $("#all_free").html(all_free);  
	 $("#all_jingli").html(all_jingli); 
	 
	 $("#all_lirunpre").html(all_lirunpre);  
	 $("#all_jinglipre").html(all_jinglipre);  
}

function FormatNumber(srcStr,nAfterDot){
　　var srcStr,nAfterDot;
　　var resultStr,nTen;
　　srcStr = ""+srcStr+"";
　　strLen = srcStr.length;
　　dotPos = srcStr.indexOf(".",0);

　　if (dotPos == -1){
　　　　resultStr = srcStr+".";
　　　　for (i=0;i<nAfterDot;i++){
　　　　　　resultStr = resultStr+"0";
　　　　}
　　　　return resultStr;
　　}
　　else{
　　　　if ((strLen - dotPos - 1) >= nAfterDot){
　　　　　　nAfter = dotPos + nAfterDot + 1;
　　　　　　nTen =1;
　　　　　　for(j=0;j<nAfterDot;j++){
　　　　　　　　nTen = nTen*10;
　　　　　　}
　　　　　　resultStr = Math.round(parseFloat(srcStr)*nTen*100)/(nTen*100);    //及匯肝狛陀渠朔励了參朔議
            resultStr = Math.round(parseFloat(resultStr)*nTen*10)/(nTen*10);    //及匯肝狛陀渠朔膨了參朔議
            resultStr = Math.round(parseFloat(resultStr)*nTen)/nTen; //及匯肝狛陀渠朔眉了參朔議
　　　　　　return resultStr;
　　　　}
　　　　else{
　　　　　　resultStr = srcStr;
　　　　　　for (i=0;i<(nAfterDot - strLen + dotPos + 1);i++){
　　　　　　　　resultStr = resultStr+"0";
　　　　　　}
　　　　　　return resultStr;
　　　　}
　　}
}
