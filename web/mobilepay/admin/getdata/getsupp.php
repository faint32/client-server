<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_supplier_b extends findbrowse{
	 var $prgname = " ��Ӧ��ѡ��" ;

	 var $brow_key = "fd_supp_id";
	 var $brow_queryselect = "select fd_supp_id , fd_supp_no , fd_supp_name , fd_supp_allname , fd_supp_code 
	                          from tb_supplier ";	 
	 
	 var $brow_field = array("fd_supp_no","fd_supp_name","fd_supp_allname");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_supp_id"  ,
   			    "1" => "fd_supp_no"  ,
   			    "2" => "fd_supp_allname",
   			    "3" => "fd_supp_name",
   			    "4" => "fd_supp_code"
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("��Ӧ�̱��", "fd_supp_no"  ,"TXT"),
			  "1" => array("��Ӧ�̼��", "fd_supp_name","TXT"),
			  "2" => array("��Ӧ��ȫ��", "fd_supp_allname","TXT")
			 );
}

class fd_supp_no extends findbrowsefield {
        var $bwfd_fdname = "fd_supp_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ�̱��";	// �ֶα���
}

class fd_supp_name extends findbrowsefield {
        var $bwfd_fdname = "fd_supp_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ�̼��";	// �ֶα���
}

class fd_supp_allname extends findbrowsefield {
        var $bwfd_fdname = "fd_supp_allname";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ӧ��ȫ��";	// �ֶα���
}

if(empty($order)){
	$order = "fd_supp_no";
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_supplier_bu = new tb_supplier_b ;

$tb_supplier_bu->brow_skin = $loginskin ;
$tb_supplier_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>