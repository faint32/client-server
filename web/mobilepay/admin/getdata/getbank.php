<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_bank_b extends findbrowse{
	 var $prgname = " ����ѡ��" ;

	 var $brow_key = "fd_bank_id";
	 var $brow_queryselect = "select fd_bank_id ,fd_bank_name from tb_bank ";	 
	 
	 var $brow_field = array("fd_bank_id","fd_bank_name");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_bank_id"  ,
   			    "1" => "fd_bank_name"
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("���б��", "fd_bank_id"  ,"TXT"),
			  "1" => array("���м��", "fd_bank_name","TXT"),
			 );
}

class fd_bank_id extends findbrowsefield {
        var $bwfd_fdname = "fd_bank_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}

class fd_bank_name extends findbrowsefield {
        var $bwfd_fdname = "fd_bank_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��������";	// �ֶα���
}


if(empty($order)){
	$order = "fd_bank_id";
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_bank_bu = new tb_bank_b ;

$tb_bank_bu->brow_skin = $loginskin ;
$tb_bank_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>