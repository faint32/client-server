javascript:window.history.forward(1); 

function killerrors() { 
return true; 
}
window.onerror = killerrors;	
function KeyFilter(type)
{
	var berr=false;
	
	switch(type)
	{
		case 'date':
			if (!(event.keyCode == 45 || event.keyCode == 47 || (event.keyCode>=48 && event.keyCode<=57)))
				berr=true;
			break;
		case 'number':
			if (!(event.keyCode>=48 && event.keyCode<=57))
				berr=true;
			break;
		case 'cy':
			if (!(event.keyCode == 46 || (event.keyCode>=48 && event.keyCode<=57)))
				berr=true;
			break;
		case 'long':
			if (!(event.keyCode == 45 || (event.keyCode>=48 && event.keyCode<=57)))
				berr=true;
			break;
		case 'double':
			if (!(event.keyCode == 45 || event.keyCode == 46 || (event.keyCode>=48 && event.keyCode<=57)))
				berr=true;
			break;
		default:
			if (event.keyCode == 35 || event.keyCode == 37 || event.keyCode==38)
				berr=true;
	}
	return !berr;
}

function getParentFromSrc(src,parTag)
{
	if(src && src.tagName!=parTag)
		src=getParentFromSrc(src.parentElement,parTag);
	return src;
}

function switchToOption(sel,newOption,byWhat)
{
	newOption=newOption.toString();
	if(newOption && sel && sel.tagName=="SELECT")
	{
		newOption=trim(newOption);
		var opts=sel.options;
		for(var i=0;i<opts.length;i++)
		{
			if(trim(opts[i][byWhat].toString())==newOption)
			{
				sel.selectedIndex=i;
				break;
			}
		}
	}
}

// Is a element visible?
function isElementVisible(src)
{
	if(src)
	{
		var x=getOffsetLeft(src)+2-document.body.scrollLeft;
		var y=getOffsetTop(src)+2-document.body.scrollTop;
		if(ptIsInRect(x,y,0,0,document.body.offsetWidth,document.body.offsetHeight))
		{
			var e=document.elementFromPoint(x,y);
			return src==e;
		}
	}
			
	return false;
}

function ptIsInRect(x,y,left,top,right,bottom)
{
	return (x>=left && x<right) && (y>=top && y<bottom);
}

function getOffsetLeft(src){
	var set=0;
	if(src)
	{
		if (src.offsetParent)
			set+=src.offsetLeft+getOffsetLeft(src.offsetParent);
		
		if(src.tagName!="BODY")
		{
			var x=parseInt(src.scrollLeft,10);
			if(!isNaN(x))
				set-=x;
		}
	}
	return set;
}
function getOffsetTop(src){
	var set=0;
	if(src)
	{
		if (src.offsetParent)
			set+=src.offsetTop+getOffsetTop(src.offsetParent);
		
		if(src.tagName!="BODY")
		{
			var y=parseInt(src.scrollTop,10);
			if(!isNaN(y))
				set-=y;
		}
	}
	return set;
}

function isAnyLevelParent(src,par)
{
	var hr=false;
	if(src==par)
		hr=true;
	else if(src!=null)
		hr=isAnyLevelParent(src.parentElement,par);
	
	return hr;
}

function isIE(version)
{
	var i0=navigator.appVersion.indexOf("MSIE")
	var i1=-1;
	var ver=0;
	if(i0>=0)
	{
		i1=navigator.appVersion.indexOf(" ",i0+1);
		if(i1>=0)
		{
			i0=i1;
			i1=navigator.appVersion.indexOf(";",i0+1);
			if(i1>=0)
			{
				ver=parseFloat(navigator.appVersion.substring(i0+1,i1));
				if(isNaN(ver))
					ver=0;
			}
		}
	}
	
	return (navigator.userAgent.indexOf("MSIE")!= -1 
		&& navigator.userAgent.indexOf("Windows")!=-1 
		&& ((ver<(version+1) && ver>=version) || version==0));
}

