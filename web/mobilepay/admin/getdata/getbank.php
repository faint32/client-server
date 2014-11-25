<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_bank_b extends findbrowse{
	 var $prgname = " 银行选择" ;

	 var $brow_key = "fd_bank_id";
	 var $brow_queryselect = "select fd_bank_id ,fd_bank_name from tb_bank ";	 
	 
	 var $brow_field = array("fd_bank_id","fd_bank_name");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_bank_id"  ,
   			    "1" => "fd_bank_name"
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("银行编号", "fd_bank_id"  ,"TXT"),
			  "1" => array("银行简称", "fd_bank_name","TXT"),
			 );
}

class fd_bank_id extends findbrowsefield {
        var $bwfd_fdname = "fd_bank_id";	// 数据库中字段名称
        var $bwfd_title = "银行名称";	// 字段标题
}

class fd_bank_name extends findbrowsefield {
        var $bwfd_fdname = "fd_bank_name";	// 数据库中字段名称
        var $bwfd_title = "银行名称";	// 字段标题
}


if(empty($order)){
	$order = "fd_bank_id";
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_bank_bu = new tb_bank_b ;

$tb_bank_bu->brow_skin = $loginskin ;
$tb_bank_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>