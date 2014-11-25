javascript:window.history.forward(1);
$(document).ready(function(){
	$.ajaxSetup ({cache: false });

		//获取url参数
		$.getUrlParam = function(name)
		{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r!=null) return unescape(r[2]); return null;
		}


		var urlstorageid=$.getUrlParam('storageid');
		var urlsdcrid=$.getUrlParam('sdcrid');
		var urlagencyid=$.getUrlParam('agencyid');
		var urlpragme;
		if(urlagencyid)
		{
			urlpragme=urlstorageid;
		}else{
			urlpragme=urlsdcrid;
		}
	    $.ajax({
            type: "POST",
            url:"ajaxread/readstartcitycode.php?type=checkareaid&urlstorageid="+urlpragme,
             data: poststr ,
             success: function(strItem){
			 var arr_strItem=strItem.split("@@");
				if(arr_strItem[0] !="0")
				{
					$("#loginarea").html("当前区域:（"+arr_strItem[0]+"）");
					$("#selchinarea[rel!='quickorder']").html(arr_strItem[1]);
				}else{
					var mycity = remote_ip_info['city'] ;
					$.ajax({
					 type: "POST",
					url:"ajaxread/readstartcitycode.php?type=readareaid&cityname="+escape(mycity),
					 data: poststr ,
					 success: function(strItem){
						var arr_strItem=strItem.split("@@");
						$("#loginarea").html("当前区域:（"+arr_strItem[1]+"）");
						$("#selchinarea[rel!='quickorder']").html(arr_strItem[2]);
							/* setCookie("cookie_citycode",arr_strItem[0],365);
							 setCookie("cookie_areaname",arr_strItem[1],365); */
					 }
					});
				}
             }
	        }); //通过用途大类获取分类


	//$("#fixfloat").smartFloat();
	//--- 开始 全部纸类产品 --  //
	 $('#leftmlbox').bind("mouseenter", function(e) {
			$(this).addClass('hoverlm');
			$('div.leftmlbtm').css("display","block");
		    e?e.stopPropagation():event.cancelBubble = true;
			});
  	$('#leftmlbox').live('mouseleave', function() {
	       	$(this).removeClass('hoverlm');
			$('div.leftmlbtm').css("display","none");
			//return false;
			});

	    searchvaliquery = function() {

	       if($("#searchvalue").val()=='搜克重、搜当地地址、搜品牌、搜纸种、搜店铺 ')
		   {
			  var  values = '';
		   }else
		   {
			  var  values = $("#searchvalue").val();
			}
			  //搜索提交，写入数据库
                $.post("ajaxread/addsearchkey.php?keyval="+escape(values),function(){
				location.href="quickorder.php?queryvalue="+values+"&priceorder=asc";
			  })

			}
	$("#leftmlbox").click(function(e) {
	       e?e.stopPropagation():event.cancelBubble = true;
	});
	$(document).click(function() {
		 	$(this).removeClass('hoverlm');
			$('div.leftmlbtm').css("display","none");
	});
	$('div.sidelist').bind("mouseenter", function() {

	   		var rel=$(this).attr("rel");
			var typeid=rel.split("_");
		    $(this).find('h3').addClass('hover');
			$('.i-list').hide();
			var poststr = "typeid="+typeid[1];
			$.ajax({
             type: "POST",
             url : "ajaxread/readnavprocat.php"  ,
             //url : "ajaxread/toptype.php"  ,
             data: poststr ,
             success: function(strItem){
				var strvalue =strItem.split("@@@");
				$("#"+rel).html(strvalue[1]);
             }
	        }); //通过用途大类获取分类
			$(this).find('.i-list').fadeIn();
		    return false;
			});



  	$('div.sidelist,a.iclose').live('mouseleave', function() {
	        //$("#leftmlbox").removeClass('hoverlm');
			//$('div.leftmlbtm').css("display","none");
		    $(this).find('h3').removeClass('hover');
			$(this).find('.i-list').fadeOut();
			return false;
			});
	$('div.lfclistbmt').bind("mouseenter", function() {   //品牌导购鼠标滑动效果
	        var type=$(this).attr("rel");
			$('div.allflbox').css("display","none");
			$(this).find('.allflbox').css("display","block");
			});
	// --- 结束 全部纸类产品  --  //
	$('i.loginip,.reglogin,input.loginipt').live('click', function() {  //登录按钮
			$.cookie('_cururl_',location.href,{expires: 7, path: '/'});
	  		window.location="login.php";
	});
	$('input.regipt').live('click', function() {  //注册按钮
	  		window.location="reg.php";
	});
	$('div.gwcbox').bind("mouseenter", function() {   //购物车显示

		    $(".gwcfcbox").html(""); //清空原有的选项
		    $(".gwcfcbox").append("<img src='images/load.jpg' alt='加载中...' />加载数据...");
			$('.gwcfcbox').show();
     		$('.gwcfcbox').load('ajaxread/readbuycar.php');
	});
    $('div.gwcbox').live('mouseleave', function() {  //鼠标离开购物车隐藏
			$('.gwcfcbox').hide();
			});
    logout = function()
	{
		if(confirm("是否退出登录？")){
		location.href="logout.php";
		}
	}

	//$.cookie('hisArt',null);   //商品浏览记录
	if($.cookie("hisArt")!= null)
	{
		var jsons = eval("("+$.cookie("hisArt")+")");
		var scommid = "";
		for(var i=0; i<jsons.length;i++){
		scommid = "@@" + jsons[i].commid+"@@"+scommid ;
		}
		var url     = "ajaxread/readcookiepro.php";
		var poststr = "commid="+scommid;
    	ajaxposthtml(url,poststr,"readcookiepro");
	}

});
//获取cookie
	function getCookie(c_name){

		if (document.cookie.length>0){

			c_start=document.cookie.indexOf(c_name + "=")
			if (c_start!=-1){

				c_start=c_start + c_name.length+1;

				c_end=document.cookie.indexOf(";",c_start);

				if (c_end==-1) c_end=document.cookie.length;

				return unescape(document.cookie.substring(c_start,c_end))
			}
		}
		return ""
	}

	//设置cookie
	function setCookie(c_name,value,expiredays){

		var exdate=new Date()

		exdate.setDate(exdate.getDate()+expiredays)

		document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
	}
  function enterPress(e) {

        if (e.keyCode == 13) {
            searchvaliquery();
            event.returnValue = false;//阻止自动提交
        }
        }
