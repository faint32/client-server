<?
$thismenucode = "7001";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_squadno_b extends browse 
{
	 var $prgnoware    = array("推广管理","邮箱管理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_emailuser_id";
	 
	 var $browse_queryselect = "select * from tb_emailuser ";
     var $browse_querywhere = "";
	                            
	 var $browse_delsql = "delete from tb_emailuser where fd_emailuser_id = '%s'" ;
	 var $browse_new = "emailuser.php" ;
	 var $browse_edit = "emailuser.php?id=" ;
	 
	 var $browse_field = array("fd_emailuser_name","fd_emailuser_nick","fd_emailuser_host","fd_emailuser_port","fd_emailuser_status");
 	 var $browse_find = array(		// 查询条件
				"0" => array("邮箱用户名"      ,   "fd_emailuser_name"   ,"TXT") ,
				"1" => array("卖家公司"      ,   "fd_emailuser_nick"   ,"TXT") 
				);
	 
}	

class fd_emailuser_name extends browsefield {
        var $bwfd_fdname = "fd_emailuser_name";	// 数据库中字段名称
        var $bwfd_title = "邮箱用户名";	// 字段标题
}

class fd_emailuser_nick extends browsefield {
        var $bwfd_fdname = "fd_emailuser_nick";	// 数据库中字段名称
        var $bwfd_title = "发件人称呼";	// 字段标题		
}

class fd_emailuser_host extends browsefield {
        var $bwfd_fdname = "fd_emailuser_host";	// 数据库中字段名称
        var $bwfd_title = "邮箱服务器";	// 字段标题		
}

class fd_emailuser_port extends browsefield {
        var $bwfd_fdname = "fd_emailuser_port";	// 数据库中字段名称
        var $bwfd_title = "端口";	// 字段标题		
}

class fd_emailuser_status extends browsefield {
        var $bwfd_fdname = "fd_emailuser_status";	// 数据库中字段名称
        var $bwfd_title = "邮箱状态";	// 字段标题	
		function makeshow(){
		    if($this->bwfd_value == 1) {      		
		      return "开启" ;
  	        }else{
			  return "关闭" ;
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
