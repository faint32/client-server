<?
$thismenucode = "2n106";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bank_b extends browse 
{
	 var $prgnoware    = array("ˢ��������","��������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_bank_id";
	 
	 var $browse_queryselect = "select * from tb_bank ";
	
	 var $browse_edit   = "bank.php?listid=" ;
	  var $browse_new   = "bank.php" ;
	

	 var $browse_field = array("fd_bank_id","fd_bank_name","fd_bank_rates","fd_bank_bear");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_bank_id","TXT"),
				"1" => array("��������" , "fd_bank_name","TXT"),
				); 
}

class  fd_bank_id  extends browsefield {
        var $bwfd_fdname = "fd_bank_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}
class fd_bank_rates  extends browsefield {
        var $bwfd_fdname = "fd_bank_rates";	// ���ݿ����ֶ�����
        var $bwfd_title = "��׼����";	// �ֶα���
}
class fd_bank_bear  extends browsefield {
        var $bwfd_fdname = "fd_bank_bear";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ге�";	// �ֶα���
}

if(empty($order)){
	$order = "fd_bank_id";
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_bank_b_bu = new tb_bank_b ;
$tb_bank_b_bu->browse_skin = $loginskin ;
$tb_bank_b_bu->browse_delqx = 1;  // ɾ��Ȩ��
$tb_bank_b_bu->browse_addqx = 1;  // ����Ȩ��
$tb_bank_b_bu->browse_editqx = 1;  // �༭Ȩ��

$tb_bank_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
