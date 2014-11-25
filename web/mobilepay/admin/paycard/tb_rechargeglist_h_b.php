<?
$thismenucode = "2n502";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");


class tb_feedback_b extends browse 
{
	  var $prgnoware    = array("充值管理","充值支付历史");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_rechargelist_id";
	 

	 var $browse_queryselect = "select * 
	                            from tb_rechargeglist
	                            left join tb_paycard on fd_rechargelist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_rechargelist_authorid
	                            ";
	 var $browse_edit = "rechargeglist_sp.php?type=check&listid=" ;
       var $browse_editname = "查看";
	 var $browse_field = array("fd_rechargelist_no","fd_author_truename","fd_paycard_key","fd_rechargelist_bankcardno","fd_rechargelist_bankname","fd_rechargelist_bkntno","fd_rechargelist_money","fd_rechargelist_banktype","fd_rechargeglist_isagentpay","fd_rechargeglist_agentdate","fd_rechargelist_datetime");
 	 var $browse_find = array(		// 查询条件
				"0" => array("刷卡器编号", "fd_paycard_key","TXT"),
				"1" => array("用户名", "fd_author_truename","TXT"),
				"2" => array("充值日期", "fd_rechargelist_datetime","TXT"),
				
				);
	 
}

class fd_rechargelist_no extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_no";	// 数据库中字段名称
        var $bwfd_title = "流水号
";	// 字段标题
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "用户名";	// 字段标题
}

class fd_paycard_key extends browsefield {
        var $bwfd_fdname = "fd_paycard_key";	// 数据库中字段名称
        var $bwfd_title = "刷卡器设备号";	// 字段标题
}
class fd_rechargelist_bankcardno extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_bankcardno";	// 数据库中字段名称
        var $bwfd_title = "银行卡号";	// 字段标题
}
class fd_rechargelist_bankname extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_bankname";	// 数据库中字段名称
        var $bwfd_title = "所属银行";	// 字段标题
}
class fd_rechargelist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_bkntno";	// 数据库中字段名称
        var $bwfd_title = "银行流水号";	// 字段标题
}
class fd_rechargelist_money extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_money";	// 数据库中字段名称
        var $bwfd_title = "充值金额";	// 字段标题
}
class fd_rechargelist_banktype extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_banktype";	// 数据库中字段名称
        var $bwfd_title = "银行卡类型";	// 字段标题
		 function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "creditcard":
        		    $this->bwfd_show = "信用卡";
        		     break;       		
        		case "depositcard":
        		    $this->bwfd_show = "储蓄卡";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
			  }
}

class fd_rechargelist_datetime extends browsefield {
        var $bwfd_fdname = "fd_rechargelist_datetime";	// 数据库中字段名称
        var $bwfd_title = "充值日期";	// 字段标题
}

class fd_rechargeglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_rechargeglist_isagentpay";	// 数据库中字段名称
        var $bwfd_title = "是否已代付";	// 字段标题
             
		 function makeshow() {	// 将值转为显示值
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
					    
			if($transStatus == "0") {
        		$this->bwfd_show = "否";    		
        	}else if($transStatus == "1"){
        	  $this->bwfd_show = "<span style='color:#0000ff'>是</span>";
        	}
        		   	     
		      return $this->bwfd_show ;
  	  }
}

class fd_rechargeglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_rechargeglist_agentdate";	// 数据库中字段名称
        var $bwfd_title = "已代付日期";	// 字段标题
        
        function makeshow(){	
        	  $arrivetime = $this->bwfd_value;  
			  if($arrivetim==""){
				  $arrivetime = "-";
			  }else{
        	  	$arrivetime = substr($arrivetime,0,10);
			  }
        	     	  
        	  $this->bwfd_show = $arrivetime;        	     	               	       
		        return $this->bwfd_show ;
		    }  
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
//$tb_feedback_b_bu->browse_querywhere = "fd_ccglist_state = '9' and fd_ccglist_payrq='1'";
$tb_feedback_b_bu->browse_querywhere = "fd_rechargelist_payrq='00' and fd_rechargelist_state='9'";
$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

