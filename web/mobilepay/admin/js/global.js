//��������¼� ��ֹ���˼���Backspace��������С������ı������
function banBackSpace(e){
    var ev = e || window.event;//��ȡevent����
    var obj = ev.target || ev.srcElement;//��ȡ�¼�Դ
    var t = obj.type || obj.getAttribute('type');//��ȡ�¼�Դ����
    //��ȡ��Ϊ�ж��������¼�����
    var vReadOnly = obj.readOnly;
    var vDisabled = obj.disabled;
    //����undefinedֵ���
    vReadOnly = (vReadOnly == undefined) ? false : vReadOnly;
    vDisabled = (vDisabled == undefined) ? true : vDisabled;
    //����Backspace��ʱ���¼�Դ����Ϊ������С������ı��ģ�
    //����readOnly����Ϊtrue��disabled����Ϊtrue�ģ����˸��ʧЧ
    var flag1= ev.keyCode == 8 && (t=="password" || t=="text" || t=="textarea")&& (vReadOnly==true || vDisabled==true);
    //����Backspace��ʱ���¼�Դ���ͷ�������С������ı��ģ����˸��ʧЧ
    var flag2= ev.keyCode == 8 && t != "password" && t != "text" && t != "textarea" ;
    //�ж�
    if(flag2 || flag1)return false;
}


function showtips(action)
{

	if(action)
	{
		$("#showcontent").html(action);
	}else{
		$("#showcontent").html("���ڴ������ݣ����Ժ�...");
	}

	sending.style.display ="";
	sendingbg.style.display ="";
}
function uploadimg(scatid,dateid,fd_cat_id,getvalid,act,refunction,imgid,obj){
	 //alert("���Ե����򣬹رվͿ�����");
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
$("#upload_"+scatid).empty().html('<input type="button" class="thglbtnb" name="uploadfilecolorbox" id="uploadfilecolorbox" value="�ش�" onclick="uploadimg('+scatid+',\''+$("#dateid").val()+'\',\''+arr_value[1]+'\',\'upload_'+scatid+'\',\'edit\',\'refeedback\',\'preuploadfile\')" class="dpszjbszscb"/>');

$("#showimg_"+scatid).html('<input type="button" name="button" onclick=\'parent.$.fn.colorbox({href:"'+arr_value[0]+'"}); return false;\' value="�鿴" class="thglbtnb" />');
$("#showimg_"+scatid).prev().hide();
//$("#showimg_"+scatid).attr('onclick','parent.$.fn.colorbox({href:"'+arr_value[0]+'"}); return false;');
if(scatid==1111){
$("#upload_"+scatid).empty().html('<input type="button" class="thglbtnb" name="uploadfilecolorbox" id="uploadfilecolorbox" value="�ش�" onclick="uploadimg('+scatid+',\''+$("#dateid").val()+'\',\''+arr_value[1]+'\',\'upload_'+scatid+'\',\'edit\',\'refeedback\',\'preuploadfile\')" class="dpszjbszscb"/>');
}else if(scatid==16){
$("#upload_"+scatid).empty().html('<input type="button" name="uploadfilecolorbox" id="uploadfilecolorbox" value="�ش�" onclick="uploadimg(16,\''+$("#dateid").val()+'\',\'\',\'uploadlogo\',\'edit\',\'refeedback\',\'preuploadlogo\')" class="dpszjbszscb"/>');
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
	   $("option",show_province).remove(); //���ԭ�е�ѡ��
       $("option",show_scounty).remove(); //���ԭ�е�ѡ��
	   $("option",show_town).remove(); //���ԭ�е�ѡ��
	   $("option",show_scity).remove(); //���ԭ�е�ѡ��
	   var option = "<option value=''>--��ѡ��--</option>";
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

	   $("option",show_town).remove(); //���ԭ�е�ѡ��
	   $("option",show_scity).remove(); //���ԭ�е�ѡ��
	   var option = "<option value=''>--��ѡ��--</option>";
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
	     $("option",show_stown).remove(); //���ԭ�е�ѡ��
	     var option = "<option value=''>--��ѡ��--</option>";
         show_stown.append(option);
	     $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
             show_stown.append(option);

	    });

		});
}




