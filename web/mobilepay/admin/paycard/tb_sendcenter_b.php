<?
$thismenucode = "2k512";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("��������", "���׹�������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_sdcr_id";
	 
	 //var $browse_delsql = "delete from tb_coupon where fd_sdcr_id = '%s'" ;
	 var $browse_queryselect = "select * from tb_sendcenter
							left join tb_provinces on fd_provinces_code=fd_sdcr_provcode	
							left join tb_city on fd_city_code=fd_sdcr_citycode	
								";
	 var $browse_edit = "sendcenter.php?listid=" ;
	 var $browse_new = "sendcenter.php";
   var $browse_editname = "�޸�";
    
   var $browse_defaultorder = " fd_sdcr_id asc
                              "; 
    
	 var $browse_field = array("fd_sdcr_id","fd_provinces_name","fd_city_name","fd_sdcr_name","fd_sdcr_active","fd_sdcr_merid","fd_sdcr_payfee","fd_sdcr_agentfee");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("����ʡ��", "fd_provinces_name","TXT"),
				"1" => array("��������", "fd_city_name","TXT"),
				"2" => array("�����̻���", "fd_sdcr_merid","TXT"),
				"3" => array("�����̻���Կ", "fd_sdcr_securitykey","TXT"),
				);
	 
}

class fd_sdcr_id extends browsefield {
        var $bwfd_fdname = "fd_sdcr_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_provinces_name extends browsefield {
        var $bwfd_fdname = "fd_provinces_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ʡ��";	// �ֶα���
}

class fd_city_name extends browsefield {
        var $bwfd_fdname = "fd_city_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��˾��";	// �ֶα���
}

class fd_sdcr_active extends browsefield {
        var $bwfd_fdname = "fd_sdcr_active";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ񼤻�";	// �ֶα���
		function makeshow() {	// ��ֵתΪ��ʾֵ
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "δ����";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "����";
        		     break; 						
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_sdcr_merid extends browsefield {
        var $bwfd_fdname = "fd_sdcr_merid";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̻���";	// �ֶα���
}

class fd_sdcr_payfee extends browsefield {
        var $bwfd_fdname = "fd_sdcr_payfee";	// ���ݿ����ֶ�����
        var $bwfd_title = "����֧������ʢ������";	// �ֶα���
}
class fd_sdcr_agentfee extends browsefield {
        var $bwfd_fdname = "fd_sdcr_agentfee";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʽ������ȡ������";	// �ֶα���
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx =1;  // ����Ȩ��
$tb_feedback_b_bu->browse_editqx =1;  // �༭Ȩ��
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

