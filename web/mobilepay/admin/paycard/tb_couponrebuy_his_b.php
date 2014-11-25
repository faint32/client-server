<?
$thismenucode = "8n004";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("优惠劵管理","优惠劵回购历史");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_couponrebuy_id";
	 
  var $browse_link  = array("lk_view0");
	 var $browse_queryselect = "select fd_couponrebuy_id,fd_couponrebuy_no,date_format(fd_couponrebuy_datetime,'%Y-%m-%d') as fd_couponrebuy_datetime,
	                             fd_couponrebuy_money,fd_author_username,fd_author_truename,fd_bank_name,fd_couponrebuy_bankcardno,
	                             fd_author_idcard,fd_couponrebuy_czman, fd_couponrebuy_czdatetime,fd_couponrebuy_couponno
	                            from tb_couponrebuy
	                            left join tb_couponsale on fd_couponsale_id = fd_couponrebuy_couponid
	                            left join tb_bank on fd_couponrebuy_bankid = fd_bank_id
	                            left join tb_author on fd_author_id = fd_couponrebuy_authorid
	                            ";
	 var $browse_field = array("fd_couponrebuy_no","fd_couponrebuy_couponno","fd_couponrebuy_money","fd_author_username","fd_author_truename","fd_author_idcard","fd_bank_name","fd_couponrebuy_bankcardno","fd_couponrebuy_datetime","fd_couponrebuy_czman","fd_couponrebuy_czdatetime");
 	 var $browse_find = array(		// 查询条件
				"0" => array("兑换单号", "fd_couponrebuy_no","TXT"),
				"1" => array("优惠劵号", "fd_couponrebuy_couponno","TXT"),
				"2" => array("兑换金额", "fd_couponrebuy_money","TXT"),
				"3" => array("用户账号", "fd_author_username","TXT"),
				"4" => array("用户姓名", "fd_author_truename","TXT"),
				"5" => array("身份证号", "fd_author_idcard","TXT"),
				"6" => array("开户银行", "fd_bank_name","TXT"),
				"7" => array("银行账户", "fd_couponrebuy_bankcardno","TXT"),
				"8" => array("单据日期", "fd_couponrebuy_datetime","TXT"),
				"9" => array("操作人", "fd_couponrebuy_czman","TXT"),
				"10" => array("操作日期", "fd_couponrebuy_czdatetime","TXT"),
				);
	 
}

class fd_couponrebuy_no extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_no";	// 数据库中字段名称
        var $bwfd_title = "订单号";	// 字段标题
}

class fd_couponrebuy_couponno extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_couponno";	// 数据库中字段名称
        var $bwfd_title = "优惠劵号";	// 字段标题
}

class fd_couponrebuy_money extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_money";	// 数据库中字段名称
        var $bwfd_title = "兑换金额";	// 字段标题
}
class fd_author_username extends browsefield {
        var $bwfd_fdname = "fd_author_username";	// 数据库中字段名称
        var $bwfd_title = "用户账号";	// 字段标题
}
class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "用户姓名";	// 字段标题
}
class fd_author_idcard extends browsefield {
        var $bwfd_fdname = "fd_author_idcard";	// 数据库中字段名称
        var $bwfd_title = "身份证号";	// 字段标题
        var $bwfd_format = "idcard";
}
class fd_bank_name extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// 数据库中字段名称
        var $bwfd_title = "开户银行";	// 字段标题
}
class fd_couponrebuy_bankcardno extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_bankcardno";	// 数据库中字段名称
        var $bwfd_title = "银行账户";	// 字段标题
}
class fd_couponrebuy_datetime extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_datetime";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}
class fd_couponrebuy_czman extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_czman";	// 数据库中字段名称
        var $bwfd_title = "操作人";	// 字段标题
}
class fd_couponrebuy_czdatetime extends browsefield {
        var $bwfd_fdname = "fd_couponrebuy_czdatetime";	// 数据库中字段名称
        var $bwfd_title = "操作日期";	// 字段标题
}
// 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_couponrebuy_id") 
   			    );
   var $bwlk_prgname = "couponrebuy_view.php?listid=";
   var $bwlk_title ="查看明细";  
}
if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_feedback_b_bu->browse_querywhere = "fd_couponrebuy_state = 9";
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

