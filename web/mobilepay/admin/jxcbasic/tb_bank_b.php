<?
$thismenucode = "2k106";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bank_b extends browse {
	//var $prgnoware = array ("ˢ��������", "��������" );
	var $prgnoware = array ("��������","��������" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_bank_id";
	
	var $browse_queryselect = "select * from tb_bank ";
	
	var $browse_edit = "bank.php?listid=";
	var $browse_new = "bank.php";
		 var $browse_delsql = "delete from tb_bank where fd_bank_id = '%s'" ;
		 
	var $browse_field = array ("fd_bank_id", "fd_bank_name", "fd_bank_activemobilesms", "fd_bank_smsphone","fd_bank_active" );
	var $browse_find = array (// ��ѯ����
"0" => array ("���", "fd_bank_id", "TXT" ), "1" => array ("��������", "fd_bank_name", "TXT" ) );
}

class fd_bank_id extends browsefield {
	var $bwfd_fdname = "fd_bank_id"; // ���ݿ����ֶ�����
	var $bwfd_title = "���"; // �ֶα���
}
class fd_bank_name extends browsefield {
	var $bwfd_fdname = "fd_bank_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_bank_activemobilesms extends browsefield {
	var $bwfd_fdname = "fd_bank_activemobilesms"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ�֧���ֻ����ж��Ź���"; // �ֶα���
	function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "��";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_bank_smsphone extends browsefield {
	var $bwfd_fdname = "fd_bank_smsphone"; // ���ݿ����ֶ�����
	var $bwfd_title = "���Ͷ��ŵĺ���"; // �ֶα���
}
class fd_bank_active extends browsefield {
	var $bwfd_fdname = "fd_bank_active"; // ���ݿ����ֶ�����
	var $bwfd_title = "�Ƿ񼤻�"; // �ֶα���
	function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "��";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "��";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
if (empty ( $order )) {
	$order = "fd_bank_id";
}

if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_bank_b_bu = new tb_bank_b;
$tb_bank_b_bu->browse_skin = $loginskin;
$tb_bank_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_bank_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_bank_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��


$tb_bank_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
				
?>
