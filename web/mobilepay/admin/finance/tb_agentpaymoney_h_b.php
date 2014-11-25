<?
$thismenucode = "2n605";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_paymoneylist_b extends browse {
	var $prgnoware = array ("资金代付管理", "代付款历史" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_pymylt_id";
	
	var $browse_queryselect = "select * from tb_paymoneylist";
	
	var $browse_link = array("lk_view0");
		
	var $browse_field = array ("fd_pymylt_no","fd_pymylt_dealwithman", "fd_pymylt_date", "fd_pymylt_paytype","fd_pymylt_fkdate", "fd_pymylt_money");
	var $browse_find = array (// 查询条件
                      "0" => array ("单据编号"    , "fd_pymylt_no"            , "TXT" ), 
                      "1" => array ("经手人"      , "fd_pymylt_dealwithman"   , "TXT" ), 
                      "2" => array ("单据日期"    , "fd_pymylt_date"          , "TXT" ), 
                      "3" => array ("付款日期"    , "fd_pymylt_fkdate"        , "TXT" ), 
                      "4" => array ("付款金额"    , "fd_pymylt_money"         , "TXT" ), 
                      );
}

class fd_pymylt_no extends browsefield {
	var $bwfd_fdname = "fd_pymylt_no"; // 数据库中字段名称
	var $bwfd_title = "单据编号"; // 字段标题
}


class fd_pymylt_dealwithman extends browsefield {
	var $bwfd_fdname = "fd_pymylt_dealwithman"; // 数据库中字段名称
	var $bwfd_title = "经手人"; // 字段标题
}

class fd_pymylt_date extends browsefield {
	var $bwfd_fdname = "fd_pymylt_date"; // 数据库中字段名称
	var $bwfd_title = "单据日期"; // 字段标题
}
class fd_pymylt_paytype extends browsefield {
	var $bwfd_fdname = "fd_pymylt_paytype"; // 数据库中字段名称
	var $bwfd_title = "代付款类型"; // 字段标题
	
	function makeshow() {	// 将值转为显示值
  	switch($this->bwfd_value){
  		case "coupon":
  		  $this->bwfd_show = "购买抵用券";
  		  break;
  		case "creditcard":
  		  $this->bwfd_show = "信用卡还款";
  		  break;
  		case "recharge":
  		  $this->bwfd_show = "充值";
  		  break;
  		case "repay":
  		  $this->bwfd_show = "还贷款";
  		  break;
		case "order":
  		  $this->bwfd_show = "订单付款";
  		  break;
  		case "tfmg":
  		  $this->bwfd_show = "转账汇款";
  		  break;
		default:
			$this->bwfd_show = "其他业务";
		break;
  	}
	  return $this->bwfd_show;
  }
	
}

class fd_pymylt_fkdate extends browsefield {
	var $bwfd_fdname = "fd_pymylt_fkdate"; // 数据库中字段名称
	var $bwfd_title = "付款日期"; // 字段标题
	
}

class fd_pymylt_money extends browsefield {
	var $bwfd_fdname = "fd_pymylt_money"; // 数据库中字段名称
	var $bwfd_title = "付款金额"; // 字段标题
	var $bwfd_align = "right";
}

class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_pymylt_id") 
   			    );
   var $bwlk_prgname = "paymoneylist_view.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看明细</span>";  
  
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

if (empty ( $order )) {
	$order = "fd_pymylt_date";
	$upordown = "desc";
}

$tb_paymoneylist_b_bu = new tb_paymoneylist_b ( );
$tb_paymoneylist_b_bu->browse_skin = $loginskin;
$tb_paymoneylist_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_paymoneylist_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_paymoneylist_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_paymoneylist_b_bu->browse_querywhere = "  fd_pymylt_state = '9'";

$tb_paymoneylist_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
