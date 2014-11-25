<?
$thismenucode = "2n604";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_paymoneylist_b extends browse {
	var $prgnoware = array ("资金代付管理", "代付款核对" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_pymylt_id";
	
	var $browse_queryselect = "select * from tb_paymoneylist";
	
	var $browse_edit   = "paymoneylist_hd.php?listid=";

	
	var $browse_state = array("fd_pymylt_state"); 
	
	var $browse_defaultorder = " CASE WHEN fd_pymylt_state = '3'
                                THEN 1                                  
                                WHEN fd_pymylt_state = '0'
                                THEN 3 
                                WHEN fd_pymylt_state = '1'
                                THEN 2
                                WHEN fd_pymylt_state = '2'
                                THEN 4                                 
                                END,fd_pymylt_state desc
                              ";	  
	
	var $browse_editname = "核对";
	
	function makeedit($key) {	// 生成编辑连接
  	  $returnval = "" ;
   	  switch($this->arr_spilthfield[0]){  //判断记录是在那一个状态下，在那一个状态下就使用那一个连接
   	  	case "3":
   	  	  if(!empty($this->browse_edit)){
  	        $returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\")><font color='#0000ff'>".$this->browse_editname."</font></a>" ;
  	      }
   	  	  break;
   	  	default:
   	  	  $returnval = "<a href=javascript:linkurl(\"paymoneylist_view.php?state=4&listid=".$key."\")>查看</a>" ;;	
   	  	  break;
   	  }
  	  return $returnval;
   }
		
	var $browse_field = array ("fd_pymylt_no","fd_pymylt_state","fd_pymylt_paytype","fd_pymylt_dealwithman", "fd_pymylt_date", "fd_pymylt_fkdate", "fd_pymylt_money");
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

class fd_pymylt_state extends browsefield {
	var $bwfd_fdname = "fd_pymylt_state"; // 数据库中字段名称
	var $bwfd_title = "状态"; // 字段标题
	
	function makeshow() {	// 将值转为显示值
  	switch($this->bwfd_value){
  		case "0":
  		  $this->bwfd_show = "代付款申请";
  		  break;
  		case "1":
  		  $this->bwfd_show = "代付款审批";
  		  break;
  		case "2":
  		  $this->bwfd_show = "代付款出账";
  		  break;
  		case "3":
  		  $this->bwfd_show = "代付款核对";
  		  break;
  	}
	  return $this->bwfd_show;
  }
	
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

class fd_pymylt_dealwithman extends browsefield {
	var $bwfd_fdname = "fd_pymylt_dealwithman"; // 数据库中字段名称
	var $bwfd_title = "经手人"; // 字段标题
}

class fd_pymylt_date extends browsefield {
	var $bwfd_fdname = "fd_pymylt_date"; // 数据库中字段名称
	var $bwfd_title = "单据日期"; // 字段标题
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
$tb_paymoneylist_b_bu->browse_querywhere = "  fd_pymylt_state != '9'";

$tb_paymoneylist_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>
