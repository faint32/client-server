<?
$thismenucode = "2k503";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_arrive_b extends browse {
	//var $prgnoware = array ("刷卡器管理", "到帐周期设置" );
	var $prgnoware = array ("基础设置", "到帐周期设置" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_arrive_id";
	
	var $browse_queryselect = "select * from tb_arrive ";
	
	var $browse_edit = "arrive.php?listid=";
//	var $browse_new = "arrive.php";
	var $browse_delsql = "delete from tb_arrive where fd_arrive_id = '%s'";
	
	var $browse_field = array ("fd_arrive_id", "fd_arrive_name" );
	var $browse_find = array (// 查询条件
"0" => array ("编号", "fd_arrive_id", "TXT" ), "1" => array ("到帐周期", "fd_arrive_name", "TXT" ) );
}

class fd_arrive_id extends browsefield {
	var $bwfd_fdname = "fd_arrive_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
}
class fd_arrive_name extends browsefield {
	var $bwfd_fdname = "fd_arrive_name"; // 数据库中字段名称
	var $bwfd_title = "到帐周期"; // 字段标题
}
class fd_arrive_rates extends browsefield {
	var $bwfd_fdname = "fd_arrive_rates"; // 数据库中字段名称
	var $bwfd_title = "标准费率"; // 字段标题
}
class fd_arrive_bear extends browsefield {
	var $bwfd_fdname = "fd_arrive_bear"; // 数据库中字段名称
	var $bwfd_title = "银行承担"; // 字段标题
}

if (empty ( $order )) {
	$order = "fd_arrive_id";
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_arrive_b_bu = new tb_arrive_b ( );
$tb_arrive_b_bu->browse_skin = $loginskin;
$tb_arrive_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_arrive_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_arrive_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限


$tb_arrive_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
