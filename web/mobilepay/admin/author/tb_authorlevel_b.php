<?
$thismenucode = "2k308";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("��������", "�̻��ȼ�" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_authorlevel_id";
	
	var $browse_queryselect = "select * 
	                            from tb_authorlevel";
	var $browse_edit = "authorlevel.php?listid=";
	var $browse_editname = "����";
	var $browse_new = "authorlevel.php";
	var $browse_delsql = "delete from tb_authorlevel where fd_authorlevel_id = '%s'";
	var $browse_field = array ("fd_authorlevel_no", "fd_authorlevel_name" );
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻��ȼ����", "fd_authorlevel_no", "TXT" ), "1" => array ("�̻��ȼ���", "fd_authorlevel_name", "TXT" ) );

}

class fd_authorlevel_no extends browsefield {
	var $bwfd_fdname = "fd_authorlevel_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻��ȼ����
"; // �ֶα���
}
class fd_authorlevel_name extends browsefield {
	var $bwfd_fdname = "fd_authorlevel_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻��ȼ���
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
