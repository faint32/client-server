<?
$thismenucode = "2k503";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_arrive_b extends browse {
	//var $prgnoware = array ("ˢ��������", "������������" );
	var $prgnoware = array ("��������", "������������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_arrive_id";
	
	var $browse_queryselect = "select * from tb_arrive ";
	
	var $browse_edit = "arrive.php?listid=";
//	var $browse_new = "arrive.php";
	var $browse_delsql = "delete from tb_arrive where fd_arrive_id = '%s'";
	
	var $browse_field = array ("fd_arrive_id", "fd_arrive_name" );
	var $browse_find = array (// ��ѯ����
"0" => array ("���", "fd_arrive_id", "TXT" ), "1" => array ("��������", "fd_arrive_name", "TXT" ) );
}

class fd_arrive_id extends browsefield {
	var $bwfd_fdname = "fd_arrive_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class fd_arrive_name extends browsefield {
	var $bwfd_fdname = "fd_arrive_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_arrive_rates extends browsefield {
	var $bwfd_fdname = "fd_arrive_rates"; // ���ݿ����ֶ�����
	var $bwfd_title = "��׼����"; // �ֶα���
}
class fd_arrive_bear extends browsefield {
	var $bwfd_fdname = "fd_arrive_bear"; // ���ݿ����ֶ�����
	var $bwfd_title = "���ге�"; // �ֶα���
}

if (empty ( $order )) {
	$order = "fd_arrive_id";
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_arrive_b_bu = new tb_arrive_b ( );
$tb_arrive_b_bu->browse_skin = $loginskin;
$tb_arrive_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_arrive_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_arrive_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��


$tb_arrive_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
