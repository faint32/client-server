<?
$thismenucode = "2k218";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
//require ("../include/checkisopendata.php");

class tb_inmoneylist_b extends browse 
{
	 var $prgnoware    = array("ˢ��������","�տ����");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_inmylt_id";
	 
	 var $browse_queryselect = "select * from tb_inmoneylist
	                            left join tb_account  on fd_account_id   = fd_inmylt_accountid 
	                            ";
	 var $browse_delsql = "delete from tb_inmoneylist where fd_inmylt_id = '%s'";
	 var $browse_new    = "inmoneylist.php" ;
	 var $browse_edit   = "inmoneylist.php?listid=" ;
  
	 var $browse_field = array("fd_inmylt_no","fd_inmylt_date","fd_inmylt_clientno","fd_inmylt_clientname","fd_inmylt_dealwithman","fd_inmylt_money");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("���ݱ��", "fd_inmylt_no"          ,"TXT"),				
				"1" => array("��������", "fd_inmylt_date"        ,"TXT"), 
				"2" => array("�տ���"  , "fd_inmylt_dealwithman" ,"TXT"),
				"3" => array("������λ", "fd_inmylt_clientname"  ,"TXT")
				);
}
class fd_inmylt_no extends browsefield {
        var $bwfd_fdname = "fd_inmylt_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "���ݱ��";	// �ֶα���
}

class fd_inmylt_clientno extends browsefield {
        var $bwfd_fdname = "fd_inmylt_clientno";	// ���ݿ����ֶ�����
        var $bwfd_title = "������λ���";	// �ֶα���
}

class fd_inmylt_clientname extends browsefield {
        var $bwfd_fdname = "fd_inmylt_clientname";	// ���ݿ����ֶ�����
        var $bwfd_title = "������λ����";	// �ֶα���
}

class fd_inmylt_date extends browsefield {
        var $bwfd_fdname = "fd_inmylt_date";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_inmylt_dealwithman extends browsefield {
        var $bwfd_fdname = "fd_inmylt_dealwithman";	// ���ݿ����ֶ�����
        var $bwfd_title = "�տ���";	// �ֶα���
}

class fd_inmylt_money extends browsefield {
        var $bwfd_fdname = "fd_inmylt_money";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_inmylt_date";
	$upordown = "desc";
}

$tb_inmoneylist_b_bu = new tb_inmoneylist_b ;
$tb_inmoneylist_b_bu->browse_skin = $loginskin ;
$tb_inmoneylist_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_inmoneylist_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_inmoneylist_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��
$tb_inmoneylist_b_bu->browse_querywhere = "fd_inmylt_state = '0' ";

$tb_inmoneylist_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
