<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_manufacturer_b extends findbrowse{
	 var $prgname = " 制造商选择" ;

	 var $brow_key = "fd_manu_id";
	 var $brow_queryselect = "select fd_manu_id , fd_manu_no , fd_manu_name , fd_manu_allname 
	                          from tb_manufacturer ";	 
	 
	 var $brow_field = array("fd_manu_no","fd_manu_name","fd_manu_allname");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_manu_id"  ,
   			    "1" => "fd_manu_no"  ,
   			    "2" => "fd_manu_allname",
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("制造商编号", "fd_manu_no"  ,"TXT"),
			  "1" => array("制造商简称", "fd_manu_name","TXT"),
			  "2" => array("制造商全称", "fd_manu_allname","TXT")
			 );
}

class fd_manu_no extends findbrowsefield {
        var $bwfd_fdname = "fd_manu_no";	// 数据库中字段名称
        var $bwfd_title = "制造商编号";	// 字段标题
}

class fd_manu_name extends findbrowsefield {
        var $bwfd_fdname = "fd_manu_name";	// 数据库中字段名称
        var $bwfd_title = "制造商简称";	// 字段标题
}

class fd_manu_allname extends findbrowsefield {
        var $bwfd_fdname = "fd_manu_allname";	// 数据库中字段名称
        var $bwfd_title = "制造商全称";	// 字段标题
}

if(empty($order)){
	$order = "fd_manu_no";
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_manufacturer_bu = new tb_manufacturer_b ;

$tb_manufacturer_bu->brow_skin = $loginskin ;
$tb_manufacturer_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>