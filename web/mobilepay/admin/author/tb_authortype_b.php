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
	var $prgnoware = array ("��������", "�̻�����" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_authortype_id";
	
	var $browse_queryselect = "select * 
	                            from tb_authortype";
	var $browse_edit = "authortype.php?listid=";
	var $browse_editname = "����";
	var $browse_new = "authortype.php";
	var $browse_delsql = "delete from tb_authortype where fd_authortype_id = '%s'";
	var $browse_field = array ("fd_authortype_no", "fd_authortype_name","fd_authortype_appmnuid" );
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻�������", "fd_authortype_no", "TXT" ), "1" => array ("�̻�������", "fd_authortype_name", "TXT" ) );

}

class fd_authortype_no extends browsefield {
	var $bwfd_fdname = "fd_authortype_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�������
"; // �ֶα���
}
class fd_authortype_name extends browsefield {
	var $bwfd_fdname = "fd_authortype_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�������
"; // �ֶα���
}
class fd_authortype_appmnuid extends browsefield {
	var $bwfd_fdname = "fd_authortype_appmnuid"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�APP����"; // �ֶα���
	function makeshow() { // ��ֵתΪ��ʾֵ
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getappname ($this->bwfd_value);
		return $this->bwfd_show;
	}
}
if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_author_sp_b_bu = new tb_feedback_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��


$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