function showqp(type,agencyid,sdcrid,commid,unitid,other){   //显示添加购物车页面
 		var postStr ="comm="+ commid+"&sdcrid="+sdcrid+"&unitid="+unitid+"&agencyid="+agencyid+"&other="+other;
 	 	parent.$.fn.colorbox({href:"./box/quickprodure-box.php?"+postStr});

	}

function showqp_new(type,agencyid,sdcrid,commid,unitid,other){   //显示添加购物车页面
 		var postStr ="comm="+ commid+"&sdcrid="+sdcrid+"&unitid="+unitid+"&agencyid="+agencyid+"&other="+other;
 	 	parent.$.fn.colorbox({href:"./box/quickprodure-box-new.php?"+postStr});

	}

function uploadimg(scatid,dateid,fd_cat_id,getvalid,act,refunction,imgid,uploadtype){
   var havupload =0;
   var szRef;
   var str;
   $.ajax({
             type: "POST",
             url : "ajaxread/returnglobal.php?scatid="+scatid   ,
             success: function(strItem){
				var  arr_strItem=strItem.split("@@");
             	var g_uppic   	    = arr_strItem[0];
				var g_upbackurl   	= arr_strItem[1];
				    scatid       	= arr_strItem[2];


				str = $("#"+getvalid).val();
				var arr_value
				if(act!='new')
				{
				arr_value= str.split('@@');
				var catid = parseInt(arr_value[1]);
				if(catid!=""){fd_cat_id=parseInt(catid);}
				}

				szRef=g_uppic+"?scatid="+scatid+"&dateid="+dateid+"&act="+act+"&fd_cat_id="+fd_cat_id+"&getvalid="+getvalid+"&cb="+g_upbackurl+"&uploadtype="+uploadtype;
				parent.$.fn.colorbox({href:szRef,iframe:true, innerWidth:800, innerHeight:250,onClosed:function()
				{eval(refunction)(scatid,act,getvalid,imgid);}});

			 }
	   });
}
function retfuncsaler(scatid,act,getvalid,typeid)
{

	 parent.$.fn.colorbox({innerWidth:800, innerHeight:380,href:"salerreg.php?"+$.cookie("salerregvalue")});

}

  function ajaxposthtml(url,poststr,html)
  {
	  $.ajax({
             type: "POST",
             url : url   ,
             data: poststr ,
             success: function(strItem){
				 $("#"+html).html(strItem);
             }
	        });
  }
  $('#selchinarea').live('change', function() {     //加载中国区域
		var value =$(this).val();
		var productrel=$(this).attr('rel');
		//alert(productrel);
	 $.getJSON("ajaxread/readchinaareaprov.php",{chinaareaid:value},function(json){
       var show_scounty = $("#selprov");


       $("option",show_scounty).remove(); //清空原有的选项
	   var option = "<option value=''>--请选择--</option>";

	   var show_sdcrid = $("#ppselectlist");

       $("option",show_sdcrid).remove(); //清空原有的选项
	   if(productrel!="productarea")
	   {
	   $("#jzform").submit();
	     return false;
	   }
	   show_scounty.append(option);
	   $.each(json,function(index,array){

	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
	     show_scounty.append(option);
	    });

		});
		});

	$('#selprov').live('change', function() {     //加载中国区域

		var value =$(this).val();
		var productrel=$(this).attr('rel');

	 $.getJSON("ajaxread/readsdcrprov.php?thisurl="+encodeURIComponent(document.URL),{provinces_code:value},function(json){
      var show_scounty = $("#ppselectlist");

       $("option",show_scounty).remove(); //清空原有的选项

	   if(productrel!="productarea")
	   {
	   $("#jzform").submit();
	   return false;
	   }
	   var option = "<option value=''>--请选择--</option>";
	   show_scounty.append(option);
	   $.each(json,function(index,array){
	     option = "<option value='"+array['storageid']+"' lable='"+array['id']+"' >"+array['title']+"</option>";
	     show_scounty.append(option);


	    });

		});
		});

