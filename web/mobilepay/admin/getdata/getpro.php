<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_supplier_b extends findbrowse{
	 var $prgname = " 商品选择" ;

	 var $brow_key = "fd_product_id";
	 var $brow_queryselect = 'select fd_product_id,fd_product_name
	                          from tb_product';	 
	 
	 var $brow_field = array("fd_product_id","fd_product_name");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_product_id" ,
				"1" => "fd_product_name",
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("编号", "fd_product_id"  ,"TXT"),
			  "1" => array("商品名称", "fd_product_name","TXT"),
			 );
}

class fd_product_id extends findbrowsefield {
        var $bwfd_fdname = "fd_product_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_product_name extends findbrowsefield {
        var $bwfd_fdname = "fd_product_name";	// 数据库中字段名称
        var $bwfd_title = "商品名称";	// 字段标题
}

if(empty($order)){
	$order = "fd_product_id";
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