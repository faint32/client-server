<?
$thismenucode = "2n106";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bank_b extends browse 
{
	 var $prgnoware    = array("刷卡器管理","银行设置");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_bank_id";
	 
	 var $browse_queryselect = "select * from tb_bank ";
	
	 var $browse_edit   = "bank.php?listid=" ;
	  var $browse_new   = "bank.php" ;
	

	 var $browse_field = array("fd_bank_id","fd_bank_name","fd_bank_rates","fd_bank_bear");
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_bank_id","TXT"),
				"1" => array("银行名称" , "fd_bank_name","TXT"),
				); 
}

class  fd_bank_id  extends browsefield {
        var $bwfd_fdname = "fd_bank_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// 数据库中字段名称
        var $bwfd_title = "银行名称";	// 字段标题
}
class fd_bank_rates  extends browsefield {
        var $bwfd_fdname = "fd_bank_rates";	// 数据库中字段名称
        var $bwfd_title = "标准费率";	// 字段标题
}
class fd_bank_bear  extends browsefield {
        var $bwfd_fdname = "fd_bank_bear";	// 数据库中字段名称
        var $bwfd_title = "银行承担";	// 字段标题
}

if(empty($order)){
	$order = "fd_bank_id";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_bank_b_bu = new tb_bank_b ;
$tb_bank_b_bu->browse_skin = $loginskin ;
$tb_bank_b_bu->browse_delqx = 1;  // 删除权限
$tb_bank_b_bu->browse_addqx = 1;  // 新增权限
$tb_bank_b_bu->browse_editqx = 1;  // 编辑权限

$tb_bank_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
