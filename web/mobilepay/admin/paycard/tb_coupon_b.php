<?
$thismenucode = "2n304";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("���Ä�����");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_coupon_id";
	 
	 var $browse_delsql = "delete from tb_coupon where fd_coupon_id = '%s'" ;
	 var $browse_queryselect = "select * from tb_coupon ";
	 var $browse_edit = "coupon.php?listid=" ;
	 var $browse_new = "coupon.php";
   var $browse_editname = "�޸�";
	 var $browse_field = array("fd_coupon_no","fd_coupon_money","fd_coupon_limitnum","fd_coupon_active","fd_coupon_datetime");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("����ȯ���","fd_coupon_no","TXT"),
				"1" => array("��ȯ�ֽ��","fd_coupon_money","TXT"),
				);
	 
}

class fd_coupon_no extends browsefield {
        var $bwfd_fdname = "fd_coupon_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "����ȯ���";	// �ֶα���
}

class fd_coupon_money extends browsefield {
        var $bwfd_fdname = "fd_coupon_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ȯ�ֶ��";	// �ֶα���
}
class fd_coupon_limitnum extends browsefield {
    var $bwfd_fdname = "fd_coupon_limitnum";	// ���ݿ����ֶ�����
    var $bwfd_title = "���ƹ�������";	// �ֶα���
}
class fd_coupon_active extends browsefield {
        var $bwfd_fdname = "fd_coupon_active";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ƿ񼤻����";	// �ֶα���
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

class fd_coupon_datetime extends browsefield {
        var $bwfd_fdname = "fd_coupon_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "ʱ��";	// �ֶα���
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

