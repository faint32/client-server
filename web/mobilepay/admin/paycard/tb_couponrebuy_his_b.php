<?
$thismenucode = "8n004";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("�Ż݄�����","�Ż݄��ع���ʷ");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_couponrebuy_id";
	 
  var $browse_link  = array("lk_view0");
	 var $browse_queryselect = "select fd_couponrebuy_id,fd_couponrebuy_no,date_format(fd_couponrebuy_datetime,'%Y-%m-%d') as fd_couponrebuy_datetime,
	                             fd_couponrebuy_money,fd_author_username,fd_author_truename,fd_bank_name,fd_couponrebuy_bankcardno,
	                             fd_author_idcard,fd_couponrebuy_czman, fd_couponrebuy_czdatetime,fd_couponrebuy_couponno
	                            from tb_couponrebuy
	                            left join tb_couponsale on fd_couponsale_id = fd_couponrebuy_couponid
	                            left join tb_bank on fd_couponrebuy_bankid = fd_bank_id
	                            left join tb_author on fd_author_id = fd_couponrebuy_authorid
	                            ";
	 var $browse_field = array("fd_couponrebuy_no","fd_couponrebuy_couponno","fd_couponrebuy_money","fd_author_username","fd_author_truename","fd_author_idcard","fd_bank_name","fd_couponrebuy_bankcardno","fd_couponrebuy_datetime","fd_couponrebuy_czman","fd_couponrebuy_czdatetime");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�һ�����", "fd_couponrebuy_no","TXT"),
				"1" => array("�Ż݄���", "fd_couponrebuy_couponno","TXT"),
				"2" => array("�һ����", "fd_couponrebuy_money","TXT"),
				"3" => array("�û��˺�", "fd_author_username","TXT"),
				"4" => array("�û�����", "fd_author_truename","TXT"),
				"5" => array("���֤��", "fd_author_idcard","TXT"),
				"6" => array("��������", "fd_bank_name","TXT"),
				"7" => array("�����˻�", "fd_couponrebuy_bankcardno","TXT"),
				"8" => array("��������", "fd_couponrebuy_datetime","TXT"),
				"9" => array("������", "fd_couponrebuy_czman","TXT"),
				"10" => array("��������", "fd_couponrebuy_czdatetime","TXT"),
				);
	 
}

class fd_couponrebuy_no extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
}

class fd_couponrebuy_couponno extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_couponno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�Ż݄���";	// �ֶα���
}

class fd_couponrebuy_money extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "�һ����";	// �ֶα���
}
class fd_author_username extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û��˺�";	// �ֶα���
}
class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// ���ݿ����ֶ�����
        var $bwfd_title = "�û�����";	// �ֶα���
}
class fd_author_idcard extends browsefield {
        var $bwfd_fdname = "fd_author_idcard";	// ���ݿ����ֶ�����
        var $bwfd_title = "���֤��";	// �ֶα���
        var $bwfd_format = "idcard";
}
class fd_bank_name extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_couponrebuy_bankcardno extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_bankcardno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����˻�";	// �ֶα���
}
class fd_couponrebuy_datetime extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_datetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_couponrebuy_czman extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_czman";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
}
class fd_couponrebuy_czdatetime extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_czdatetime";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_couponrebuy_id") 
   			    );
   var $bwlk_prgname = "couponrebuy_view.php?listid=";
   var $bwlk_title ="�鿴��ϸ";  
}
if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_feedback_b_bu->browse_querywhere = "fd_couponrebuy_state = 9";
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

