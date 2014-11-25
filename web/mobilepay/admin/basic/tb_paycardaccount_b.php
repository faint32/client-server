<?
$thismenucode = "2k105";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("基本资料","刷卡器公户管理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_paycardaccount_id";
	 

	 var $browse_queryselect = "select * 
	                            from tb_paycardaccount";
	 var $browse_edit = "paycardaccount.php?listid=" ;
	 var $browse_editname   = "管理" ;
	 var $browse_new= "paycardaccount.php";
	 var $browse_delsql = "delete from tb_paycardaccount where fd_paycardaccount_id = '%s'" ;
	 var $browse_field = array("fd_paycardaccount_company","fd_paycardaccount_accountname","fd_paycardaccount_accountnum","fd_paycardaccount_bank");
 	 var $browse_find = array(		// 查询条件
				"0" => array("所属公司", "fd_paycardaccount_company","TXT"),
				"1" => array("账户", "fd_paycardaccount_accountname","TXT"),
				"2" => array("帐号", "fd_paycardaccount_accountnum","TXT"),
				"3" => array("开户行", "fd_paycardaccount_bank","TXT"),					
				);
	 
}

class fd_paycardaccount_company extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_company";	// 数据库中字段名称
        var $bwfd_title = "所属公司
";	// 字段标题
}
class fd_paycardaccount_accountname extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_accountname";	// 数据库中字段名称
        var $bwfd_title = "账户
";	// 字段标题
}

class fd_paycardaccount_accountnum extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_accountnum";	// 数据库中字段名称
        var $bwfd_title = "帐号
";	// 字段标题
}

class fd_paycardaccount_bank extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_bank";	// 数据库中字段名称
        var $bwfd_title = "开户行
";	// 字段标题
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_author_sp_b_bu = new tb_feedback_b ;
$tb_author_sp_b_bu->browse_skin = $loginskin ;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3];  // 删除权限
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1];  // 新增权限
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2];  // 编辑权限

$tb_author_sp_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

