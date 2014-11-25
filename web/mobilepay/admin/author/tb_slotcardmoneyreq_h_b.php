<?
$thismenucode = "2k510";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");



class tb_slotcardmoneyreq_sp_b extends browse {
	var $prgnoware = array ("��������", "���������ʷ" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_pmreq_id";
	//var $browse_edit  = "slotcardmoneyreq_sp.php?listid=" ;
  //var $browse_editname = "����";
	var $browse_queryselect = "select * from tb_slotcardmoneyreq 
								left join tb_author on fd_author_id = fd_pmreq_authorid 
	                           left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid ";
	var $browse_field = array ("fd_pmreq_reqno","fd_author_truename","fd_scdmset_name",
								"fd_scdmset_scope","fd_scdmset_nallmoney","fd_scdmset_sallmoney",
								"fd_pmreq_reqmoney" ,"fd_pmreq_repmoney", "fd_pmreq_reqdatetime","fd_pmreq_state");
	var $browse_find = array (// ��ѯ����
							"0" => array ("������", "fd_pmreq_reqno", "TXT" ),
							"1" => array ("�����û�", "fd_author_truename", "TXT" ),
							"2" => array ("�ײ�", "fd_scdmset_name", "TXT" ) ,
							"3" => array ("������", "fd_scdmset_scope", "TXT" ) ,
							);

}

class fd_pmreq_reqno extends browsefield {
	var $bwfd_fdname = "fd_pmreq_reqno"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // ���ݿ����ֶ�����
	var $bwfd_title = "�����û�"; // �ֶα���
}
class fd_scdmset_name extends browsefield {
	var $bwfd_fdname = "fd_scdmset_name"; // ���ݿ����ֶ�����
	var $bwfd_title = "�ײ�"; // �ֶα���
}
class fd_scdmset_scope extends browsefield {
	var $bwfd_fdname = "fd_scdmset_scope"; // ���ݿ����ֶ�����
	var $bwfd_title = "������"; // �ֶα���
	 function makeshow() {	// ��ֵתΪ��ʾֵ
		switch($this->bwfd_value){
			case "creditcard":
			  $this->bwfd_show = "���ÿ�";
			  break;
			case "bankcard":
			  $this->bwfd_show = "���";
			  break; 
		}
		  return $this->bwfd_show;
	}
}
class fd_scdmset_nallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_nallmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "������ȣ���/�£�"; // �ֶα���
}
class fd_scdmset_sallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_sallmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "���������ȣ���/�£�"; // �ֶα���
}
class fd_pmreq_reqmoney extends browsefield {
	var $bwfd_fdname = "fd_pmreq_reqmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "���������ȣ���/�£�"; // �ֶα���
}

class fd_pmreq_repmoney extends browsefield {
	var $bwfd_fdname = "fd_pmreq_repmoney"; // ���ݿ����ֶ�����
	var $bwfd_title = "�������"; // �ֶα���
} 

class fd_pmreq_reqdatetime extends browsefield {
	var $bwfd_fdname = "fd_pmreq_reqdatetime"; // ���ݿ����ֶ�����
	var $bwfd_title = "��������"; // �ֶα���
}
class fd_pmreq_state extends browsefield {
	var $bwfd_fdname = "fd_pmreq_state"; // ���ݿ����ֶ�����
	var $bwfd_title = "״̬"; // �ֶα���
	 function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch($this->bwfd_value){
        		case "0":
        		  $this->bwfd_show = "δ����";
        		  break;
				case "1":
        		  $this->bwfd_show = "����δͨ��";
        		  break;  
        		case "9":
        		  $this->bwfd_show = "����ͨ��";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}

 // ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_pmreq_id") ,
   			    );
   var $bwlk_prgname ="slotcardmoneyreq_h.php?listid=";
   var $bwlk_title ="��ϸ�鿴";  
} 
if (isset ( $pagerows )) { // ��ʾ����
	$pagerows = min ( $pagerows, 100 ); // �����ʾ����������100
} else {
	$pagerows = $loginbrowline;
}

$tb_slotcardmoneyreq_sp_b_bu = new tb_slotcardmoneyreq_sp_b ();
$tb_slotcardmoneyreq_sp_b_bu->browse_skin = $loginskin;
$tb_slotcardmoneyreq_sp_b_bu->browse_delqx = $thismenuqx [3]; // ɾ��Ȩ��
$tb_slotcardmoneyreq_sp_b_bu->browse_addqx = $thismenuqx [1]; // ����Ȩ��
$tb_slotcardmoneyreq_sp_b_bu->browse_editqx = $thismenuqx [2]; // �༭Ȩ��
$tb_slotcardmoneyreq_sp_b_bu->browse_link  = array("lk_view0");
//��ʾ��Ȩ�޲鿴�Ļ�������
$tb_slotcardmoneyreq_sp_b_bu->browse_querywhere = "fd_pmreq_state <> 0";
$tb_slotcardmoneyreq_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>

