<?
$thismenucode = "2k502";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("��������","�̻���������");
	var $prgnoware = array ("��������","�̻���������");
	var $prgnowareurl = array (
		"",
		""
	);

	var $browse_key = "fd_payfset_id";

	var $browse_queryselect = "select * from tb_payfeeset 
	                           left join tb_authorindustry on fd_auindustry_id = fd_payfset_auindustryid
	                          ";
	var $browse_edit = "payfeeset.php?listid=";
	var $browse_editname = "�޸�";
	var $browse_new = "payfeeset.php";
	var $browse_delsql = "delete from tb_payfeeset where fd_payfset_id = '%s'";
	var $browse_field = array (
		"fd_payfset_no",
		"fd_auindustry_name",
		"fd_payfset_scope",
		"fd_payfset_fixfee",
		"fd_payfset_fee",
		"fd_payfset_minfee",
		"fd_payfset_maxfee",
		"fd_payfset_datetime"
	);
	var $browse_find = array (// ��ѯ����
	"0" => array (
			"�̻�����",
			"fd_auindustry_name",
			"TXT"
		)
		
	);
			
		function dodelete() { //  ɾ������.
		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			$ishvaeflage = 0;
			$sql="select * from tb_author where fd_author_bkcardpayfsetid='" . $this->browse_check [$i] . "' or fd_author_slotpayfsetid='" . $this->browse_check [$i] . "'";
			$this->db->query ($sql);
			if($this->db->nf()){
				$ishvaeflage=1;
			} 
			if($ishvaeflage==1)
			{
				die ("<script>alert('���ײ��Ѱ��̻�,����ȡ����!'); window.history.back();</script>" );
			}
			else{
					$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
					$this->db->query ( $query ); //ɾ������ļ�¼
			}
		}
	}

}
class fd_payfset_no extends browsefield {
	var $bwfd_fdname = "fd_payfset_no"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ײͺ�"; // �ֶα���
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̻�����"; // �ֶα���
}
class fd_payfset_scope extends browsefield {
	var $bwfd_fdname = "fd_payfset_scope"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
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
class fd_paycardtype_name extends browsefield {
	var $bwfd_fdname = "fd_paycardtype_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "ˢ��������"; // �ֶα���
}
class fd_arrive_name extends browsefield {
	var $bwfd_fdname = "fd_arrive_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_payfset_mode extends browsefield {
	var $bwfd_fdname = "fd_payfset_mode"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_payfset_fixfee extends browsefield {
	var $bwfd_fdname = "fd_payfset_fixfee"; // ���ݿ����ֶ�����
	var $bwfd_title = "�̶�����"; // �ֶα���
	var $bwfd_align = "center";
}
class fd_payfset_fee extends browsefield {
	var $bwfd_fdname = "fd_payfset_fee"; // ���ݿ����ֶ�����
	var $bwfd_title = "��ȡ����"; // �ֶα���
	var $bwfd_align = "center";
}
class fd_payfset_minfee extends browsefield {
	var $bwfd_fdname = "fd_payfset_minfee"; // ���ݿ����ֶ�����
	var $bwfd_title = "��ͷ��ʶ�"; // �ֶα���
	var $bwfd_align = "center";
}
class fd_payfset_maxfee extends browsefield {
	var $bwfd_fdname = "fd_payfset_maxfee"; // ���ݿ����ֶ�����
	var $bwfd_title = "��߷��ʶ�"; // �ֶα���
	var $bwfd_align = "center";
}
class fd_payfset_datetime extends browsefield {
	var $bwfd_fdname = "fd_payfset_datetime"; // ���ݿ����ֶ�����
	var $bwfd_title = "����޸�ʱ��"; // �ֶα���
	var $bwfd_align = "center";
}
if (isset ($pagerows)) { // ��ʾ����
	$pagerows = min($pagerows, 100); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_author_sp_b_bu = new tb_feedback_b();
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx[3]; // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = $thismenuqx[1]; // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = $thismenuqx[2]; // �༭Ȩ��

$tb_author_sp_b_bu->main($now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition);
?>

