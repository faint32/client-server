<?
$thismenucode = "2k511";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("��������", "�̻�����Ԥ������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_warnlevel_id";
	
	var $browse_queryselect = "select * from tb_warnlevel
								left join tb_authorindustry on fd_auindustry_id=fd_warnlevel_typeid
								 ";
	var $browse_edit = "poswarn.php?listid=";
	var $browse_editname = "�޸�";
	var $browse_new = "poswarn.php";
	var $browse_delsql = "delete from tb_warnlevel where fd_warnlevel_id = '%s'";
	var $browse_field = array ("fd_warnlevel_id","fd_warnlevel_level", "fd_auindustry_name","fd_warnlevel_creditcard",
								"fd_warnlevel_cashcard","fd_warnlevel_postnum","fd_warnlevel_average","fd_warnlevel_scale" );
	var $browse_find = array (// ��ѯ����
							"0" => array ("Ԥ��������", "fd_warnlevel_id", "TXT" ), 
							"1" => array ("�̻�����", "fd_auindustry_name", "TXT" ) );

}
class fd_warnlevel_id extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class fd_warnlevel_level extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_level"; // ���ݿ����ֶ�����
	var $bwfd_title = "Ԥ������"; // �ֶα���
			function makeshow() {	// ��ֵתΪ��ʾֵ
		switch ($this->bwfd_value) {
			case "highest":
				$this->bwfd_show = "<font color='#FF0000'>����</font>";
				 break;       		
			case "high":
				$this->bwfd_show = "<font color='#00CC33'>��</font>";
				 break;
			case "middle":
				$this->bwfd_show = "<font color='#00CC66'>��</font>";
				 break;       		
			case "low":
				$this->bwfd_show = "<font color='#339999'>��</font>";
				 break;        		 								
	  }
		  return $this->bwfd_show ;
	}
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�����"; // �ֶα���

}
class fd_warnlevel_creditcard extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_creditcard"; // ���ݿ����ֶ�����
	var $bwfd_title = "���ÿ�ˢ���ܽ���/�£�"; // �ֶα���
}
class fd_warnlevel_cashcard extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_cashcard"; // ���ݿ����ֶ�����
	var $bwfd_title = "���ˢ���ܽ���/�£�"; // �ֶα���
}
class fd_warnlevel_postnum extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_postnum"; // ���ݿ����ֶ�����
	var $bwfd_title = "POSˢ����������/�£�"; // �ֶα���
}
class fd_warnlevel_average extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_average"; // ���ݿ����ֶ�����
	var $bwfd_title = "ƽ��ÿ��ˢ����Ԫ/�ʣ�"; // �ֶα���
}
class fd_warnlevel_scale extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_scale"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ÿ�ˢ��ռ�ȣ�%��"; // �ֶα���
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