function show_newprovinces(value){
 // var strvalue=document.getElementById("show_scity").value;
      $.getJSON("ajaxread/readnewprovinces.php",{area_id:value},function(json){
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
      $.getJSON("ajaxread/readnewcity.php",{provinces_code:value},function(json){
      var show_scounty = $("#show_scounty");
	  var show_town    = $("#show_stown");
	   var show_scity  = $("#show_scity");
       $("option",show_scounty).remove(); //清空原有的选项
	   $("option",show_town).remove(); //清空原有的选项
	   $("option",show_scity).remove(); //清空原有的选项
	   var option = "<option value=''>--请选择--</option>";
	   show_scity.append(option);
	   $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
	     show_scity.append(option);
		 $("#showaddresserror").removeClass("valid_right").removeClass("valid_wrong").html("");
	    });

		});
}
function show_newcounty(value){
  $.getJSON("ajaxread/readnewcounty.php",{city_code:value},function(json){
     var show_scounty = $("#show_scounty");
	  var show_town = $("#show_stown");
         $("option",show_scounty).remove(); //清空原有的选项
	     $("option",show_town).remove(); //清空原有的选项
	     var option = "<option value=''>--请选择--</option>";
	     show_scounty.append(option);
	     $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
	     show_scounty.append(option);

	    });

		});
}


function show_newtown(value){
// alert(value);
 // var strvalue=document.getElementById("show_scity").value;
     $.getJSON("ajaxread/readnewtown.php",{county_code:value},function(json){
     var show_scounty = $("#show_stown");
     $("option",show_scounty).remove(); //清空原有的选项
	  var option = "<option value=''>--请选择--</option>";
	   show_scounty.append(option);
	   $.each(json,function(index,array){
	     option = "<option value='"+array['id']+"'>"+array['title']+"</option>";
	     show_scounty.append(option);
	    });

		});
}


$.fn.smartFloat = function() {
	var position = function(element) {
		var top = element.position().top, pos = element.css("position");
		$(window).scroll(function() {
			var scrolls = $(this).scrollTop();
			if (scrolls > top) {
				if (window.XMLHttpRequest) {
					element.css({
						position: "fixed",
						top: 0,
						display:"block"
					}).addClass("fixfloat");
				} else {
					element.css({
						top: scrolls,
						display:"block"
					});
				}
			}else {
				element.css({
					position: pos,
					top: top,
					display:""
				}).removeClass("fixfloat");
			}
		});
};
	return $(this).each(function() {
		position($(this));
	});
};

function isphone(phone){return(new RegExp(/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/).test(phone));}
function isstr(string){return(new RegExp(/^[\u4E00-\u9FA5\uf900-\ufa2d\w\.\s]{6,18}$/).test(string));}
function isidcard(string){return(new RegExp(/^([0-9]{17}[0-9X]{1})|([0-9]{15})$/).test(string));}

 function changeCode(imgId){
   var imgCode = document.getElementById(imgId);
   var date = new Date();
   imgCode.src = "function/ImageR.php?s="+date.getTime()+"&nowtime="+document.getElementById("nowtime").value;
   return;
}

