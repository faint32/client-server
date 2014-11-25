<?
$thismenucode = "2k102";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../include/fsubstr.php");

class tb_supplier_b extends browse 
{
	 var $prgnoware    = array("基本设置","供应商资料");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_supp_id";
	 
	 var $browse_queryselect = "select * from tb_supplier ";
	 var $browse_delsql = "delete from tb_supplier where fd_supp_id = '%s'" ;
	 var $browse_edit = "supplier.php?&id=" ;
     var $browse_new  = "supplier.php";

	 var $browse_field = array("fd_supp_no","fd_supp_name","fd_supp_allname","fd_supp_supptypeid","fd_supp_xingfen",
	 							"fd_supp_linkman","fd_supp_manphone","fd_supp_workstatus","fd_supp_memo");
 	 var $browse_find = array(		// 查询条件
 	      "0" => array("编号"      , "fd_supp_no"        ,"TXT") ,
				"1" => array("供应商简称", "fd_supp_name"      ,"TXT") ,
				"2" => array("供应商全称", "fd_supp_allname"   ,"TXT") ,
				"3" => array("联系人"    , "fd_supp_linkman"   ,"TXT") ,
				"4" => array("职位"      , "fd_supp_position"  ,"TXT") ,
				"5" => array("手机号码"  , "fd_supp_manphone"  ,"TXT") ,
				"6" => array("类型"      , "fd_srte_name"      ,"TXT") ,
				"7" => array("所属省份"  , "fd_supp_xingfen"   ,"TXT")
				);
}

class fd_supp_no extends browsefield {
        var $bwfd_fdname = "fd_supp_no";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_supp_name extends browsefield {
        var $bwfd_fdname = "fd_supp_name";	// 数据库中字段名称
        var $bwfd_title = "简称";	// 字段标题
}

class fd_supp_allname extends browsefield {
        var $bwfd_fdname = "fd_supp_allname";	// 数据库中字段名称
        var $bwfd_title = "全称";	// 字段标题
}

class fd_supp_supptypeid extends browsefield {
        var $bwfd_fdname = "fd_supp_supptypeid";	// 数据库中字段名称
        var $bwfd_title = "类型";	// 字段标题
}

class fd_supp_xingfen extends browsefield {
        var $bwfd_fdname = "fd_supp_xingfen";	// 数据库中字段名称
        var $bwfd_title = "省份";	// 字段标题
}

class fd_supp_linkman extends browsefield {
        var $bwfd_fdname = "fd_supp_linkman";	// 数据库中字段名称
        var $bwfd_title = "联系人";	// 字段标题
}

class fd_supp_manphone extends browsefield {
        var $bwfd_fdname = "fd_supp_manphone";	// 数据库中字段名称
        var $bwfd_title = "手机";	// 字段标题
}

class fd_supp_workstatus extends browsefield {
        var $bwfd_fdname = "fd_supp_workstatus";	// 数据库中字段名称
        var $bwfd_title = "经营状况";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "正常";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "半停产";
        		     break;
        		case "2":
        		    $this->bwfd_show = "停产";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}

class fd_supp_memo extends browsefield {
        var $bwfd_fdname = "fd_supp_memo";	// 数据库中字段名称
        var $bwfd_title = "备注";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
          $showvalue = $this->bwfd_value ;
          $showvalue = FSubstr($showvalue,0,15);
          return $showvalue ;
        }
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_supp_no";
}

$tb_supplier_b_bu = new tb_supplier_b ;
$tb_supplier_b_bu->browse_skin = $loginskin ;
$tb_supplier_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_supplier_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_supplier_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

$tb_supplier_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
