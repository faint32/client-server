function refeedback(scatid,dateid,act,getvalid,imgid)
{	
	
	var strItems
	  	strItems = $("#"+getvalid).val();
	var arr_value =new Array();
	 	arr_value= strItems.split('@@');
	var content;
	var content1;
	if(strItems=="" && act!="delete")  
	{
		return false;
	}
	switch(act)
	{
		case 'new':
			content='<img src="'+arr_value[0]+'" />';
			content1='<a href="#" onclick=uploadimg("'+scatid+'","'+dateid+'","'+arr_value[1]+'","uploadfile","edit","refeedback","preuploadfile",this); class="kpzjxgt">�޸�</a> |  <a href="#" onclick=uploadimg("'+dateid+'","'+dateid+'","'+arr_value[1]+'","uploadfile","delete","refeedback","preuploadfile",this); class="kpzjxsc">ɾ��</a>';
		      $("#"+getvalid+"_img").html(content);
			  $("#"+getvalid+"_a").html(content1);
			  break;
		case 'edit':
		content='<img src="'+arr_value[0]+'" />';
		content1='<a href="#" onclick=uploadimg("'+scatid+'","'+dateid+'","'+arr_value[1]+'","uploadfile","edit","refeedback","preuploadfile",this);  class="kpzjxgt">�޸�</a> |  <a href="#" onclick=uploadimg("'+dateid+'","'+dateid+'","'+arr_value[1]+'","uploadfile","delete","refeedback","preuploadfile",this);  class="kpzjxsc">ɾ��</a>';
		$("#"+getvalid+"_img").html(content);
		$("#"+getvalid+"_a").html(content1);
			break;
		case "delete":
		content='<img src="moren.jpg" />';
		content1='<a href="#" onclick=uploadimg("'+scatid+'","'+dateid+'","'+arr_value[1]+'","uploadfile","new","refeedback","preuploadfile",this);  class="kpzjxgt">�ϴ�</a>';
		$("#"+getvalid+"_img").html(content);
		$("#"+getvalid+"_a").html(content1);
		break;
	}
}
function submit_save() {
	 
	 if(form1.fd_ggwgl_link.value==""){
	 	  alert("���������ӵ�ַ��");
	 	  form1.name.focus();
	 	  return;
	 }	
	form1.submit(); 
}
function sub_delete()
{
if (!confirm("�Ƿ�ɾ���ü�¼��")) {
     return false;   
    }
}