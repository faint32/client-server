<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("��������", "��Ȩˢ�����" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_paymset_id";
	
	var $browse_queryselect = "select * from tb_paymoneyset 
	                           left join tb_authortype on fd_authortype_id = fd_paymset_authortypeid ";
	var $browse_edit = "paymoneyset.php?listid=";
	var $browse_new = "paymoneyset.php";
	var $browse_delsql = "delete from tb_paymoneyset where fd_paymset_id = '%s'";
	var $browse_field = array ("fd_paymset_no", "fd_authortype_name","fd_paymset_mode","fd_paymset_nallmoney","fd_paymset_sallmoney");
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻�����", "fd_authortype_name", "TXT" ), "1" => array ("����", "fd_paymset_mode", "TXT" ), "2" => array ("�������", "fd_paymset_nallmoney", "TXT" ), "3" => array ("��˶��", "fd_paymset_sallmoney", "TXT" ) );

}
class fd_paymset_no extends browsefield {
	var $bwfd_fdname = "fd_paymset_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ײͺ�"; // �ֶα���
}
class fd_authortype_name extends browsefield {
	var $bwfd_fdname = "fd_authortype_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�����"; // �ֶα���
}
class fd_paymset_mode extends browsefield {
	var $bwfd_fdname = "fd_paymset_mode"; // ���ݿ����ֶ�����
	var $bwfd_title = "�޶�����"; // �ֶα���
	function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {      		
        		case "date":
        		    $this->bwfd_show = "���ײ�";
        		     break;
        		case "month":
        		    $this->bwfd_show = "���ײ�";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_paymset_nallmoney extends browsefield {
	var $bwfd_fdname = "fd_paymset_nallmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "������ȣ���"; // �ֶα���
	var $bwfd_align ="center";
}
class fd_paymset_sallmoney extends browsefield {
	var $bwfd_fdname = "fd_paymset_sallmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "������ȣ���"; // �ֶα���
	var $bwfd_align = "center";
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

