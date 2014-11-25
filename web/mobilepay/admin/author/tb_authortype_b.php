<?
$thismenucode = "2k305";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
function getappname($appmnuid)
{
	$db=new DB_test();
	if(!$appmnuid)
	{
		$appmnuid=0;
	}
	$query = "select fd_appmnu_name from tb_appmenu where fd_appmnu_id in($appmnuid)";
	$db->query($query);
	if($db->nf())
	{
	while($db->next_record())
	{
		$appname .= $db->f('fd_appmnu_name')."&nbsp;&nbsp;";
	}
	}
	return $appname;
}

class tb_feedback_b extends browse {
	var $prgnoware = array ("基本资料", "商户分类" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_authortype_id";
	
	var $browse_queryselect = "select * 
	                            from tb_authortype";
	var $browse_edit = "authortype.php?listid=";
	var $browse_editname = "管理";
	var $browse_new = "authortype.php";
	var $browse_delsql = "delete from tb_authortype where fd_authortype_id = '%s'";
	var $browse_field = array ("fd_authortype_no", "fd_authortype_name","fd_authortype_appmnuid" );
	var $browse_find = array (// 查询条件
"0" => array ("商户分类编号", "fd_authortype_no", "TXT" ), "1" => array ("商户分类名", "fd_authortype_name", "TXT" ) );

}

class fd_authortype_no extends browsefield {
	var $bwfd_fdname = "fd_authortype_no"; // 数据库中字段名称
	var $bwfd_title = "商户分类编号
"; // 字段标题
}
class fd_authortype_name extends browsefield {
	var $bwfd_fdname = "fd_authortype_name"; // 数据库中字段名称
	var $bwfd_title = "商户分类名
"; // 字段标题
}
class fd_authortype_appmnuid extends browsefield {
	var $bwfd_fdname = "fd_authortype_appmnuid"; // 数据库中字段名称
	var $bwfd_title = "商户APP功能"; // 字段标题
	function makeshow() { // 将值转为显示值
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getappname ($this->bwfd_value);
		return $this->bwfd_show;
	}
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
