<?
//$thisprgcode = "sys";
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_author_b extends findbrowse{
	 var $prgname = " 会员选择" ;

	 var $brow_key = "fd_author_id";
	 var $brow_queryselect = "select fd_author_id ,fd_author_username , fd_author_truename , fd_author_mobile 
	                          from tb_author ";	 
	 
	 var $brow_field = array("fd_author_id","fd_author_username","fd_author_truename","fd_author_mobile");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_author_id"  ,
   			    "1" => "fd_author_truename",
   			    "2" => "fd_author_mobile",
   			    "3" => "fd_author_username",
   			   );  
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("会员编号", "fd_author_id"  ,"TXT"),
			  "1" => array("会员简称", "fd_author_username","TXT"),
			  "2" => array("会员全称", "fd_author_truename","TXT")
			 );
}

class fd_author_id extends findbrowsefield {
        var $bwfd_fdname = "fd_author_id";	// 数据库中字段名称
        var $bwfd_title = "会员编号";	// 字段标题
}

class fd_author_username extends findbrowsefield {
        var $bwfd_fdname = "fd_author_username";	// 数据库中字段名称
        var $bwfd_title = "会员账户";	// 字段标题
}

class fd_author_truename extends findbrowsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "会员真实姓名";	// 字段标题
}
class fd_author_mobile extends findbrowsefield {
        var $bwfd_fdname = "fd_author_mobile";	// 数据库中字段名称
        var $bwfd_title = "会员手机";	// 字段标题
}

if(empty($order)){
	$order = "fd_author_id";
}

if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_author_bu = new tb_author_b ;

$tb_author_bu->brow_skin = $loginskin ;
$tb_author_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>