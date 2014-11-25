<?
$thismenucode = "7n001";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_procatalog_b extends browse 
{
	 var $prgnoware    = array("辅助功能","帮助管理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_help_id";
	 
	 var $browse_queryselect = "select web_help.*,web_helpset.fd_helpset_name from web_help 
	                              left join web_helpset on web_helpset.fd_helpset_id = web_help.fd_help_type
	                            ";
	 var $browse_delsql = "delete from web_help where fd_help_id = '%s'" ;
	 var $browse_new    = "help.php" ;
	 var $browse_edit   = "help.php?id=" ;
	 //var $browse_outtoexcel ="excelwriter_procatalog.php";
	 //var $browse_inputfile = "input_procatalog.php";

	 var $browse_field = array("fd_help_no","fd_help_name","fd_helpset_name","fd_help_state","fd_help_date");
 	 var $browse_find = array(		// 查询条件
				"0" => array("帮助编号" , "fd_help_no","TXT"),
				"1" => array("帮助名称" , "fd_helpset_name","TXT"),
				"2" => array("帮助类型" , "fd_helpset_name","TXT",)
				); 
}
class fd_help_no extends browsefield {
        var $bwfd_fdname = "fd_help_no";	// 数据库中字段名称
        var $bwfd_title = "帮助编号";	// 字段标题
}

class fd_help_name extends browsefield {
        var $bwfd_fdname = "fd_help_name";	// 数据库中字段名称
        var $bwfd_title = "帮助名称";	// 字段标题
}

class fd_helpset_name extends browsefield {
        var $bwfd_fdname = "fd_helpset_name";	// 数据库中字段名称
        var $bwfd_title = "帮助类型";	// 字段标题
}
class fd_help_state extends browsefield {
        var $bwfd_fdname = "fd_help_state";	// 数据库中字段名称
        var $bwfd_title = "帮助状态";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch($this->bwfd_value){
        		case "2":
        		  $this->bwfd_show = "停用";
        		  break;
        		case "1":
        		  $this->bwfd_show = "使用中";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}
class fd_help_date extends browsefield {
        var $bwfd_fdname = "fd_help_date";	// 数据库中字段名称
        var $bwfd_title = "帮助时间";	// 字段标题
}



if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_procatalog_b_bu = new tb_procatalog_b ;
$tb_procatalog_b_bu->browse_skin = $loginskin ;
$tb_procatalog_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_procatalog_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_procatalog_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

//echo $teller_userid."-id<br>";
//echo $thismenuqx[1]."=".$thismenuqx[2]."=".$thismenuqx[3];
$tb_procatalog_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
