<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_supplier_b extends findbrowse{
	 var $prgname = " 供应商选择" ;

	 var $brow_key = "fd_supp_id";
	 var $brow_queryselect = "select fd_supp_id , fd_supp_no , fd_supp_name , fd_supp_allname , fd_supp_code 
	                          from tb_supplier ";	 
	 
	 var $brow_field = array("fd_supp_no","fd_supp_name","fd_supp_allname");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_supp_id"  ,
   			    "1" => "fd_supp_no"  ,
   			    "2" => "fd_supp_allname",
   			    "3" => "fd_supp_name",
   			    "4" => "fd_supp_code"
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("供应商编号", "fd_supp_no"  ,"TXT"),
			  "1" => array("供应商简称", "fd_supp_name","TXT"),
			  "2" => array("供应商全称", "fd_supp_allname","TXT")
			 );
}

class fd_supp_no extends findbrowsefield {
        var $bwfd_fdname = "fd_supp_no";	// 数据库中字段名称
        var $bwfd_title = "供应商编号";	// 字段标题
}

class fd_supp_name extends findbrowsefield {
        var $bwfd_fdname = "fd_supp_name";	// 数据库中字段名称
        var $bwfd_title = "供应商简称";	// 字段标题
}

class fd_supp_allname extends findbrowsefield {
        var $bwfd_fdname = "fd_supp_allname";	// 数据库中字段名称
        var $bwfd_title = "供应商全称";	// 字段标题
}

if(empty($order)){
	$order = "fd_supp_no";
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_supplier_bu = new tb_supplier_b ;

$tb_supplier_bu->brow_skin = $loginskin ;
$tb_supplier_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>