function getValidDate(str)
{
	var sDate=str.replace(/\//g,"-");
	var vArr=sDate.split("-");
	var sRet="";
	
	if(vArr.length>=3)
	{
		var year=parseInt(vArr[0],10);
		var month=parseInt(vArr[1],10);
		var day=parseInt(vArr[2],10);
		if(!(isNaN(year) || isNaN(month) || isNaN(day)))
			if(year>=1900 && year<9999 && month>=1 && month<=12)
			{
				var dt=new Date(year,month-1,day);
				year=dt.getFullYear();
				month=dt.getMonth()+1;
				day=dt.getDate();
				sRet=year+"-"+(month<10?"0":"")+month+"-"+(day<10?"0":"")+day;
			}
	}
	
	return sRet;
}

function getSafeValue(val,def)
{
	if(typeof(val)=='undefined' || val==null)
		return def;
	else
		return val;
}
function  loadingok(){

/*if(typeof(parent.parent.frames[0].form1.loadingok.value)=='undefined' ){
	//parent.parent.frames[0].form1.loadingok.value  = "";
	parent.parent.frames[1].loadingbg.style.display ="none";
}*/

//parent.parent.frames[1].loadingbg.style.display ="none";
//parent.parent.frames[0].form1.loadingok.value  = "";

}
document.onload = loadingok();

function nextedit(){
form1.action.value="nextedit";
 form1.submit(); 
}

function prvedit(){
form1.action.value="prvedit";
 form1.submit(); 
}

function formhead(){
window.location.href= "#";
}
function lasthead(){
window.location.href= "#inputpro";
}

function MoveLayer(AdLayer) {
var x = 10;//浮动广告层固定于浏览器的x方向位置
var y = 300;//浮动广告层固定于浏览器的y方向位置
var diff = (document.body.scrollTop + y - document.all.AdLayer.style.posTop)*.40;
var y = document.body.scrollTop + y - diff;
eval("document.all." + AdLayer + ".style.posTop = y");
eval("document.all." + AdLayer + ".style.posLeft = x");//移动广告层
setTimeout("MoveLayer('AdLayer');", 20);//设置20毫秒后再调用函数MoveLayer()
}
function oncontextmenu() 
{
	

if(document.selection.type == "None" && document.activeElement.tagName != "INPUT" && document.activeElement.tagName!= "TEXTAREA"){
  return false; 
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
 function openWindowCanClose(url,title,width,height,state) 
{ 
    try{ 
    var dt=new Date(); 
    var iframeId="dailogIframe_"+dt.getHours()+""+dt.getMinutes()+""+dt.getSeconds()+""+Math.random(); 
    var ifame_html="<iframe id='"+iframeId+"' src='"+url+"' style='BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; WIDTH: 98%; BORDER-BOTTOM: 0px; HEIGHT: 98%' frameBorder='0' scrolling='auto'></iframe>";  
    var win=new Ext.Window({ 
                    title: title, 
                    closable:true, 
                    width:width, 
                    height:height, 
                    border:false, 
                    plain:true, 
                    closeAction:'close',                  
                    items: [ 
                    new Ext.Panel({   
                    height:height-30,  
                    html:ifame_html                    
                         }) 
                    ] 
                }); 
         win.show(); 
		 if(state==0)
		 {
		 	win.close();
		 }
		 win.on("close",function(p) 
         { 
            document.getElementById(iframeId).src=""; 
            //document.removeChild(document.getElementById(iframeId));  
         });  
         return win; 
         }catch(e){alert(e);} 
}
function  readuppermoney(money)   //预收款日期更改
{
	//alert(money);
   var postStr = "money="+money;
　  //需要进行Ajax的URL地址
　  var url = "../ajaxread/readuppermoney.php";
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
      // alert(ajax.responseText);
       document.getElementById("UpperMoney").innerHTML =ajax.responseText;
	
　  　} 
　  }
	
}
function showTab(tabHeadId,tabContentId) 
{
	//alert(tabContentId);
  var tabDiv = document.getElementById("tabDiv_tab");
  var taContents = tabDiv.childNodes;
  for(i=0; i<taContents.length; i++) 
  {
	  
    if(taContents[i].id!=null && taContents[i].id != 'tabsHead_tab')
     {
         taContents[i].style.display = 'none';
     }
 }
  document.getElementById(tabContentId).style.display = 'block';
  var tabHeads = document.getElementById('tabsHead_tab').getElementsByTagName('a');
  for(i=0; i<tabHeads.length; i++) 
  {                 
    tabHeads[i].className='tabs'; 
   }
   document.getElementById(tabHeadId).className='curtab';
  // alert(document.getElementById(tabHeadId).className);
   document.getElementById(tabHeadId).blur();
   //setTabdata(tabContentId);
}
function  readuppermoney2(money,byid)   //预收款日期更改
{
	//alert(money);
   var postStr = "money="+money;
　  //需要进行Ajax的URL地址
　  var url = "../ajaxread/readuppermoney.php";
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
      // alert(ajax.responseText);
       document.getElementById(byid).innerHTML =ajax.responseText;
	
　  　} 
　  }
	
}

