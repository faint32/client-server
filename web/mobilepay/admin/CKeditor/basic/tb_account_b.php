<?
$thismenucode = "1c614";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("��������","��Ʊ�趨");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_account_id";
	 
	 var $browse_queryselect = "select fd_account_id,fd_account_linkman,fd_account_bank,fd_account_bankno,
	                           fd_account_bankno,fd_sdcr_name,a.fd_fptype_name as afpname,
	                           b.fd_fptype_name as bfpname
	                            from web_account 
	                            left join tb_sendcenter on fd_sdcr_id = fd_account_sdcrid
	                            left join web_fptype as a on a.fd_fptype_id = fd_account_zzsfp
	                            left join web_fptype as b on b.fd_fptype_id = fd_account_ptfp
	                            ";
	var $browse_new    = "account.php" ;
	 var $browse_edit   = "account.php?id=" ;
	 var $browse_delsql = "delete from web_account where fd_account_id = '%s'" ;
	 var $browse_field = array("fd_account_linkman","fd_account_bank","fd_account_bankno","fd_sdcr_name","afpname","bfpname");
 	 var $browse_find = array(		// ��ѯ����
				"0" => array("��Ʊ��˾" , "fd_account_linkman","TXT"),
				"1" => array("��������" , "fd_account_bank","TXT"),
				"2" => array("�����˺�" , "fd_account_bankno","TXT"),
				"3" => array("�����ܲ�" , "fd_sdcr_name","TXT"),
				"4" => array("��ֵ˰��Ʊ����" , "afpname","TXT"),
				"5" => array("��ͨ��Ʊ����" , "bfpname","TXT"),
				); 
}

class fd_account_linkman extends browsefield {
        var $bwfd_fdname = "fd_account_linkman";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ʊ��˾";	// �ֶα���
}

class fd_account_bank extends browsefield {
        var $bwfd_fdname = "fd_account_bank";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_account_bankno extends browsefield {
        var $bwfd_fdname = "fd_account_bankno";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����˺�";	// �ֶα���
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����ܲ�";	// �ֶα���
}

class afpname extends browsefield {
        var $bwfd_fdname = "afpname";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ֵ˰��Ʊ����";	// �ֶα���
}

class bfpname extends browsefield {
        var $bwfd_fdname = "bfpname";	// ���ݿ����ֶ�����
        var $bwfd_title = "��ͨ��Ʊ����";	// �ֶα���
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;
$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // ɾ��Ȩ��
$tb_procatalog_b_bu->browse_addqx = $thismenuqx[1];  // ����Ȩ��
$tb_procatalog_b_bu->browse_editqx = $thismenuqx[2];  // �༭Ȩ��

$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
