<?
$thismenucode = "2k306";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("��������", "�̻�����" );
	var $prgnoware = array ("�̻�����", "�̻���������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_auq_id";
	
	var $browse_queryselect = "select * 
	                            from tb_authorquali";
	var $browse_edit = "authorquali.php?listid=";
	var $browse_editname = "����";
	var $browse_new = "authorquali.php";
	var $browse_delsql = "delete from tb_authorquali where fd_auq_id = '%s'";
	var $browse_field = array ("fd_auq_no", "fd_auq_name" );
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻����ʱ��", "fd_auq_no", "TXT" ), "1" => array ("�̻�������", "fd_auq_name", "TXT" ) );

}

class fd_auq_no extends browsefield {
	var $bwfd_fdname = "fd_auq_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻����ʱ��
"; // �ֶα���
}
class fd_auq_name extends browsefield {
	var $bwfd_fdname = "fd_auq_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�����֤����
"; // �ֶα���
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

