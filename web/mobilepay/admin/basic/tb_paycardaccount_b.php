<?
$thismenucode = "2k105";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("��������","ˢ������������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_paycardaccount_id";
	 

	 var $browse_queryselect = "select * 
	                            from tb_paycardaccount";
	 var $browse_edit = "paycardaccount.php?listid=" ;
	 var $browse_editname   = "����" ;
	 var $browse_new= "paycardaccount.php";
	 var $browse_delsql = "delete from tb_paycardaccount where fd_paycardaccount_id = '%s'" ;
	 var $browse_field = array("fd_paycardaccount_company","fd_paycardaccount_accountname","fd_paycardaccount_accountnum","fd_paycardaccount_bank");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("������˾", "fd_paycardaccount_company","TXT"),
				"1" => array("�˻�", "fd_paycardaccount_accountname","TXT"),
				"2" => array("�ʺ�", "fd_paycardaccount_accountnum","TXT"),
				"3" => array("������", "fd_paycardaccount_bank","TXT"),					
				);
	 
}

class fd_paycardaccount_company extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_company";	// ���ݿ����ֶ�����
        var $bwfd_title = "������˾
";	// �ֶα���
}
class fd_paycardaccount_accountname extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_accountname";	// ���ݿ����ֶ�����
        var $bwfd_title = "�˻�
";	// �ֶα���
}

class fd_paycardaccount_accountnum extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_accountnum";	// ���ݿ����ֶ�����
        var $bwfd_title = "�ʺ�
";	// �ֶα���
}

class fd_paycardaccount_bank extends browsefield {
        var $bwfd_fdname = "fd_paycardaccount_bank";	// ���ݿ����ֶ�����
        var $bwfd_title = "������
";	// �ֶα���
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_author_sp_b_bu = new tb_feedback_b ;
$tb_author_sp_b_bu->browse_skin = $loginskin ;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3];  // ɾ��Ȩ��
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1];  // ����Ȩ��
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2];  // �༭Ȩ��

$tb_author_sp_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

