<?
$thismenucode = "2k105";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_customertype_b extends browse 
{
	 var $prgnoware    = array("基本设置","代理商类型");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_customertype_id";
	 
	 var $browse_queryselect = "select * from tb_customertype ";
	 var $browse_delsql = "delete from tb_customertype where fd_customertypetomertype_id = '%s'" ;
	 var $browse_new    = "customertype.php" ;
	 var $browse_edit   = "customertype.php?id=" ;
	 
	 var $browse_field = array("fd_customertype_no","fd_customertype_name");
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_customertype_no","TXT"),
				"1" => array("代理商类型名称"   , "fd_customertype_name","TXT"),
				);
}

class fd_customertype_no extends browsefield {
        var $bwfd_fdname = "fd_customertype_no";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}


class fd_customertype_name extends browsefield {
        var $bwfd_fdname = "fd_customertype_name";	// 数据库中字段名称
        var $bwfd_title = "代理商类型名称";	// 字段标题
}





if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_customertype_b_bu = new tb_customertype_b ;
$tb_customertype_b_bu->browse_skin = $loginskin ;
$tb_customertype_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_customertype_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_customertype_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_customertype_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
