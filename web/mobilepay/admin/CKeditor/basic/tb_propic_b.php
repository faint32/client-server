<?
$thismenucode = "1c607";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("基本资料","商品设置");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_procatrad_id";
	 
	 var $browse_queryselect = "select fd_trademark_name,fd_trademark_id,fd_proca_catname,fd_procatrad_id,fd_procatrad_datetime,
	 fd_procatrad_commjs from web_conf_procatrademark
	                            left join tb_trademark on fd_trademark_id = fd_procatrad_trademarkid
	                            left join tb_procatalog on fd_proca_id = fd_procatrad_procaid                        
	                             ";

     var $browse_defaultorder = " fd_procatrad_datetime desc
                              ";	    
     var $browse_new    = "propic.php" ;
	 var $browse_edit   = "propic.php?listid=" ;
    // var $browse_link  = array("lk_view0");

	 var $browse_field = array("fd_procatrad_id","fd_proca_catname","fd_trademark_name","fd_procatrad_datetime","fd_procatrad_commjs");
  	 var $browse_find = array(		// 查询条件
				"0" => array("品牌"    , "fd_trademark_name"   ,"TXT"    ),
				"1" => array("类型"    , "fd_proca_catname"   ,"TXT"    ),
				);
}
class fd_procatrad_id extends browsefield {
        var $bwfd_fdname = "fd_procatrad_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_procatrad_datetime extends browsefield {
        var $bwfd_fdname = "fd_procatrad_datetime";	// 数据库中字段名称
        var $bwfd_title = "添加时间";	// 字段标题
		var $bwfd_align ="center";
}
class fd_trademark_name extends browsefield {
        var $bwfd_fdname = "fd_trademark_name";	// 数据库中字段名称
        var $bwfd_title = "品牌";	// 字段标题
}
class fd_proca_catname extends browsefield {
        var $bwfd_fdname = "fd_proca_catname";	// 数据库中字段名称
        var $bwfd_title = "类型";	// 字段标题
}
class fd_procatrad_commjs extends browsefield {
        var $bwfd_fdname = "fd_procatrad_commjs";	// 数据库中字段名称
        var $bwfd_title = "是否操作";	// 字段标题
		
		  function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {      		
        		case "":
        		    $this->bwfd_show = "未操作";
        		     break;
        		default:
        		    $this->bwfd_show = "已操作";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;

// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_procatrad_id","")
   			    );  
   var $bwlk_title ="品牌logo";	// link标题
   var $bwlk_prgname = "tradlogo.php?listid=";	// 链接程序
}

$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_procatalog_b_bu->browse_addqx = 1;  // 新增权限
$tb_procatalog_b_bu->browse_editqx = 1;  // 编辑权限
$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
