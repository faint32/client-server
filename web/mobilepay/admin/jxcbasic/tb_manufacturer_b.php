<?
$thismenucode = "2k102";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../include/fsubstr.php");

class tb_manufacturer_b extends browse 
{
	 var $prgnoware    = array("基本资料","制造商资料");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_manu_id";
	 
	 var $browse_queryselect = "select * from tb_manufacturer ";
	 var $browse_delsql = "delete from tb_manufacturer where fd_manu_id = '%s'" ;
	 var $browse_edit = "manufacturer.php?listid=" ;
     var $browse_new  = "manufacturer.php";

	 var $browse_field = array("fd_manu_no","fd_manu_name","fd_manu_allname","fd_manu_xingfen",
	 							"fd_manu_linkman","fd_manu_manphone","fd_manu_workstatus");
 	 var $browse_find = array(		// 查询条件
 	      "0" => array("编号"      , "fd_manu_no"        ,"TXT") ,
				"1" => array("制造商简称", "fd_manu_name"      ,"TXT") ,
				"2" => array("制造商全称", "fd_manu_allname"   ,"TXT") ,
				"3" => array("联系人"    , "fd_manu_linkman"   ,"TXT") ,
				"5" => array("手机号码"  , "fd_manu_manphone"  ,"TXT") ,
				"7" => array("所属省份"  , "fd_manu_xingfen"   ,"TXT")
				);
}

class fd_manu_no extends browsefield {
        var $bwfd_fdname = "fd_manu_no";	// 数据库中字段名称
        var $bwfd_title = "制造商编号";	// 字段标题
}

class fd_manu_name extends browsefield {
        var $bwfd_fdname = "fd_manu_name";	// 数据库中字段名称
        var $bwfd_title = "制造商简称";	// 字段标题
}

class fd_manu_allname extends browsefield {
        var $bwfd_fdname = "fd_manu_allname";	// 数据库中字段名称
        var $bwfd_title = "制造商全称";	// 字段标题
}

class fd_manu_xingfen extends browsefield {
        var $bwfd_fdname = "fd_manu_xingfen";	// 数据库中字段名称
        var $bwfd_title = "省份";	// 字段标题
}

class fd_manu_linkman extends browsefield {
        var $bwfd_fdname = "fd_manu_linkman";	// 数据库中字段名称
        var $bwfd_title = "联系人";	// 字段标题
}

class fd_manu_manphone extends browsefield {
        var $bwfd_fdname = "fd_manu_manphone";	// 数据库中字段名称
        var $bwfd_title = "联系人手机";	// 字段标题
}

class fd_manu_workstatus extends browsefield {
        var $bwfd_fdname = "fd_manu_workstatus";	// 数据库中字段名称
        var $bwfd_title = "经营状况";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "1":
        		    $this->bwfd_show = "正常";
        		     break;       		
        		case "2":
        		    $this->bwfd_show = "半停产";
        		     break;
        		case "3":
        		    $this->bwfd_show = "停产";
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

if(empty($order)){
	$order = "fd_manu_no";
}

$tb_manufacturer_b_bu = new tb_manufacturer_b ;
$tb_manufacturer_b_bu->browse_skin = $loginskin ;
$tb_manufacturer_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_manufacturer_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_manufacturer_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

$tb_manufacturer_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
