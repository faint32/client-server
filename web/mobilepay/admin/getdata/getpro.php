<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_supplier_b extends findbrowse{
	 var $prgname = " ��Ʒѡ��" ;

	 var $brow_key = "fd_product_id";
	 var $brow_queryselect = 'select fd_product_id,fd_product_name
	                          from tb_product';	 
	 
	 var $brow_field = array("fd_product_id","fd_product_name");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_product_id" ,
				"1" => "fd_product_name",
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("���", "fd_product_id"  ,"TXT"),
			  "1" => array("��Ʒ����", "fd_product_name","TXT"),
			 );
}

class fd_product_id extends findbrowsefield {
        var $bwfd_fdname = "fd_product_id";	// ���ݿ����ֶ�����
        var $bwfd_title = "���";	// �ֶα���
}

class fd_product_name extends findbrowsefield {
        var $bwfd_fdname = "fd_product_name";	// ���ݿ����ֶ�����
        var $bwfd_title = "��Ʒ����";	// �ֶα���
}

if(empty($order)){
	$order = "fd_product_id";
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