<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_product_b extends findbrowse{
	 var $prgname = " 商品选择" ;

	 var $brow_key = "fd_product_id";
	 var $brow_queryselect = "select fd_product_id , fd_product_no , fd_product_name  
	                          from tb_product ";	 
	 
	 var $brow_field = array("fd_product_no","fd_product_name");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_product_id"  ,
   			    "1" => "fd_product_name",
				"2" => "fd_product_no",
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("商品编号", "fd_product_no"  ,"TXT"),
			  "1" => array("商品名称", "fd_product_name","TXT"),
			 );
}

class fd_product_no extends findbrowsefield {
        var $bwfd_fdname = "fd_product_no";	// 数据库中字段名称
        var $bwfd_title = "商品编号";	// 字段标题
}

class fd_product_name extends findbrowsefield {
        var $bwfd_fdname = "fd_product_name";	// 数据库中字段名称
        var $bwfd_title = "商品名称";	// 字段标题
}



if(empty($order)){
	$order = "fd_product_no";
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_product_bu = new tb_product_b ;
$tb_product_bu->brow_querywhere = " fd_product_suppid='$suppid'";

$productno = strtolower($productno);
if(!empty($productno)){
	   $allbarcount = strlen($productno);
    $intcount=0;
    $arr_chars = preg_split('//', $productno, -1, PREG_SPLIT_NO_EMPTY);
    for($i=0;$i<count($arr_chars);$i++){
    	if(ereg("[0-9]",$arr_chars[$i])){
    	  $str_int .= $arr_chars[$i];
      }
    }
    
    for($i=0;$i<count($arr_chars);$i++){
    	if(ereg("[a-z]",$arr_chars[$i])){
    	  $strchars .= $arr_chars[$i];
      }
    }
$tb_product_bu->brow_querywhere .= " and fd_product_no like '%".$str_int."%' and fd_product_no like '%".$strchars."%'";
}

$tb_product_bu->brow_skin = $loginskin ;
$tb_product_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>