<?php
session_start();
include ("../include/config.inc.php");
require("../login/autologin.php");
require ("../include/get.variable.inc.php");

if($state == 1){
 $username = it;
 $password = md5(1111111111);
}else{
$username = $username;
$password = md5($password);

}
$loginreturn = autologin($username,$password);

//下面是出错信息的数组
$arr_errmsg[2]="密码错误!(请注意区分大小写)";
$arr_errmsg[3]="由于密码错误多次,禁止使用该用户!";
$arr_errmsg[4]="该用户被停用!";
$arr_errmsg[9]="该用户不存在!";

if ($loginreturn == 1) {
    session_register("loginuser"); // 登陆id，可用于授权用户
    session_register("loginname"); // 登陆用户名，可用于授权用户
    session_register("logintrueuser"); // 真实登陆id
    session_register("logintruename"); // 真实登陆用户名
    session_register("loginbrowline"); // 用户brow习惯
    session_register("loginruning"); // 用户当前的程序id
    session_register("loginusermenu"); // 用户可执行程序
    session_register("loginuserqx"); // 用户可执行程序
    session_register("logintimes"); // 用户打开的窗口数
    session_register("loginlastchecktime"); // 用户最后检查时戳
    session_register("loginskin"); // 用户使用的界面风格
    
    session_register("loginpartid"); // 分行ID
    session_register("loginpartname"); // 分行ID
    
    session_register("loginstaid"); // 职员id号
    session_register("loginstaname"); // 职员姓名
   /* session_register("loginshopman"); // 店员姓名
    session_register("loginshopmanid"); // 店员id号
    session_register("loginshopname"); // 商店名
    session_register("loginshopid"); // 商店id号
    */
    //session_register("loginopendate"); // 本月开始日期
    session_register("loginonlinetime"); //用户登陆时间
    
    session_register("loginindextype"); //用户主页显示类型
    
    session_register("lastprgcode"); // 用户最后使用的程序
    $loginlastchecktime = mktime() ;
    //$loginuser = $authdo->user;
    $loginname = $username;
    $logintimes = 0;
    
   

    //$logintrueuser = $loginuser ; // 真实登陆id
    //$logintruename = $loginname ; // 真实登陆用户名
     
    // 得出用户的职员名,部门,职务，所属机构
    $db = new DB_test ;
    $query = "select * from web_teller      
              where fd_tel_name = '$loginname'";
    $db->query($query);
    $db->next_record();
    $loginbrowline   = $db->f(fd_tel_browline) ;
    $logintelorg     = $db->f(fd_tel_org) ;
    $loginstaid      = $db->f(fd_tel_staffer);
    $loginuser       = $db->f(fd_tel_id);
    $loginonlinetime = $db->f(fd_tel_onlinetime);
    $loginindextype  = $db->f(fd_tel_pagetype);

    
    $chgpass = $db->f(fd_tel_chgpass) ; // 是否需要修改密码
    $loginskin = "../skin/skin" . $db->f(fd_tel_skin) . "/" ;
    
    $query = "select * from tb_staffer 
              where fd_sta_id = '$loginstaid' ";
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$loginstaname   = $db->f(fd_sta_name);
    }
     
    $loginusermenu = makeqx($loginuser);   //用户可执行的程序

    if ($chgpass == 1) {   //判断是否已经修改过密码，如果没有修改密码就转到修改页面。
        Header("Location: ../updatepass/updatepass.php");
        exit ;
    } 
    Header("Location: ../loadpopup.htm");
} else {
?>
<HTML><HEAD><TITLE>用户登录</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<link href="../skin/skin0/button_css.css" rel="stylesheet" type="text/css">
<link href="../skin/skin0/page_title.css" rel="stylesheet" type="text/css">
<LINK href="../skin/skin0/browse.css" type=text/css rel=stylesheet>
<BODY  leftMargin=0 topMargin=0 >
<center>
<table width="450" border="0" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="450" height="150">&nbsp;
	</td>
  </tr>
  <tr>
    <td width="450" >
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="" >
        <tr > 
          <TD id="tdmove"  width="80" class="inputtitle" align=center> 
             出错信息
          </TD>
          <TD id="tdmove"  class="inputtitleright" width="24"> 
          </td>
           <td  id="tdmove" class="titlefont">
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </TD>
        </tr>
      </TABLE>
	</td>
  </tr>
  <tr>
    <td height="100" class="titlefont">
	<BLOCKQUOTE>
              <DIV id=base><BR>
                出错原因：<?php echo $arr_errmsg[$loginreturn] ?>
	</DIV></BLOCKQUOTE></td>
  </tr>
  <tr>
    <td height="34"  valign="middle" align="center" class="bottombotton">
	<INPUT class=buttonsmall onclick="javascript:window.history.back()" type=button value="返回"></td>
  </tr>
</table>
</center>
</BODY></HTML>
<?php
} 

