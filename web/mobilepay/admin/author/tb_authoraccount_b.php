<?
$thismenucode = "2k310";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("��ԱǮ������" );
	var $prgnoware = array (
		"�̻�����",
		"�̻��ʽ��˻�"
	);
	var $prgnowareurl = array (
		"",
		""
	);

	var $browse_key = "fd_acc_id";

	var $browse_queryselect = "select * from tb_authoraccount
		                            left join tb_author on fd_author_id = fd_acc_authorid
		                            ";
	var $browse_edit = "authoraccount.php?listid=";
	var $browse_editname = "�鿴";
	var $browse_field = array (
		"fd_acc_id",
		"fd_author_truename",
		"fd_acc_money"
	);
	var $browse_find = array (// ��ѯ����
	"0" => array (
			"���",
			"fd_acc_id",
			"TXT"
		),
		"1" => array (
			"�û���",
			"fd_author_truename",
			"TXT"
		),
		"1" => array (
			"���",
			"fd_acc_money",
			"TXT"
		)
	);

}

class fd_acc_id extends browsefield {
	var $bwfd_fdname = "fd_acc_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}

class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // ���ݿ����ֶ�����
	var $bwfd_title = "�û���"; // �ֶα���
}
class fd_acc_typeno extends browsefield {
	var $bwfd_fdname = "fd_acc_typeno"; // ���ݿ����ֶ�����
	var $bwfd_title = "�˻�����"; // �ֶα���
}
class fd_acc_typename extends browsefield {
	var $bwfd_fdname = "fd_acc_typename"; // ���ݿ����ֶ�����
	var $bwfd_title = "�˻�������"; // �ֶα���
}
class fd_acc_money extends browsefield {
	var $bwfd_fdname = "fd_acc_money"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}

if (isset ($pagerows)) { // ��ʾ����
	$pagerows = min($pagerows, 100); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_feedback_b_bu = new tb_feedback_b();
$tb_feedback_b_bu->browse_skin = $loginskin;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3]; // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1]; // ����Ȩ��
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2]; // �༭Ȩ��
$tb_feedback_b_bu->main($now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition);
?>

