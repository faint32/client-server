
   function CheckAll(form){
     for (var i=0;i<form.elements.length;i++){ 
         var e = form.elements[i];
         if (e.name != 'chkall')   e.checked = form.chkall.checked;
     }
   }
   


function submit_del(){
    if (confirm("�Ƿ�ɾ���ü�¼��")) {
         
      
	    if(requestSubmitted == true){
      alert("���Ѿ��ύ����ȴ�һ��");
      return (false);
   } 
       requestSubmitted = true;   
        sending.style.display="";
        sendingbg.style.display="";
        form1.toaction.value = "delete";
        form1.submit();
        
    }
} 


function help(){  
   if(document.getElementById("helpid").style.pixelHeight == 0 &&  document.getElementById("helpid").style.display == "none"){      
       document.getElementById("helpid").style.display = "";
	  ChS();
   }else{   
	   HhS();    	  
   }
}

function ChS(){
if(helpid.style.pixelHeight<250){helpid.style.pixelHeight+=10;setTimeout("ChS()",0.5);
} 
}
function HhS(){
if(helpid.style.pixelHeight > 0){helpid.style.pixelHeight-=10;setTimeout("HhS()",0.5);
}
if(helpid.style.pixelHeight == 0){
 document.getElementById("helpid").style.display = "none";
 }
}


function InitAjax()
{
��var ajax=false; 
��try { 
����ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
��} catch (e) { 
����try { 
������ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
����} catch (E) { 
������ajax = false; 
����} 
��} 
��if (!ajax && typeof XMLHttpRequest!='undefined') { 
����ajax = new XMLHttpRequest(); 
��} 
��return ajax;
}


function show_city(){
  var postStr = "provinces_code="+ form1.provinces.value;
��//��Ҫ����Ajax��URL��ַ
��var url = "../ajaxread/readcity.php";
��//ʵ����Ajax����
��var ajax = InitAjax();
��//ʹ��POST��ʽ��������
��ajax.open("POST", url, true); 
  //���崫����ļ�HTTPͷ��Ϣ
��ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
��//����POST����
��ajax.send(postStr);
��//��ȡִ��״̬
��ajax.onreadystatechange = function() {
����//���ִ����״̬��������ô�Ͱѷ��ص����ݸ�ֵ������ָ���Ĳ�
����if (ajax.readyState == 4 && ajax.status == 200) {

		 document.getElementById("show_city").innerHTML="<select name='city' onChange='show_county()'><option value=''>�ؼ���</option>"+ajax.responseText+"</select>&nbsp;";
	  }
	}
}


function show_county(){
  var postStr = "city_code="+ form1.city.value;
��//��Ҫ����Ajax��URL��ַ
��var url = "../ajaxread/readcounty.php";
��//ʵ����Ajax����
��var ajax = InitAjax();
��//ʹ��POST��ʽ��������
��ajax.open("POST", url, true); 
  //���崫����ļ�HTTPͷ��Ϣ
��ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
��//����POST����
��ajax.send(postStr);
��//��ȡִ��״̬
��ajax.onreadystatechange = function() {
����//���ִ����״̬��������ô�Ͱѷ��ص����ݸ�ֵ������ָ���Ĳ�
����if (ajax.readyState == 4 && ajax.status == 200) {

		 document.getElementById("show_county").innerHTML="<select name='county'><option value=''>�����ؼ��С���</option>"+ajax.responseText+"</select>";
	  }
	}
}

function checkcard(){

var postStr = "mcardno="+ form1.mcardno.value+"&listid="+form1.listid.value;
 
��//��Ҫ����Ajax��URL��ַ
��var url = "../ajaxread/checkcard.php";
��//ʵ����Ajax����
��var ajax = InitAjax();
��//ʹ��POST��ʽ��������
��ajax.open("POST", url, true); 
  //���崫����ļ�HTTPͷ��Ϣ
��ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
��//����POST����
��ajax.send(postStr);
��//��ȡִ��״̬
��ajax.onreadystatechange = function() {
����//���ִ����״̬��������ô�Ͱѷ��ص����ݸ�ֵ������ָ���Ĳ�
����if (ajax.readyState == 4 && ajax.status == 200) {

		if(ajax.responseText==0){
			alert("�û�Ա�������ڻ���ʧЧ��");
			form1.mcardid.value = "";
			form1.mcardno.value = "";
			form1.dgman.value = "";
		}else if(ajax.responseText==1){
			alert("�û�Ա����Ч��");
		}else{
			var arritem = ajax.responseText.split("@@@");
			form1.mcardid.value = arritem[0];
			form1.mcardno.value = arritem[1];
			form1.dgman.value = arritem[2];
		}
	  }
	}	
}
