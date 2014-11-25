<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_manufacturer_b extends findbrowse{
	 var $prgname = " ������ѡ��" ;

	 var $brow_key = "fd_manu_id";
	 var $brow_queryselect = "select fd_manu_id , fd_manu_no , fd_manu_name , fd_manu_allname 
	                          from tb_manufacturer ";	 
	 
	 var $brow_field = array("fd_manu_no","fd_manu_name","fd_manu_allname");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_manu_id"  ,
   			    "1" => "fd_manu_no"  ,
   			    "2" => "fd_manu_allname",
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("�����̱��", "fd_manu_no"  ,"TXT"),
			  "1" => array("�����̼��", "fd_manu_name","TXT"),
			  "2" => array("������ȫ��", "fd_manu_allname","TXT")
			 );
}

class fd_manu_no extends findbrowsefield {
        var $bwfd_fdname = "fd_manu_no";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̱��";	// �ֶα���
}

class fd_manu_name extends findbrowsefield {
        var $bwfd_fdname = "fd_manu_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "�����̼��";	// �ֶα���
}

class fd_manu_allname extends findbrowsefield {
        var $bwfd_fdname = "fd_manu_allname";	// ���ݿ����ֶ�����
        var $bwfd_title = "������ȫ��";	// �ֶα���
}

if(empty($order)){
	$order = "fd_manu_no";
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_manufacturer_bu = new tb_manufacturer_b ;

$tb_manufacturer_bu->brow_skin = $loginskin ;
$tb_manufacturer_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>