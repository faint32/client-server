<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("��������", "��Ȩˢ�����" );
	var $prgnoware = array ("��������", "��Ȩˢ�����" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_scdmset_id";
	
	var $browse_queryselect = "select * from tb_slotcardmoneyset 
	                           left join tb_authorindustry on fd_auindustry_id = fd_scdmset_auindustryid ";
	var $browse_edit = "slotcardmoneyset.php?listid=";
	var $browse_new = "slotcardmoneyset.php";
	var $browse_delsql = "delete from tb_slotcardmoneyset where fd_scdmset_id = '%s'";
	var $browse_field = array ("fd_scdmset_no","fd_scdmset_name", "fd_auindustry_name","fd_scdmset_scope","fd_scdmset_nallmoney","fd_scdmset_sallmoney","fd_scdmset_everymoney","fd_scdmset_everycounts","fd_scdmset_datetime");
	var $browse_find = array (// ��ѯ����
"0" => array ("�̻�����", "fd_auindustry_name", "TXT" ), "1" => array ("����", "fd_scdmset_mode", "TXT" ), "2" => array ("�������", "fd_scdmset_nallmoney", "TXT" ), "3" => array ("��˶��", "fd_scdmset_sallmoney", "TXT" ) );
		
		function dodelete() { //  ɾ������.
		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			$ishvaeflage = 0;
			$sql="select * from tb_author where fd_author_bkcardscdmsetid='" . $this->browse_check [$i] . "' or fd_author_slotscdmsetid='" . $this->browse_check [$i] . "'";
			$this->db->query ($sql);
			if($this->db->nf()){
				$ishvaeflage=1;
			} 
			if($ishvaeflage==1)
			{
				die ( "<script>alert('���ײ��Ѱ��̻�,����ȡ����!'); window.history.back();</script>" );
			}
			else{
					$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
					$this->db->query ( $query ); //ɾ������ļ�¼
			}
		}
	}
}
class fd_scdmset_no extends browsefield {
	var $bwfd_fdname = "fd_scdmset_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ײͺ�"; // �ֶα���
}
class fd_scdmset_name extends browsefield {
	var $bwfd_fdname = "fd_scdmset_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ײ���"; // �ֶα���
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�����"; // �ֶα���
}
class fd_scdmset_mode extends browsefield {
	var $bwfd_fdname = "fd_scdmset_mode"; // ���ݿ����ֶ�����
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
class fd_scdmset_scope extends browsefield {
	var $bwfd_fdname = "fd_scdmset_scope"; // ���ݿ����ֶ�����
	var $bwfd_title = "ˢ������"; // �ֶα���
	function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {      		
        		case "creditcard":
        		    $this->bwfd_show = "���ÿ�";
        		     break;
        		case "bankcard":
        		    $this->bwfd_show = "���";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_scdmset_nallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_nallmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "�������"; // �ֶα���
	var $bwfd_align ="center";
}
class fd_scdmset_sallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_sallmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "�������"; // �ֶα���
	var $bwfd_align = "center";
}
class fd_scdmset_everymoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_everymoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "ÿ���޶�"; // �ֶα���
	var $bwfd_align ="center";
}
class fd_scdmset_everycounts extends browsefield {
	var $bwfd_fdname = "fd_scdmset_everycounts"; // ���ݿ����ֶ�����
	var $bwfd_title = "����ˢ������"; // �ֶα���
	var $bwfd_align = "center";
}
class fd_scdmset_datetime extends browsefield {
	var $bwfd_fdname = "fd_scdmset_datetime"; // ���ݿ����ֶ�����
	var $bwfd_title = "����޸�ʱ��"; // �ֶα���
	var $bwfd_align ="center";
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

