<?
$thismenucode = "2k306";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("基本资料", "商户资质" );
	var $prgnoware = array ("商户管理", "商户资质设置" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_auq_id";
	
	var $browse_queryselect = "select * 
	                            from tb_authorquali";
	var $browse_edit = "authorquali.php?listid=";
	var $browse_editname = "管理";
	var $browse_new = "authorquali.php";
	var $browse_delsql = "delete from tb_authorquali where fd_auq_id = '%s'";
	var $browse_field = array ("fd_auq_no", "fd_auq_name" );
	var $browse_find = array (// 查询条件
"0" => array ("商户资质编号", "fd_auq_no", "TXT" ), "1" => array ("商户资质名", "fd_auq_name", "TXT" ) );

}

class fd_auq_no extends browsefield {
	var $bwfd_fdname = "fd_auq_no"; // 数据库中字段名称
	var $bwfd_title = "商户资质编号
"; // 字段标题
}
class fd_auq_name extends browsefield {
	var $bwfd_fdname = "fd_auq_name"; // 数据库中字段名称
	var $bwfd_title = "商户资质证书名
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

