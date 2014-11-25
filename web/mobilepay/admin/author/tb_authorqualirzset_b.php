<?
$thismenucode = "2k307";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

function getauqrzname($auqid)
{
	$db=new DB_test();
	if(!$auqid)
	{
		$auqid=0;
	}
	$query = "select fd_auq_name from tb_authorquali where fd_auq_id in($auqid)";
	$db->query($query);
	if($db->nf())
	{
	while($db->next_record())
	{
		$auqname .= $db->f('fd_auq_name')."&nbsp;&nbsp;";
	}
	}
	return $auqname;
}
class tb_feedback_b extends browse {
	//var $prgnoware = array ("基本资料", "商户资质" );
	var $prgnoware = array ("商户管理", "提款申请资质" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_auqrz_id";
	
	var $browse_queryselect = "select * from tb_authorqualirzset 
	    
	                           left join tb_authortype on fd_authortype_id = fd_auqrz_authortypeid ";
	var $browse_edit = "authorqualirzset.php?listid=";
	var $browse_editname = "修改";
	var $browse_new = "authorqualirzset.php";
	var $browse_delsql = "delete from tb_authorqualirzset where fd_auqrz_id = '%s'";
	var $browse_field = array ("fd_auqrz_no", "fd_authortype_name" , "fd_auqrz_auqid");
	var $browse_find = array (// 查询条件
"0" => array ("商户资质编号", "fd_auqrz_no", "TXT" ), "1" => array ("商户资质名", "fd_auqrz_auqid", "TXT" ), "2" => array ("商户类型名名", "fd_authortype_name", "TXT" ) );

}

class fd_auqrz_no extends browsefield {
	var $bwfd_fdname = "fd_auqrz_no"; // 数据库中字段名称
	var $bwfd_title = "编号
"; // 字段标题
}
class fd_auqrz_auqid extends browsefield {
	var $bwfd_fdname = "fd_auqrz_auqid"; // 数据库中字段名称
	var $bwfd_title = "商户资质证书名"; // 字段标题
	function makeshow() { // 将值转为显示值
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getauqrzname ($this->bwfd_value);
		return $this->bwfd_show;
	}
}
class fd_authortype_name extends browsefield {
	var $bwfd_fdname = "fd_authortype_name"; // 数据库中字段名称
	var $bwfd_title = "商户类型名"; // 字段标题
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

