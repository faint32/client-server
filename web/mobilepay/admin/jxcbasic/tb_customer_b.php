<?
$thismenucode = "2k103";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_customer_b extends browse 
{
	 var $prgnoware    = array("��������","����������");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_cus_id";
	 
	 var $browse_queryselect = "select * from tb_customer ";
	 var $browse_delsql = "delete from tb_customer where fd_cus_id = '%s'" ;
	 var $browse_new    = "customer.php" ;
	 var $browse_edit   = "customer.php?id=" ;
	 
	 var $browse_field = array("fd_cus_no","fd_cus_name","fd_cus_allname","fd_cus_address","fd_cus_linkman","fd_cus_manphone");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���" , "fd_cus_no","TXT"),
				"1" => array("������"   , "fd_cus_name","TXT"),
				"0" => array("������ȫ��" , "fd_cus_allname","TXT"),
				);
}

class fd_cus_no extends browsefield {
        var $bwfd_fdname = "fd_cus_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}


class fd_cus_name extends browsefield {
        var $bwfd_fdname = "fd_cus_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "������";	// �ֶα���
}

class fd_cus_allname extends browsefield {
        var $bwfd_fdname = "fd_cus_allname";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ȫ��";	// �ֶα���
}


class fd_cus_address extends browsefield {
        var $bwfd_fdname = "fd_cus_address";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ڵ�";	// �ֶα���
}
class fd_cus_linkman extends browsefield {
        var $bwfd_fdname = "fd_cus_linkman";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ϵ��";	// �ֶα���
}


class fd_cus_manphone extends browsefield {
        var $bwfd_fdname = "fd_cus_manphone";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ϵ���ֻ�";	// �ֶα���
}
// ���Ӷ���
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// �������ݿ����ֶ�����
   			    "0" => array("fd_cus_id") 
   			    );
   var $bwlk_prgname = "../paycardjxc/tellerlist.php?listid=";
   var $bwlk_title ="�û�����";  
}


if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_customer_b_bu = new tb_customer_b ;
$tb_customer_b_bu->browse_skin = $loginskin ;
$tb_customer_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_customer_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_customer_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_customer_b_bu->browse_link  = array("lk_view0");
$tb_customer_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
