<?
header('P3P: CP=CAO PSA OUR'); 
ob_start();
session_start();
include ("../include/config.inc.php");

//------------------清除查找session--------------------
session_unregister("find_whatdofind"); 
session_unregister("find_howdofind");  
session_unregister("find_findwhat"); 
unset($find_whatdofind);
unset($find_howdofind);
unset($find_findwhat);

session_unregister("thismenucode"); 
unset($thismenucode);
//------------------清除查找session--------------------

session_register("loginuser");          // 登陆id，可用于授权用户
session_register("loginname");          // 登陆用户名，可用于授权用户
session_register("logintrueuser");      // 真实登陆id
session_register("logintruename");      // 真实登陆用户名
session_register("loginbrowline");      // 用户brow习惯
session_register("loginruning");        // 用户当前的程序id
session_register("loginusermenu");      // 用户可执行程序
session_register("loginuserqx");        // 用户可执行程序
session_register("logintimes");         // 用户打开的窗口数
session_register("loginlastchecktime"); // 用户最后检查时戳
session_register("loginskin");          // 用户使用的界面风格
session_register("loginoutputfield");   //excel导出字段

session_register("loginstaid");         // 职员id号
session_register("loginstaname");       // 职员姓名

session_register("loginopendate");      // 本月开始日期
session_register("loginonlinetime");    //用户登陆时间

session_register("loginshoworganid");   //查看其他机构的权限
session_register("arr_loginshoworgan"); //查看其他机构的权限
session_register("loginbacktype");      //新增后跳转类型
session_register("loginorganid");       //所属机构
session_register("loginorganno");       //所属机构编号
session_register("loginorganname");     //所属机构名称
session_register("loginorgantype");     //所属机构类型
session_register("loginsdcrid");        //所属配送中心id号
session_register("loginstorageid");     //所属仓库id号
session_register("loginmscompanyid");   //所属公司id号

session_register("loginisinvestor");    //是否股东用户
session_register("loginissuper");       //是否超级用户
session_register("loginareaid");        //所属片区
session_register("loginisshowarea");    //是否有权查看区域
session_register("loginishavezb");      //是否包含总部部门


// 得出用户的职员名,部门,职务，所属机构
$db = new erp_test ;
$query = "select * from tb_teller 
          left join tb_usegroup on fd_usegroup_id = fd_tel_usegroupid
          left join tb_staffer on fd_sta_id = fd_tel_staffer
          where fd_tel_id = '$teller_userid'";
$db->query($query);
if($db->nf()){
  $db->next_record();
  $loginbrowline   = $db->f(fd_tel_browline);
  $loginstaid      = $db->f(fd_tel_staffer);
  $loginuser       = $db->f(fd_tel_id);
  $loginname       = $db->f(fd_tel_name);
  $loginonlinetime = $db->f(fd_tel_onlinetime);
  $loginindextype  = $db->f(fd_tel_pagetype);
  $loginorganid    = $db->f(fd_tel_organid);       //操作机构
  $loginstorageid  = $db->f(fd_tel_storageid);     //操作仓库
  $loginsdcrid     = $db->f(fd_tel_sdcrid);        //操作配送中心
  $showorganid     = $db->f(fd_tel_showorganqx);   //查看的分行
  $loginbacktype   = $db->f(fd_tel_isbackbrowse);  //新增后跳转类型
  $usegrouptype    = $db->f(fd_usegroup_type);     //所属用户组类型
  $loginareaid     = $db->f(fb_tel_areaid);        //所属片区
  $loginmscompanyid= $db->f(fd_tel_mscompanyid);   //所属公司
  $loginisshowarea = $db->f(fd_tel_isshowarea);    //是否有权查看区域    
  $loginishavezb   = $db->f(fd_tel_ishavezb);      //是否包含总部   
  $loginstaname    = $db->f(fd_sta_name); 
  
  $loginskin       = "../skin/skin" . $db->f(fd_tel_skin) . "/" ;  
  
  $loginshoworganid = fun_showorgan($showorganid);  //查看机构权限
  $arr_loginshoworgan = fun_showorganid($showorganid);  //查看机构权限
  
  if($usegrouptype ==0){
  	if($link_issuper==0){
  		$loginissuper   = $link_issuper;
      $loginorganid   = $link_organid;    //所属机构
      $loginorganno   = $link_organno;    //所属机构编号
      $loginorganname = $link_organname;  //所属机构名称
      $loginorgantype = $link_organtype;  //所属机构类型
      $loginsdcrid    = $link_sdcrid;     //所属配送中心
  	}else{
  	  $loginissuper = 1 ;     //是超级用户
  	  $loginorganno   = ""; //所属机构编号
      $loginorganname = ""; //所属机构名称
      $loginorgantype = ""; //所属机构类型
    }
  }else{
    $loginissuper = 0;      //不是超级用户
    $query = "select fd_agency_no , fd_agency_easyname , fd_agency_type
              from tb_agency where fd_agency_id = '$loginorganid' ";
    $db->query($query);
    if($db->nf()){
    	$db->next_record();
    	$loginorganno   = $db->f(fd_agency_no);       //所属机构编号
    	$loginorganname = $db->f(fd_agency_easyname); //所属机构名称
    	$loginorgantype = $db->f(fd_agency_type);     //所属机构类型
    }
  }
  if($link_sdcrid>0){
  	$loginsdcrid    = $link_sdcrid;     //所属配送中心
  }
  $loginusermenu = makeqx($loginuser);   //用户可执行的程序
  //print_r($loginusermenu);
}

$gotourl = $pagename;
Header("Location: $gotourl");

//找出该有的权限
function fun_showorgan($showorganid){
	$arr_organid = explode("±",$showorganid);
	for($i=0;$i<count($arr_organid);$i++){
		$tmporganid = $arr_organid[$i];
		$arr_tmpqx[$tmporganid]=1;
	}
	return $arr_tmpqx;
}

//找出该有的权限
function fun_showorganid($showorganid){
	$arr_organid = explode("±",$showorganid);
	for($i=0;$i<count($arr_organid);$i++){
		$tmporganid = $arr_organid[$i];
		if($tmporganid!=""){
	  	$arr_tmpqx[]=$tmporganid;
	  }
	}
	return $arr_tmpqx;
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
	$db = new erp_test ;
	
	$menufile = "http://www.papersystem.cn/ms2011/include/menuarryfile.php" ;
  $menuarry = file($menufile);

//1001±1±地区设置±ico_main_hr.gif±../basic/tb_area_b.php±0±
//1001代号、1为上级代号、地区设置为名称、ico_main_hr.gif为图片
//../basic/tb_area_b.php连接地址、0为是否为最底

	$query = "select * from tb_teller 
	         left join tb_usegroup on fd_usegroup_id = fd_tel_usegroupid
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