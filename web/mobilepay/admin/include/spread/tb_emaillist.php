<?
$thismenucode = "7002";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_squadno_b extends browse 
{
	 var $prgnoware    = array("推广管理","邮件推广");
	 var $prgnowareurl =  array("","");

	 var $browse_key = "fd_emaillist_id";
	 
	 var $browse_queryselect = "select * from tb_emaillist ";
     var $browse_querywhere = "";
	                            
	 var $browse_delsql = "delete from tb_emaillist where fd_emaillist_id = '%s'" ;
	 var $browse_new = "emaillist.php" ;
	 var $browse_edit = "emaillist.php?id=" ;
	 
	 var $browse_field = array("fd_emaillist_title","fd_emaillist_file","fd_emaillist_date","fd_emaillist_fsdate");
 	 var $browse_find = array(		// 查询条件
				"0" => array("邮件标题"      ,   "fd_emaillist_title"   ,"TXT") ,
				"1" => array("邮件模板"      ,   "fd_emaillist_file"   ,"TXT") 
				);
	 
}	

class fd_emaillist_title extends browsefield {
        var $bwfd_fdname = "fd_emaillist_title";	// 数据库中字段名称
        var $bwfd_title = "邮件标题";	// 字段标题
}

class fd_emaillist_file extends browsefield {
        var $bwfd_fdname = "fd_emaillist_file";	// 数据库中字段名称
        var $bwfd_title = "邮件模板";	// 字段标题		
}

class fd_emaillist_date extends browsefield {
        var $bwfd_fdname = "fd_emaillist_date";	// 数据库中字段名称
        var $bwfd_title = "创建时间";	// 字段标题	
		function makeshow(){
		  if(!empty($this->bwfd_value)){
		    return date("Y-m-d H:i:s",$this->bwfd_value);
		  }else{
		    return '';
		  }
		}			
}

class fd_emaillist_fsdate extends browsefield {
        var $bwfd_fdname = "fd_emaillist_fsdate";	// 数据库中字段名称
        var $bwfd_title = "发送时间";	// 字段标题	
		function makeshow(){
		  if(!empty($this->bwfd_value)){
		    return date("Y-m-d H:i:s",$this->bwfd_value);
		  }else{
		    return '';
		  }
		}	
}



if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_squadno_b_bu = new tb_squadno_b ;
$tb_squadno_b_bu->browse_skin = $loginskin ;
$tb_squadno_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_squadno_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_squadno_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_squadno_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
