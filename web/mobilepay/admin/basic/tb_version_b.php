<?
$thismenucode = "7n002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_version_b extends browse 
{
	 var $prgnoware    = array("辅助功能","版本更新");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_version_id";
	 

	 var $browse_queryselect = "select * from tb_version ";
	 
	 var $browse_delsql = "delete from tb_version where fd_version_id = '%s'" ;
	 var $browse_new = "version.php" ;
	 var $browse_edit = "version.php?id=" ;
   
	 var $browse_field = array("fd_version_no","fd_version_apptype","fd_version_updatetime",
	 														"fd_version_datetime","fd_version_isnew","fd_version_downurl",
	 														"fd_version_clearoldinfo","fd_version_newcontent","fd_version_strupdate");
 	 var $browse_find = array(		// 查询条件
				"0" => array("版本号", "fd_version_no","TXT"),
				"1" => array("APP类型", "fd_version_apptype","TXT")
				);
	 
}

class fd_version_no extends browsefield {
        var $bwfd_fdname = "fd_version_no";	// 数据库中字段名称
        var $bwfd_title = "版本号";	// 字段标题
}

class fd_version_apptype extends browsefield {
        var $bwfd_fdname = "fd_version_apptype";	// 数据库中字段名称
        var $bwfd_title = "APP类型";	// 字段标题
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "1":
        		    $this->bwfd_show = "android_phone";
        		     break;
        		case "2":
        		    $this->bwfd_show = "ios_phone";
        		     break; 
        		case "3":
        		    $this->bwfd_show = "android_pad";
        		     break;     
				case "4":
        		    $this->bwfd_show = "ios_pad";
        		     break;  		 								
          }
		      return $this->bwfd_show ;
  	    }
}



class fd_version_updatetime extends browsefield {
        var $bwfd_fdname = "fd_version_updatetime";	// 数据库中字段名称
        var $bwfd_title = "版本更新时间";	// 字段标题
}
class fd_version_datetime extends browsefield {
        var $bwfd_fdname = "fd_version_datetime";	// 数据库中字段名称
        var $bwfd_title = "更新时间";	// 字段标题
}
class fd_version_isnew extends browsefield {
        var $bwfd_fdname = "fd_version_isnew";	// 数据库中字段名称
        var $bwfd_title = "是否最新版本";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_version_downurl extends browsefield {
        var $bwfd_fdname = "fd_version_downurl";	// 数据库中字段名称
        var $bwfd_title = "下载地址";	// 字段标题
}
class fd_version_clearoldinfo extends browsefield {
        var $bwfd_fdname = "fd_version_clearoldinfo";	// 数据库中字段名称
        var $bwfd_title = "清除旧的数据";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_version_newcontent extends browsefield {
        var $bwfd_fdname = "fd_version_newcontent";	// 数据库中字段名称
        var $bwfd_title = "更新内容";	// 字段标题
}
class fd_version_strupdate extends browsefield {
        var $bwfd_fdname = "fd_version_strupdate";	// 数据库中字段名称
        var $bwfd_title = "强制更新";	// 字段标题
		function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
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

$tb_version_b_bu = new tb_version_b ;
$tb_version_b_bu->browse_skin = $loginskin ;
$tb_version_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_version_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_version_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
//$tb_leftmenu_b_bu->browse_link  = array("lk_view0");

$tb_version_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

