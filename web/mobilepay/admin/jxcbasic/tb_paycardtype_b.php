<?
$thismenucode = "2k403";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("��������", "ˢ��������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_paycardtype_id";
	
	var $browse_queryselect = "select * 
	                            from tb_paycardtype";
	var $browse_edit = "paycardtype.php?listid=";
	//var $browse_editname = "����";
	var $browse_new = "paycardtype.php";
	var $browse_delsql = "delete from tb_paycardtype where fd_paycardtype_id = '%s'";
	var $browse_field = array ("fd_paycardtype_no", "fd_paycardtype_name" );
	var $browse_find = array (// ��ѯ����
"0" => array ("ˢ����������", "fd_paycardtype_no", "TXT" ), "1" => array ("ˢ����������", "fd_paycardtype_name", "TXT" ) );

}

class fd_paycardtype_no extends browsefield {
	var $bwfd_fdname = "fd_paycardtype_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "ˢ����������
"; // �ֶα���
}
class fd_paycardtype_name extends browsefield {
	var $bwfd_fdname = "fd_paycardtype_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "ˢ����������
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

