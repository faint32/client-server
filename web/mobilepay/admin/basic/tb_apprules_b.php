<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_apprules_b extends browse 
{
	 var $prgnoware    = array("��������","ʹ������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_apprules_id";
	 

	 var $browse_queryselect = "select tb_apprules.*,tb_apprulestype.fd_apprulestype_name from tb_apprules
	 							left join tb_apprulestype on tb_apprulestype.fd_apprulestype_id = tb_apprules.fd_apprules_type";
	 
	 var $browse_delsql = "delete from tb_apprules where fd_apprules_id = '%s'" ;
	 var $browse_new = "apprules.php" ;
	 var $browse_edit = "apprules.php?id=" ;
   
	 var $browse_field = array("fd_apprules_no","fd_apprules_title","fd_apprulestype_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("�汾��", "fd_apprules_no","TXT"),
				"1" => array("APP����", "fd_apprules_title","TXT")
				);
	 
}

class fd_apprules_no extends browsefield {
        var $bwfd_fdname = "fd_apprules_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_apprules_title extends browsefield {
        var $bwfd_fdname = "fd_apprules_title";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}


class fd_apprulestype_name extends browsefield {
        var $bwfd_fdname = "fd_apprulestype_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "����";	// �ֶα���
}





if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_apprules_b_bu = new tb_apprules_b ;
$tb_apprules_b_bu->browse_skin = $loginskin ;
$tb_apprules_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_apprules_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_apprules_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
//$tb_leftmenu_b_bu->browse_link  = array("lk_view0");

$tb_apprules_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

