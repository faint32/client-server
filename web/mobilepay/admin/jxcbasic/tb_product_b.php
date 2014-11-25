<?
$thismenucode = "2k112";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
require ("../include/fsubstr.php");

class tb_product_b extends browse 
{
	 var $prgnoware    = array("基本设置","商品资料");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_product_id";
	 
	 var $browse_queryselect = "select * from tb_product 								 
								 left join  tb_producttype on fd_producttype_id=fd_product_producttypeid
								";
	 var $browse_delsql = "delete from tb_product where fd_product_id = '%s'" ;
	 var $browse_edit = "product.php?listid=" ;
     var $browse_new  = "product.php";

	 var $browse_field = array("fd_product_no","fd_product_name","fd_producttype_name","fd_product_productscope",
	 							"fd_product_suppno","fd_product_suppname");
 	 var $browse_find = array(		// 查询条件
 	      "0" => array("编号"      , "fd_product_no"        ,"TXT") ,
				"1" => array("商品类型", "fd_producttype_name"      ,"TXT") ,
				"3" => array("供应商编号"    , "fd_product_suppno"   ,"TXT") ,
				"4" => array("供应商名称"      , "fd_product_suppname"  ,"TXT") ,
				"5" => array("商品名称"      , "fd_product_name"  ,"TXT") ,
				);
}

class fd_product_no extends browsefield {
        var $bwfd_fdname = "fd_product_no";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_product_name extends browsefield {
        var $bwfd_fdname = "fd_product_name";	// 数据库中字段名称
        var $bwfd_title = "商品名称";	// 字段标题
}

class fd_producttype_name extends browsefield {
        var $bwfd_fdname = "fd_producttype_name";	// 数据库中字段名称
        var $bwfd_title = "商品类型";	// 字段标题
}

class fd_product_productscope extends browsefield {
        var $bwfd_fdname = "fd_product_productscope";	// 数据库中字段名称
        var $bwfd_title = "商品适用范围";	// 字段标题
		
		        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "creditcard":
        		    $this->bwfd_show = "信用卡";
        		     break;       		
        		case "bankcard":
        		    $this->bwfd_show = "储蓄卡";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}

class fd_product_suppno extends browsefield {
        var $bwfd_fdname = "fd_product_suppno";	// 数据库中字段名称
        var $bwfd_title = "供应商编号";	// 字段标题
}

class fd_product_suppname extends browsefield {
        var $bwfd_fdname = "fd_product_suppname";	// 数据库中字段名称
        var $bwfd_title = "供应商名称";	// 字段标题
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

if(empty($order)){
	$order = "fd_product_no";
}

$tb_product_b_bu = new tb_product_b ;
$tb_product_b_bu->browse_skin = $loginskin ;
$tb_product_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_product_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_product_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限

$tb_product_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
