<?
$thismenucode = "2k113";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("��������", "��Ʒ����" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_producttype_id";
	
	var $browse_queryselect = "select * from tb_producttype";
	var $browse_edit = "producttype.php?listid=";
	var $browse_editname = "����";
	var $browse_new = "producttype.php";
	var $browse_delsql = "delete from tb_producttype where fd_producttype_id = '%s'";
	var $browse_field = array ("fd_producttype_no", "fd_producttype_name" );
	var $browse_find = array (// ��ѯ����
"0" => array ("��Ʒ������", "fd_producttype_no", "TXT" ), "1" => array ("��Ʒ������", "fd_producttype_name", "TXT" ) );

}

class fd_producttype_no extends browsefield {
	var $bwfd_fdname = "fd_producttype_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "��Ʒ������
"; // �ֶα���
}
class fd_producttype_name extends browsefield {
	var $bwfd_fdname = "fd_producttype_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��Ʒ������
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