//找出该有的权限
function muqx($qxfp_arr,$arr_prgfcode){
	    
	while(list($key,$val)=@each($qxfp_arr)){
		$arr_tmpqx[$key][item]=1;  //本功能权限
		$level = $arr_prgfcode[$key][level];
		$i=$level;
		$code = $key;
		while($i>0){
			$code = $arr_prgfcode[$code][fcode];
			$arr_tmpqx[$code][item]=1;  //该功能的上一级权限
			if($arr_prgfcode[$code][level]==1){
				$i=1;
			}
			$i--;
		}
	}

	return $arr_tmpqx;
}

function makeqx($loginuser){
	global $loginuserqx;
	$db = new DB_test ;
	
	$menufile = "../include/menuarryfile.php" ;
  $menuarry = file($menufile);
  
//  $query = "select * from tb_menu where fd_menu_upcode != '0' order by fd_menu_sno asc";
//  $db->query($query);
//  if($db->nf()){
//    while($db->next_record()){
//    	   $menu_code    = $db->f(fd_menu_code);
//    	   $menu_upcode  = $db->f(fd_menu_upcode);
//    	   $menu_jpg     = $db->f(fd_menu_jpg);
//    	   $menu_name    = $db->f(fd_menu_name);
//    	   $menu_url     = $db->f(fd_menu_url);
//    	   $menu_hz      = $db->f(fd_menu_hz);
//
//    	   $menuarry[]   = $menu_code."±".$menu_upcode."±".$menu_name."±".$menu_jpg."±".$menu_url.$menu_hz;
//    }
//  }


//1001±1±地区设置±ico_main_hr.gif±../basic/tb_area_b.php±0±
//1001代号、1为上级代号、地区设置为名称、ico_main_hr.gif为图片
//../basic/tb_area_b.php连接地址、0为是否为最底

	$query = "select * from web_teller 
	         left join web_usegroup on fd_usegroup_id = fd_tel_usegroupid
	         where fd_tel_id = '$loginuser'";
  $db->query($query);
  if($db->nf()){
  	$db->next_record();
  	$str_quanxian = $db->f(fd_tel_doprg);  //该用户所拥有的权限
  	$str_groupqx  = $db->f(fd_usegroup_qx);  //所属于组权限

  	if(!empty($str_groupqx)){   //组权限
  			$tmp_gorupqx = explode("±",$str_groupqx);
  			for($i=0;$i<count($tmp_gorupqx);$i++){
  				 $tmp_zqxfp = explode("@",$tmp_gorupqx[$i]);
  		     $zqxfp=$tmp_zqxfp[0];
  		     $arr_qxfp[$zqxfp]=1;   //可执行程序
  		     
  		     if(!empty($tmp_zqxfp[1])){   //可执行权限
	  			    $str_optqx=explode("^",$tmp_zqxfp[1]);    //进一步拆分新增等功能
	  			    for($k=0;$k<count($str_optqx);$k++){
	  			     	$tmpoptqx = $str_optqx[$k];      //新增等权限代号
	  				    $loginuserqx[$zqxfp][$tmpoptqx]=1;    //可执行权限
	  			    }
	  		   }
  	    }
    }
    if(!empty($str_quanxian)){   //组权限
  			$tmp_gorupqx = explode("±",$str_quanxian);
  		
  			for($i=0;$i<count($tmp_gorupqx);$i++){
  				
  				 $tmp_zqxfp = explode("@",$tmp_gorupqx[$i]);
  		     $zqxfp=$tmp_zqxfp[0];
  		     $arr_qxfp[$zqxfp]=1;   //可执行程序
  		     
  		     if(!empty($tmp_zqxfp[1])){   //可执行权限
	  			    $str_optqx=explode("^",$tmp_zqxfp[1]);    //进一步拆分新增等功能
	  			    for($k=0;$k<count($str_optqx);$k++){
	  			     	$tmpoptqx = $str_optqx[$k];      //新增等权限代号
	  				    $loginuserqx[$zqxfp][$tmpoptqx]=1;    //可执行权限
	  			    }
	  		   }
  	    }
    }
  	$temp_arrqx = explode("±",$str_quanxian);   
  }
  for($i=0;$i<count($temp_arrqx);$i++){
  	$tmp_qxfp = explode("@",$temp_arrqx[$i]);
  	$qxfp=$tmp_qxfp[0];
  	$arr_qxfp[$qxfp]=1;
  }

//---------------------------------------
  for($i=0;$i<count($menuarry);$i++){
	   $temp_arr1 = $menuarry[$i] ;
    	
     $temp_arr2 = explode("±",$temp_arr1);
     $tmpcode = $temp_arr2[0];
     if(empty($temp_arr2[1])){
			   $arr_prgfcode[$tmpcode][fcode] = 0 ;
	   }else{
			   $arr_prgfcode[$tmpcode][fcode] = $temp_arr2[1];
	   }
	   $arr_prgfcode[$tmpcode][isunder] = $temp_arr2[5];
    $arr_prgfcode[$tmpcode][level] = $temp_arr2[6];
   }//-------------------------------------

   //找出该有的程序
   $arr_tmpqx = muqx($arr_qxfp,$arr_prgfcode);

   return $arr_tmpqx;

}

?>