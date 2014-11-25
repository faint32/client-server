<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_apprules_b extends browse 
{
	 var $prgnoware    = array("客户服务","协议管理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_apprules_id";
	 

	 var $browse_queryselect = "select * from tb_apprules";
	 
	 var $browse_delsql = "delete from tb_apprules where fd_apprules_id = '%s'" ;
	 var $browse_new = "apprules.php" ;
	 var $browse_edit = "apprules.php?id=" ;
   
	 var $browse_field = array("fd_apprules_no","fd_apprules_title","fd_apprules_content");
 	 var $browse_find = array(		// 查询条件
				"0" => array("版本号", "fd_apprules_no","TXT"),
				"1" => array("APP类型", "fd_apprules_title","TXT")
				);
	 
}

class fd_apprules_no extends browsefield {
        var $bwfd_fdname = "fd_apprules_no";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_apprules_title extends browsefield {
        var $bwfd_fdname = "fd_apprules_title";	// 数据库中字段名称
        var $bwfd_title = "标题";	// 字段标题
}


class fd_apprules_content extends browsefield {
        var $bwfd_fdname = "fd_apprules_content";	// 数据库中字段名称
        var $bwfd_title = "内容";	// 字段标题
}





if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_apprules_b_bu = new tb_apprules_b ;
$tb_apprules_b_bu->browse_skin = $loginskin ;
$tb_apprules_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_apprules_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_apprules_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
//$tb_leftmenu_b_bu->browse_link  = array("lk_view0");

$tb_apprules_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

