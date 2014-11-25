<?
$thismenucode = "2k403";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("基本资料", "刷卡器分类" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_paycardtype_id";
	
	var $browse_queryselect = "select * 
	                            from tb_paycardtype";
	var $browse_edit = "paycardtype.php?listid=";
	//var $browse_editname = "管理";
	var $browse_new = "paycardtype.php";
	var $browse_delsql = "delete from tb_paycardtype where fd_paycardtype_id = '%s'";
	var $browse_field = array ("fd_paycardtype_no", "fd_paycardtype_name" );
	var $browse_find = array (// 查询条件
"0" => array ("刷卡器分类编号", "fd_paycardtype_no", "TXT" ), "1" => array ("刷卡器分类名", "fd_paycardtype_name", "TXT" ) );

}

class fd_paycardtype_no extends browsefield {
	var $bwfd_fdname = "fd_paycardtype_no"; // 数据库中字段名称
	var $bwfd_title = "刷卡器分类编号
"; // 字段标题
}
class fd_paycardtype_name extends browsefield {
	var $bwfd_fdname = "fd_paycardtype_name"; // 数据库中字段名称
	var $bwfd_title = "刷卡器分类名
"; // 字段标题
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_author_sp_b_bu = new tb_feedback_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限


$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>

