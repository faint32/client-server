<?
$thismenucode = "1c611";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_conf_showpro_b extends browse 
{
	 var $prgnoware    = array("销售活动","首页分类展示");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_csp_id";
	 
	 var $browse_queryselect = "select * from web_conf_showpro 
	                            ";
	// var $browse_delsql = "delete from web_conf_showpro where fd_csp_id = '%s'" ;
	// var $browse_new    = "showpro.php" ;
	 var $browse_edit   = "showpro.php?listid=" ;
	 
	 //var $browse_outtoexcel ="excelwriter_conf_showpro.php";
	 //var $browse_inputfile = "input_conf_showpro.php";

	 var $browse_field = array("fd_csp_id","fd_csp_procaname","fd_csp_active");
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_csp_title","TXT"),

				);
	 

}

class fd_csp_id extends browsefield {
        var $bwfd_fdname = "fd_csp_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_csp_procaname extends browsefield {
        var $bwfd_fdname = "fd_csp_procaname";	// 数据库中字段名称
        var $bwfd_title = "分类";	// 字段标题
}
class fd_csp_active extends browsefield {
        var $bwfd_fdname = "fd_csp_active";	// 数据库中字段名称
        var $bwfd_title = "状态";	// 字段标题
		var $bwfd_align = "center";
		  function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {      		
        		case "1":
        		    $this->bwfd_show = "激活";
        		     break;
        	
				case "0":
        		    $this->bwfd_show = "<font color='#0000ff'>取消</font>";
					break;
        		break; 
        	   		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_csp_id") 
   			    );
   var $bwlk_prgname = "adpro.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>推荐广告商品</span>";  
   
  
 
}
class lk_view1 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_csp_id") 
   			    );
   var $bwlk_prgname = "hostsearch.php?id=";
   var $bwlk_title ="<span style='color:#0000ff'>设置标签</span>";  
   
  
 
}
if(empty($order)){
	$order = "fd_csp_title";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_conf_showpro_b_bu = new tb_conf_showpro_b ;
$tb_conf_showpro_b_bu->browse_skin = $loginskin ;
$tb_conf_showpro_b_bu->browse_delqx = 1;  // 删除权限
$tb_conf_showpro_b_bu->browse_addqx = 1;  // 新增权限
$tb_conf_showpro_b_bu->browse_editqx = 1;  // 编辑权限
$tb_conf_showpro_b_bu->browse_link  = array("lk_view0","lk_view1");

$tb_conf_showpro_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
