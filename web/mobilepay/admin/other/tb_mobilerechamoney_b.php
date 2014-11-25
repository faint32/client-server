<?
$thismenucode = "2n801";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_recham_b extends browse 
{
	 var $prgnoware    = array("话费充值","金额选项");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_recham_id";
	 
	 var $browse_queryselect = "select * from tb_mobilerechamoney ";
	
	 var $browse_edit   = "mobilerechamoney.php?listid=" ;
	  var $browse_new   = "mobilerechamoney.php" ;
	

	 var $browse_field = array("fd_recham_id","fd_recham_money","fd_recham_paymoney","fd_recham_costmoney","fd_recham_isdefault");
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_recham_id","TXT"),
				"1" => array("充值面额" , "fd_recham_money","TXT"),
				); 
}

class  fd_recham_id  extends browsefield {
        var $bwfd_fdname = "fd_recham_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_recham_money  extends browsefield {
        var $bwfd_fdname = "fd_recham_money";	// 数据库中字段名称
        var $bwfd_title = "充值面额";	// 字段标题
}
class fd_recham_paymoney  extends browsefield {
        var $bwfd_fdname = "fd_recham_paymoney";	// 数据库中字段名称
        var $bwfd_title = "实际支付";	// 字段标题
}
class fd_recham_costmoney  extends browsefield {
    var $bwfd_fdname = "fd_recham_costmoney";	// 数据库中字段名称
    var $bwfd_title = "成本金额";	// 字段标题
}

class fd_recham_isdefault  extends browsefield {
        var $bwfd_fdname = "fd_recham_isdefault";	// 数据库中字段名称
        var $bwfd_title = "是否默认显示";	// 字段标题
        function makeshow() {	// 将值转为显示值
        switch ($this->bwfd_value) {
            case "0":
                $this->bwfd_show = "否";
                break;
            case "1":
                $this->bwfd_show = "是";
                break;
            default:
                $this->bwfd_show = "否";
                break;
        }
        return $this->bwfd_show ;
    }

}

if(empty($order)){
	$order = "fd_recham_id";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_recham_b_bu = new tb_recham_b ;
$tb_recham_b_bu->browse_skin = $loginskin ;
$tb_recham_b_bu->browse_delqx = 1;  // 删除权限
$tb_recham_b_bu->browse_addqx = 1;  // 新增权限
$tb_recham_b_bu->browse_editqx = 1;  // 编辑权限

$tb_recham_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
