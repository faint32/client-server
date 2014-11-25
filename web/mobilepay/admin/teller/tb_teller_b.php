<?
$thismenucode = "9101";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class web_teller_b extends browse {
	 var $prgnoware = array("系统管理","用户管理","设置用户");
	 var $prgnowareurl = array("","","");
	 
	 var $browse_key = "fd_tel_id";
 	 var $browse_queryselect = "select fd_tel_id,fd_tel_name,fd_tel_recsts,fd_usegroup_name  from web_teller
 	                            left join web_usegroup on fd_usegroup_id = fd_tel_usegroupid 
 	                           ";
       
	 var $browse_delsql = "delete from web_teller where fd_tel_id = %s" ;
	 var $browse_new = "teller.php" ;
	 var $browse_edit = "teller.php?id=" ;
   

   
	 var $browse_field = array("fd_tel_name","fd_usegroup_name");
	 var $browse_link  = array("lk_view0");

	 var $browse_find = array(		// 查询条件
				"0" => array("用户名", "fd_tel_name","TXT")	,
				"1" => array("用户组", "fd_usegroup_name","TXT")										
			 );
}

class fd_tel_name extends browsefield {
        var $bwfd_fdname = "fd_tel_name";	// 数据库中字段名称
        var $bwfd_title = "用户名";	// 字段标题
}

class fd_usegroup_name extends browsefield {
        var $bwfd_fdname = "fd_usegroup_name";	// 数据库中字段名称
        var $bwfd_title = "用户组";	// 字段标题
}



// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_tel_id","")
   			    );  
   var $bwlk_title ="用户功能";	// link标题
   var $bwlk_prgname = "seltelqx.php?id=";	// 链接程序
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$web_teller_bu = new web_teller_b ;

if($loginuser != 1){
	$web_teller_bu->browse_querywhere = " fd_tel_recsts=0 and fd_tel_id !='1'" ;
}

//if($loginuser =='24'){          
//	$web_teller_bu->browse_querywhere = " fd_tel_recsts=0 " ;
//}else if($loginuser =='20'){
//  $web_teller_bu->browse_querywhere = " fd_tel_recsts=0 and fd_tel_id !='24'" ;
//}else{
//  $web_teller_bu->browse_querywhere = " fd_tel_recsts=0 and (fd_tel_id !='24' and fd_tel_id !='20')" ;
//}


$web_teller_bu->browse_skin = $loginskin ;
$web_teller_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$web_teller_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$web_teller_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$web_teller_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>