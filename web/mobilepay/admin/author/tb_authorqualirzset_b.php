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
	//var $prgnoware = array ("��������", "�̻�����" );
	var $prgnoware = array ("�̻�����", "�����������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_auqrz_id";
	
	var $browse_queryselect = "select * from tb_authorqualirzset 
	    
	                           left join tb_authortype on fd_authortype_id = fd_auqrz_authortypeid ";
	var $browse_edit = "authorqualirzset.php?listid=";
	var $browse_editname = "�޸�";
	var $browse_new = "authorqualirzset.php";
	var $browse_delsql = "delete from tb_authorqualirzset where fd_auqrz_id = '%s'";
	var $browse_field = array ("fd_auqrz_no", "fd_authortype_name" , "fd_auqrz_auqid");
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻����ʱ��", "fd_auqrz_no", "TXT" ), "1" => array ("�̻�������", "fd_auqrz_auqid", "TXT" ), "2" => array ("�̻���������", "fd_authortype_name", "TXT" ) );

}

class fd_auqrz_no extends browsefield {
	var $bwfd_fdname = "fd_auqrz_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "���
"; // �ֶα���
}
class fd_auqrz_auqid extends browsefield {
	var $bwfd_fdname = "fd_auqrz_auqid"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�����֤����"; // �ֶα���
	function makeshow() { // ��ֵתΪ��ʾֵ
		//$this->var = explode ( ",", $this->bwfd_value );
		$this->bwfd_show =getauqrzname ($this->bwfd_value);
		return $this->bwfd_show;
	}
}
class fd_authortype_name extends browsefield {
	var $bwfd_fdname = "fd_authortype_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�������"; // �ֶα���
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

