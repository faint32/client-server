<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_paycardstockdetail_b extends findbrowse{
	 var $prgname = " ˢ����ѡ��" ;

	 var $brow_key = "fd_skdetail_paycardid";
	 var $brow_queryselect = "select fd_skdetail_paycardid , fd_skdetail_paycardid , fd_skqy_quantity , fd_sect_cost  
							  from  tb_paycardstock  
							  left join   tb_paycardstockdetail on fd_skdetail_stockid=fd_stock_id
	                          left join tb_paycardstockquantity on fd_skqy_commid=fd_skdetail_paycardid
							  left join tb_storagecost on fd_sect_paycardid=fd_skdetail_paycardid
							  ";	 
	 
	 var $brow_field = array("fd_skdetail_paycardid","fd_skqy_quantity","fd_sect_cost");
   var $brow_getvalue = array(			// �������ݿ����ֶ�����
   			    "0" => "fd_skdetail_paycardid"  ,
   			    "1" => "fd_skqy_quantity"  ,
				"2" => "fd_sect_cost"  ,
   			   );  
   			    
	 var $brow_find = array(		// ��ѯ����
			  "0" => array("ˢ����KEY", "fd_skdetail_paycardid"  ,"TXT"),
			 );
}

class fd_skdetail_paycardid extends findbrowsefield {
        var $bwfd_fdname = "fd_skdetail_paycardid";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ����KEY";	// �ֶα���
}

class fd_skqy_quantity extends findbrowsefield {
        var $bwfd_fdname = "fd_skqy_quantity";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ��������";	// �ֶα���
}

class fd_sect_cost extends findbrowsefield {
        var $bwfd_fdname = "fd_sect_cost";	// ���ݿ����ֶ�����
        var $bwfd_title = "ˢ�����۸�";	// �ֶα���
}

if(empty($order)){
	$order = "fd_skdetail_paycardid";
}

if(isset($pagerows)){	// ��ʾ����
       $pagerows = min($pagerows,100) ;  // �����ʾ����������100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_paycardstockdetail_bu = new tb_paycardstockdetail_b ;
$tb_paycardstockdetail_bu->browse_querywhere = "fd_stock_state='9'";
$tb_paycardstockdetail_bu->brow_skin = $loginskin ;

$tb_paycardstockdetail_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>