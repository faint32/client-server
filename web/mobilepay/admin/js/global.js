//处理键盘事件 禁止后退键（Backspace）密码或单行、多行文本框除外
function banBackSpace(e){
    var ev = e || window.event;//获取event对象
    var obj = ev.target || ev.srcElement;//获取事件源
    var t = obj.type || obj.getAttribute('type');//获取事件源类型
    //获取作为判断条件的事件类型
    var vReadOnly = obj.readOnly;
    var vDisabled = obj.disabled;
    //处理undefined值情况
    vReadOnly = (vReadOnly == undefined) ? false : vReadOnly;
    vDisabled = (vDisabled == undefined) ? true : vDisabled;
    //当敲Backspace键时，事件源类型为密码或单行、多行文本的，
    //并且readOnly属性为true或disabled属性为true的，则退格键失效
    var flag1= ev.keyCode == 8 && (t=="password" || t=="text" || t=="textarea")&& (vReadOnly==true || vDisabled==true);
    //当敲Backspace键时，事件源类型非密码或单行、多行文本的，则退格键失效
    var flag2= ev.keyCode == 8 && t != "password" && t != "text" && t != "textarea" ;
    //判断
    if(flag2 || flag1)return false;
}


function showtips(action)
{

	if(action)
	{
		$("#showcontent").html(action);
	}else{
		$("#showcontent").html("正在处理数据，请稍候...");
	}

	sending.style.display ="";
	sendingbg.style.display ="";
}
function uploadimg(scatid,dateid,fd_cat_id,getvalid,act,refunction,imgid,obj){
	 //alert("测试弹出框，关闭就可以了");
	 //alert(obj);
	 $.ajax({
             type: "POST",
             url : "../ajaxread/returnglobal.php"   ,
             success: function(strItem){
				var  arr_strItem=strItem.split("@@");
             	var g_uppic   	    = arr_strItem[0];
				var g_upbackurl   	= arr_strItem[1];
    var szRef;
	var str
	str = $("#"+getvalid).val();
	var arr_value
	if(act!='new')
	{
	arr_value= str.split('@@');
	var catid = parseInt(arr_value[1]);
	if(catid!=""){fd_cat_id=parseInt(catid);}
	}

	szRef=g_uppic+"?scatid="+scatid+"&dateid="+dateid+"&act="+act+"&fd_cat_id="+fd_cat_id+"&getvalid="+getvalid+"&cb="+g_upbackurl;
    $(obj).colorbox({href:szRef,iframe:true, innerWidth:800, innerHeight:250,onClosed:function()
	{eval(refunction)(scatid,dateid,act,getvalid,imgid);}});
			 }});

}
function refeedback(scatid,dateid,act,getvalid,imgid)
{

	//parent.location.reload();
	//location.replace(document.referrer);
	var strItems
	strItems = $("#"+getvalid).val();

	var arr_value =new Array();
	arr_value= strItems.split('@@');

	if(strItems=="" && act!="delete")
	{
		return false;
	}



$("#"+getvalid+"id").attr("value",strItems);
$("#"+getvalid+"_pic").html("<img src="+arr_value[0]+" width='50' height='50'/>");
$("#upload_"+scatid).empty().html('<input type="button" class="thglbtnb" name="uploadfilecolorbox" id="uploadfilecolorbox" value="重传" onclick="uploadimg('+scatid+',\''+$("#dateid").val()+'\',\''+arr_value[1]+'\',\'upload_'+scatid+'\',\'edit\',\'refeedback\',\'preuploadfile\')" class="dpszjbszscb"/>');

$("#showimg_"+scatid).html('<input type="button" name="button" onclick=\'parent.$.fn.colorbox({href:"'+arr_value[0]+'"}); return false;\' value="查看" class="thglbtnb" />');
$("#showimg_"+scatid).prev().hide();
//$("#showimg_"+scatid).attr('onclick','parent.$.fn.colorbox({href:"'+arr_value[0]+'"}); return false;');
if(scatid==1111){
$("#upload_"+scatid).empty().html('<input type="button" class="thglbtnb" name="uploadfilecolorbox" id="uploadfilecolorbox" value="重传" onclick="uploadimg('+scatid+',\''+$("#dateid").val()+'\',\''+arr_value[1]+'\',\'upload_'+scatid+'\',\'edit\',\'refeedback\',\'preuploadfile\')" class="dpszjbszscb"/>');
}else if(scatid==16){
$("#upload_"+scatid).empty().html('<input type="button" name="uploadfilecolorbox" id="uploadfilecolorbox" value="重传" onclick="uploadimg(16,\''+$("#dateid").val()+'\',\'\',\'uploadlogo\',\'edit\',\'refeedback\',\'preuploadlogo\')" class="dpszjbszscb"/>');
}
$("#"+getvalid+"id").focus();
$("#"+getvalid+"img").colorbox({href:arr_value[0]});
}
function show_newprovinces(value){
 // var strvalue=document.getElementById("show_scity").value;

      $.getJSON("../../ajaxread/readnewprovinces.php",{area_id:value},function(json){
	  var show_province = $("#show_province");
      var show_scounty = $("#show_scounty");
	  var show_town    = $("#show_stown");
	   var show_scity  = $("#show_scity");
	   $("option",show_province).remove(); //清空原有的选项
       $("option",show_scounty).remove(); //清空原有的选项
	   $("option",show_town).remove(); //清空原有的选项
	   $("option",show_scity).remove(); //清空原有的选项
	   var option = "<option value=''>--请选择--</option>";
	   show_province.append(option);
	   $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
	     show_province.append(option);
	    });

		});
}

function show_newcity(value){
 // var strvalue=document.getElementById("show_scity").value;
      var prov = escape(value);
      $.getJSON("../ajaxread/readnewcity.php",{prov:prov},function(json){

	  var show_town    = $("#show_stown");
	   var show_scity  = $("#show_scity");

	   $("option",show_town).remove(); //清空原有的选项
	   $("option",show_scity).remove(); //清空原有的选项
	   var option = "<option value=''>--请选择--</option>";
	   show_scity.append(option);
	   $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
	     show_scity.append(option);

	    });

		});
}
function show_newtown(value){
    var city = escape(value);
  $.getJSON("../ajaxread/readnewtown.php",{city:city},function(json){

	    var show_stown = $("#show_stown");
	     $("option",show_stown).remove(); //清空原有的选项
	     var option = "<option value=''>--请选择--</option>";
         show_stown.append(option);
	     $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
             show_stown.append(option);

	    });

		});
}