function listclose(){
  $('div.sidelist').trigger('mouseleave');
}
      var code ; //在全局 定义验证码
     function createCode()
     {
       code = "";
       var codeLength = 4;//验证码的长度
       var checkCode = document.getElementById("checkCode");   //获得id为checkcode的值
       var selectChar = new Array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','来');//所有候选组成验证码的字符，当然也可以用中文的

       for(var i=0;i<codeLength;i++)
       {   //获取一个随机数
        var charIndex = Math.floor(Math.random()*36);
       code +=selectChar[charIndex];


       }

       if(checkCode)
       {  //如果这个数存在
         checkCode.className="code";
         checkCode.value = code;
       }

     }

function salercheck(memid){
	$.ajax({ url: "ajaxread/salercheck.php",
		     cache:false,
		     success: function(data){
               if(data){
				  location.href ="salermall.php?"+data;
				  return false;
			   }else{
				  parent.$.fn.colorbox({href:"salerreg.php?mnull=1&memid="+memid,innerWidth:800});
				  return false;
			   }
             }
		   });
   return false;
}

function parameskip(navname,field,parame)
{

   if(field=='produre')
   {
	   window.open("product.php?produreid="+parame+"");
   }else
   {
	    window.open("quickorder.php?searchvalue="+parame+"&priceorder=");
   }
}


	function delCookie(name){

		var exp = new Date();

		exp.setTime(exp.getTime() - 1);

		var cval=getCookie(name);

		if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();

	}

function loadFle(filename,ftype)
{
    var oHead = document.getElementsByTagName('HEAD').item(0);
	if(ftype=='js'){
		var oScript= document.createElement("script");
		oScript.type = "text/javascript";
		oScript.src=filename;
	}else{
		var oScript= document.createElement("link");
		oScript.type = "text/css";
		oScript.rel = "stylesheet";
		oScript.href=filename;
	}
    oHead.appendChild(oScript);
}
function winresize() {
    if ($(window).width() > 990) {
        $('.w9tbox').css({'width':'100%','text-align':'left'}); // 头box
        $('.xhyzcnavb,.xhyzcnavm').css({'width':'100%'});
        $('.w9tboxm,.w9tboxbm').css({'width':'97%','padding':'0 20px','margin':'0 0'}); //头里面的
        $('.xhyzconmain,.spglscspbt').css({'width':'100%'}); //中间部分最大的box
        $('.xhyzconmb').css({'width':'98%','margin-left':'15px','display':'inline'}); //中间部分
        //$('.xhyzconmbl').css({/*'position':'absolute',*/'float':'none','left':'15px'});//菜单
        $('.xhyzcnavmul').css({'width':'auto'}); //菜单ul自适应
        var _width = $(window).width()-203;
        $('.xhyzconmbr,.hyzxcontr,.spglscspbox').css({'width':_width,'float':'left','margin-left':'10px'}); //ritght
        $('.wdglconr').css({'width':_width,'float':'left','margin-left':'5px','overflow':'visible'}); //ritght
        $('.hywdscrbox').css({'width':'96%'}); //right 里面的
        $('.hyzjllbox').css({'width':'99%'}); //right 里面的
        $('.hyzxctrtb').css({'width':'99%'}); //right 里面的
        $('.hyzxtxmr,.hyaqjbb,.hyxfbb,.hyjxzd,.hyjxdd').css({'width':'98%'}); //right 里面的

        $('.jrljsqbr').css({'float':'left'});

        $('.hywdscrbox').find('h5').css({'width':'100%'}); //h5
        $('.xhyzddxxb').css({'width':'97%'}); // 蓝条
        $('.xhyzggm').css({'width':'97%'}); //table box
        $('.w9ssbox').css({'float':'right'}); //搜索框
		$('.xdmxbox').css({'width':'97%'});
    }else{
        $('.w9tbox').css({'width':'990px','text-align':'left'}); // 头box
        $('.xhyzcnavb,.xhyzcnavm').css({'width':'990px'});
        $('.w9tboxm,.w9tboxbm').css({'width':'990px','padding':'0','margin':'0 auto'}); //头里面的
        $('.xhyzconmain').css({'width':'990px'}); //中间部分最大的box
        $('.xhyzconmb').css({'width':'990px','margin-left':'15px','display':'inline'}); //中间部分
        //$('.xhyzconmbl').css({/*'position':'absolute',*/'float':'none','left':'15px'});//菜单
        $('.xhyzcnavmul').css({'width':'auto'}); //菜单ul自适应
        $('.xhyzconmbr').css({'width':'828px','float':'left','margin-left':'10px'}); //ritght
        $('.xhyzddxxb').css({'width':'97%'}); // 蓝条
        $('.xhyzggm').css({'width':'97%'}); //table box
        $('.w9ssbox').css({'float':'right'}); //搜索框
		$('.xdmxbox').css({'width':'97%'});
    }
}