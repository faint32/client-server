<?
$thismenucode = "9102";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class web_usegroup_b extends browse {
	 var $prgnoware = array("系统管理","用户组管理","设置用户组");
	 var $prgnowareurl = array("","","");
	 
	 var $browse_key = "fd_usegroup_id";
 	 var $browse_queryselect = "select * from web_usegroup";

	 //var $browse_delsql = "delete from web_usegroup where fd_usegroup_id = %s" ;
	 var $browse_new = "usegroup.php" ;
	 var $browse_edit = "usegroup.php?id=" ;

	 var $browse_field = array("fd_usegroup_name","fd_usegroup_memo");
	 var $browse_link  = array("lk_view0");

	 var $browse_find = array(		// 查询条件
				"1" => array("组名", "fd_usegroup_name","TXT"),
				"2" => array("备注", "fd_usegroup_memo","TXT") 										
			 );
}

class fd_usegroup_name extends browsefield {
        var $bwfd_fdname = "fd_usegroup_name";	// 数据库中字段名称
        var $bwfd_title = "组名";	// 字段标题
}

class fd_usegroup_memo extends browsefield {
        var $bwfd_fdname = "fd_usegroup_memo";	// 数据库中字段名称
        var $bwfd_title = "备注";	// 字段标题        
}


// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_usegroup_id","")
   			    );  
   var $bwlk_title ="用户组功能";	// link标题
   var $bwlk_prgname = "selgroupqx.php?id=";	// 链接程序
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$web_usegroup_bu = new web_usegroup_b ;
$web_usegroup_bu->browse_skin = $loginskin ;
$web_usegroup_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$web_usegroup_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$web_usegroup_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$web_usegroup_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>