<?
$thismenucode = "2k105";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_customertype_b extends browse 
{
	 var $prgnoware    = array("��������","����������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_customertype_id";
	 
	 var $browse_queryselect = "select * from tb_customertype ";
	 var $browse_delsql = "delete from tb_customertype where fd_customertypetomertype_id = '%s'" ;
	 var $browse_new    = "customertype.php" ;
	 var $browse_edit   = "customertype.php?id=" ;
	 
	 var $browse_field = array("fd_customertype_no","fd_customertype_name");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_customertype_no","TXT"),
				"1" => array("��������������"   , "fd_customertype_name","TXT"),
				);
}

class fd_customertype_no extends browsefield {
        var $bwfd_fdname = "fd_customertype_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}


class fd_customertype_name extends browsefield {
        var $bwfd_fdname = "fd_customertype_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������������";	// �ֶα���
}





if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_customertype_b_bu = new tb_customertype_b ;
$tb_customertype_b_bu->browse_skin = $loginskin ;
$tb_customertype_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_customertype_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_customertype_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_customertype_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
