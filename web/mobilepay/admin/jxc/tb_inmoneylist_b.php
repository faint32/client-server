<?
$thismenucode = "2k218";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_inmoneylist_b extends browse 
{
	 var $prgnoware    = array("刷卡器购销","收款办理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_inmylt_id";
	 
	 var $browse_queryselect = "select * from tb_inmoneylist
	                            left join tb_account  on fd_account_id   = fd_inmylt_accountid 
	                            ";
	 var $browse_delsql = "delete from tb_inmoneylist where fd_inmylt_id = '%s'";
	 var $browse_new    = "inmoneylist.php" ;
	 var $browse_edit   = "inmoneylist.php?listid=" ;
  
	 var $browse_field = array("fd_inmylt_no","fd_inmylt_date","fd_inmylt_clientno","fd_inmylt_clientname","fd_inmylt_dealwithman","fd_inmylt_money");
 	 var $browse_find = array(		// 查询条件
				"0" => array("单据编号", "fd_inmylt_no"          ,"TXT"),				
				"1" => array("单据日期", "fd_inmylt_date"        ,"TXT"), 
				"2" => array("收款人"  , "fd_inmylt_dealwithman" ,"TXT"),
				"3" => array("往来单位", "fd_inmylt_clientname"  ,"TXT")
				);
}
class fd_inmylt_no extends browsefield {
        var $bwfd_fdname = "fd_inmylt_no";	// 数据库中字段名称
        var $bwfd_title = "单据编号";	// 字段标题
}

class fd_inmylt_clientno extends browsefield {
        var $bwfd_fdname = "fd_inmylt_clientno";	// 数据库中字段名称
        var $bwfd_title = "往来单位编号";	// 字段标题
}

class fd_inmylt_clientname extends browsefield {
        var $bwfd_fdname = "fd_inmylt_clientname";	// 数据库中字段名称
        var $bwfd_title = "往来单位名称";	// 字段标题
}

class fd_inmylt_date extends browsefield {
        var $bwfd_fdname = "fd_inmylt_date";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}

class fd_inmylt_dealwithman extends browsefield {
        var $bwfd_fdname = "fd_inmylt_dealwithman";	// 数据库中字段名称
        var $bwfd_title = "收款人";	// 字段标题
}

class fd_inmylt_money extends browsefield {
        var $bwfd_fdname = "fd_inmylt_money";	// 数据库中字段名称
        var $bwfd_title = "金额";	// 字段标题
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_inmylt_date";
	$upordown = "desc";
}

$tb_inmoneylist_b_bu = new tb_inmoneylist_b ;
$tb_inmoneylist_b_bu->browse_skin = $loginskin ;
$tb_inmoneylist_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_inmoneylist_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_inmoneylist_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_inmoneylist_b_bu->browse_querywhere = "fd_inmylt_state = '0' ";

$tb_inmoneylist_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
