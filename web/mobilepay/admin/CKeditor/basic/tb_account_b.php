<?
$thismenucode = "1c614";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("基本资料","发票设定");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_account_id";
	 
	 var $browse_queryselect = "select fd_account_id,fd_account_linkman,fd_account_bank,fd_account_bankno,
	                           fd_account_bankno,fd_sdcr_name,a.fd_fptype_name as afpname,
	                           b.fd_fptype_name as bfpname
	                            from web_account 
	                            left join tb_sendcenter on fd_sdcr_id = fd_account_sdcrid
	                            left join web_fptype as a on a.fd_fptype_id = fd_account_zzsfp
	                            left join web_fptype as b on b.fd_fptype_id = fd_account_ptfp
	                            ";
	var $browse_new    = "account.php" ;
	 var $browse_edit   = "account.php?id=" ;
	 var $browse_delsql = "delete from web_account where fd_account_id = '%s'" ;
	 var $browse_field = array("fd_account_linkman","fd_account_bank","fd_account_bankno","fd_sdcr_name","afpname","bfpname");
 	 var $browse_find = array(		// 查询条件
				"0" => array("开票公司" , "fd_account_linkman","TXT"),
				"1" => array("开户银行" , "fd_account_bank","TXT"),
				"2" => array("银行账号" , "fd_account_bankno","TXT"),
				"3" => array("所属总仓" , "fd_sdcr_name","TXT"),
				"4" => array("增值税发票版类" , "afpname","TXT"),
				"5" => array("普通发票版类" , "bfpname","TXT"),
				); 
}

class fd_account_linkman extends browsefield {
        var $bwfd_fdname = "fd_account_linkman";	// 数据库中字段名称
        var $bwfd_title = "开票公司";	// 字段标题
}

class fd_account_bank extends browsefield {
        var $bwfd_fdname = "fd_account_bank";	// 数据库中字段名称
        var $bwfd_title = "开户银行";	// 字段标题
}

class fd_account_bankno extends browsefield {
        var $bwfd_fdname = "fd_account_bankno";	// 数据库中字段名称
        var $bwfd_title = "银行账号";	// 字段标题
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// 数据库中字段名称
        var $bwfd_title = "所属总仓";	// 字段标题
}

class afpname extends browsefield {
        var $bwfd_fdname = "afpname";	// 数据库中字段名称
        var $bwfd_title = "增值税发票版类";	// 字段标题
}

class bfpname extends browsefield {
        var $bwfd_fdname = "bfpname";	// 数据库中字段名称
        var $bwfd_title = "普通发票版类";	// 字段标题
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;
$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_procatalog_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_procatalog_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
