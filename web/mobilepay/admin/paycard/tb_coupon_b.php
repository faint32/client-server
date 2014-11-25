<?
$thismenucode = "2n304";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("抵用劵管理");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_coupon_id";
	 
	 var $browse_delsql = "delete from tb_coupon where fd_coupon_id = '%s'" ;
	 var $browse_queryselect = "select * from tb_coupon ";
	 var $browse_edit = "coupon.php?listid=" ;
	 var $browse_new = "coupon.php";
   var $browse_editname = "修改";
	 var $browse_field = array("fd_coupon_no","fd_coupon_money","fd_coupon_limitnum","fd_coupon_active","fd_coupon_datetime");
 	 var $browse_find = array(		// 查询条件
				"0" => array("抵用券编号","fd_coupon_no","TXT"),
				"1" => array("用券抵金额","fd_coupon_money","TXT"),
				);
	 
}

class fd_coupon_no extends browsefield {
        var $bwfd_fdname = "fd_coupon_no";	// 数据库中字段名称
        var $bwfd_title = "抵用券编号";	// 字段标题
}

class fd_coupon_money extends browsefield {
        var $bwfd_fdname = "fd_coupon_money";	// 数据库中字段名称
        var $bwfd_title = "用券抵额度";	// 字段标题
}
class fd_coupon_limitnum extends browsefield {
    var $bwfd_fdname = "fd_coupon_limitnum";	// 数据库中字段名称
    var $bwfd_title = "限制购买数量";	// 字段标题
}
class fd_coupon_active extends browsefield {
        var $bwfd_fdname = "fd_coupon_active";	// 数据库中字段名称
        var $bwfd_title = "是否激活可用";	// 字段标题
		 function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "未激活";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "激活";
        		     break; 						
          }
		      return $this->bwfd_show ;
  	    }
}

class fd_coupon_datetime extends browsefield {
        var $bwfd_fdname = "fd_coupon_datetime";	// 数据库中字段名称
        var $bwfd_title = "时间";	// 字段标题
}



if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = 1;  // 删除权限
$tb_feedback_b_bu->browse_addqx =1;  // 新增权限
$tb_feedback_b_bu->browse_editqx =1;  // 编辑权限
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

