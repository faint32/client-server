
   function CheckAll(form){
     for (var i=0;i<form.elements.length;i++){ 
         var e = form.elements[i];
         if (e.name != 'chkall')   e.checked = form.chkall.checked;
     }
   }
   


function submit_del(){
    if (confirm("是否删除该记录？")) {
         
      
	    if(requestSubmitted == true){
      alert("你已经提交，请等待一下");
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


function show_city(){
  var postStr = "provinces_code="+ form1.provinces.value;
　//需要进行Ajax的URL地址
　var url = "../ajaxread/readcity.php";
　//实例化Ajax对象
　var ajax = InitAjax();
　//使用POST方式进行请求
　ajax.open("POST", url, true); 
  //定义传输的文件HTTP头信息
　ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　//发送POST数据
　ajax.send(postStr);
　//获取执行状态
　ajax.onreadystatechange = function() {
　　//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
　　if (ajax.readyState == 4 && ajax.status == 200) {

		 document.getElementById("show_city").innerHTML="<select name='city' onChange='show_county()'><option value=''>地级市</option>"+ajax.responseText+"</select>&nbsp;";
	  }
	}
}


function show_county(){
  var postStr = "city_code="+ form1.city.value;
　//需要进行Ajax的URL地址
　var url = "../ajaxread/readcounty.php";
　//实例化Ajax对象
　var ajax = InitAjax();
　//使用POST方式进行请求
　ajax.open("POST", url, true); 
  //定义传输的文件HTTP头信息
　ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　//发送POST数据
　ajax.send(postStr);
　//获取执行状态
　ajax.onreadystatechange = function() {
　　//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
　　if (ajax.readyState == 4 && ajax.status == 200) {

		 document.getElementById("show_county").innerHTML="<select name='county'><option value=''>区、县级市、县</option>"+ajax.responseText+"</select>";
	  }
	}
}

function checkcard(){

var postStr = "mcardno="+ form1.mcardno.value+"&listid="+form1.listid.value;
 
　//需要进行Ajax的URL地址
　var url = "../ajaxread/checkcard.php";
　//实例化Ajax对象
　var ajax = InitAjax();
　//使用POST方式进行请求
　ajax.open("POST", url, true); 
  //定义传输的文件HTTP头信息
　ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　//发送POST数据
　ajax.send(postStr);
　//获取执行状态
　ajax.onreadystatechange = function() {
　　//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
　　if (ajax.readyState == 4 && ajax.status == 200) {

		if(ajax.responseText==0){
			alert("该会员卡不存在或已失效！");
			form1.mcardid.value = "";
			form1.mcardno.value = "";
			form1.dgman.value = "";
		}else if(ajax.responseText==1){
			alert("该会员卡有效！");
		}else{
			var arritem = ajax.responseText.split("@@@");
			form1.mcardid.value = arritem[0];
			form1.mcardno.value = arritem[1];
			form1.dgman.value = arritem[2];
		}
	  }
	}	
}
