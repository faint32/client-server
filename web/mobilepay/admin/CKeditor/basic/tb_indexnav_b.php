<?
$thismenucode = "1c612";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_usefultype_b extends browse 
{
	 var $prgnoware    = array("网站后台","首页分类导航");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_usefultype_id";
	 
	 var $browse_queryselect = "select * from web_usefultype 
	                            ";
	// var $browse_delsql = "delete from web_usefultype where fd_usefultype_id = '%s'" ;
	// var $browse_new    = "showpro.php" ;
	 var $browse_edit   = "indexnav.php?listid=" ;
	 
	 //var $browse_outtoexcel ="excelwriter_usefultype.php";
	 //var $browse_inputfile = "input_usefultype.php";

	 var $browse_field = array("fd_usefultype_id","fd_usefultype_no","fd_usefultype_name");
 	 var $browse_find = array(		// 查询条件
				"0" => array("分类名" , "fd_usefultype_name","TXT"),

				);
	 

}

class fd_usefultype_id extends browsefield {
        var $bwfd_fdname = "fd_usefultype_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_usefultype_no extends browsefield {
        var $bwfd_fdname = "fd_usefultype_no";	// 数据库中字段名称
        var $bwfd_title = "排序";	// 字段标题
		var $bwfd_align = "center";
		
}
class fd_usefultype_name extends browsefield {
        var $bwfd_fdname = "fd_usefultype_name";	// 数据库中字段名称
        var $bwfd_title = "名称";	// 字段标题
		var $bwfd_align = "center";
		
}

class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_usefultype_id") 
   			    );
   var $bwlk_prgname = "toptra.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>推荐品牌</span>";  
   
  
}
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_usefultype_id") 
   			    );
   var $bwlk_prgname = "toptralogo.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>推荐品牌logo</span>";  
   
  
}


if(empty($order)){
	$order = "fd_usefultype_title";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_usefultype_b_bu = new tb_usefultype_b ;
$tb_usefultype_b_bu->browse_skin = $loginskin ;
$tb_usefultype_b_bu->browse_delqx = 1;  // 删除权限
$tb_usefultype_b_bu->browse_addqx = 1;  // 新增权限
$tb_usefultype_b_bu->browse_editqx = 1;  // 编辑权限
$tb_usefultype_b_bu->browse_link  = array("lk_view0","lk_view1");

$tb_usefultype_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
