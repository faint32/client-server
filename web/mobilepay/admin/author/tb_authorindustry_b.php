<?
$thismenucode = "2k311";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("��������", "�̻���ҵ" );
	var $prgnoware = array ("�̻�����", "�̻���ҵ����" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_auindustry_id";
	
	var $browse_queryselect = "select * 
	                            from tb_authorindustry";
	var $browse_edit = "authorindustry.php?listid=";
	var $browse_editname = "����";
	var $browse_new = "authorindustry.php";
	var $browse_delsql = "delete from tb_authorindustry where fd_auindustry_id = '%s'";
	var $browse_field = array ("fd_auindustry_no", "fd_auindustry_name" );
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻���ҵ���", "fd_auindustry_no", "TXT" ), "1" => array ("�̻���ҵ��", "fd_auindustry_name", "TXT" ) );

}

class fd_auindustry_no extends browsefield {
	var $bwfd_fdname = "fd_auindustry_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻���ҵ���
"; // �ֶα���
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻���ҵ��
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
