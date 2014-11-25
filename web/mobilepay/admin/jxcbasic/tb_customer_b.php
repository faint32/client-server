<?
$thismenucode = "2k103";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_customer_b extends browse 
{
	 var $prgnoware    = array("基本设置","代理商资料");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_cus_id";
	 
	 var $browse_queryselect = "select * from tb_customer ";
	 var $browse_delsql = "delete from tb_customer where fd_cus_id = '%s'" ;
	 var $browse_new    = "customer.php" ;
	 var $browse_edit   = "customer.php?id=" ;
	 
	 var $browse_field = array("fd_cus_no","fd_cus_name","fd_cus_allname","fd_cus_address","fd_cus_linkman","fd_cus_manphone");
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_cus_no","TXT"),
				"1" => array("代理商"   , "fd_cus_name","TXT"),
				"0" => array("代理商全称" , "fd_cus_allname","TXT"),
				);
}

class fd_cus_no extends browsefield {
        var $bwfd_fdname = "fd_cus_no";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}


class fd_cus_name extends browsefield {
        var $bwfd_fdname = "fd_cus_name";	// 数据库中字段名称
        var $bwfd_title = "代理商";	// 字段标题
}

class fd_cus_allname extends browsefield {
        var $bwfd_fdname = "fd_cus_allname";	// 数据库中字段名称
        var $bwfd_title = "代理商全称";	// 字段标题
}


class fd_cus_address extends browsefield {
        var $bwfd_fdname = "fd_cus_address";	// 数据库中字段名称
        var $bwfd_title = "所在地";	// 字段标题
}
class fd_cus_linkman extends browsefield {
        var $bwfd_fdname = "fd_cus_linkman";	// 数据库中字段名称
        var $bwfd_title = "联系人";	// 字段标题
}


class fd_cus_manphone extends browsefield {
        var $bwfd_fdname = "fd_cus_manphone";	// 数据库中字段名称
        var $bwfd_title = "联系人手机";	// 字段标题
}
// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_cus_id") 
   			    );
   var $bwlk_prgname = "../paycardjxc/tellerlist.php?listid=";
   var $bwlk_title ="用户设置";  
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_customer_b_bu = new tb_customer_b ;
$tb_customer_b_bu->browse_skin = $loginskin ;
$tb_customer_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_customer_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_customer_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_customer_b_bu->browse_link  = array("lk_view0");
$tb_customer_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
