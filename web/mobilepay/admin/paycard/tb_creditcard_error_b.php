<?
$thismenucode = "2n503";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("信用卡还款管理","信用卡还款支付异常");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_ccglist_id";
	 

	 var $browse_queryselect = "select * ,fd_ccglist_id as tdcount 
	                            from tb_creditcardglist
	                            left join tb_paycard on fd_ccglist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_ccglist_authorid
	                            left join tb_sendcenter on fd_sdcr_id = fd_ccglist_sdcrid
	                            ";
	 var $browse_edit = "creditcard_sp.php?type=error&listid=" ;
   var $browse_editname = "查看";
	 var $browse_field = array("tdcount","fd_author_truename","fd_ccglist_shoucardno","fd_ccglist_fucardno","fd_ccglist_paymoney","fd_ccglist_payfee","fd_ccglist_money","fd_ccglist_sdcrpayfeemoney","fd_ccglist_payfeedirct","fd_ccglist_sdcragentfeemoney","fd_ccglist_arrivedate","fd_sdcr_name","fd_ccglist_paydate","fd_paycard_no","fd_ccglist_memo","fd_ccglist_bkordernumber","fd_ccglist_bkntno","fd_ccglist_isagentpay","fd_ccglist_agentdate");
 	 
 	 var $browse_hj = array("fd_ccglist_paymoney","fd_ccglist_payfee","fd_ccglist_money","fd_ccglist_sdcrpayfeemoney","fd_ccglist_sdcragentfeemoney");
 	 
 	 var $browse_find = array(		// 查询条件
				"0" => array("刷卡器编号", "fd_paycard_no","TXT"),
				"1" => array("用户名", "fd_author_truename","TXT"),
				"2" => array("交易日期", "fd_ccglist_paydate","TXT"),
				"3" => array("银行交易流水号", "fd_ccglist_bkntno","TXT"),	
				"4" => array("付款卡号", "fd_ccglist_fucardno","TXT"),	
				"5" => array("还款卡号", "fd_ccglist_shoucardno","TXT"),
				
				);
	 
}

$tdcount  = 0;

class tdcount  extends browsefield {
        var $bwfd_fdname = "tdcount";	// 数据库中字段名称
        var $bwfd_title = "序号";	// 字段标题
        
        function makeshow(){	
        	global $tdcount;   
        	$tdcount++;        	
        	       	                  
          $showvalue = $tdcount;       	       
          	
        	$this->bwfd_show = $showvalue;       	        	

		      return $this->bwfd_show ;
		    }
}

class fd_ccglist_bkordernumber extends browsefield {
        var $bwfd_fdname = "fd_ccglist_bkordernumber";	// 数据库中字段名称
        var $bwfd_title = "银联交易流水号";	// 字段标题
}

class fd_ccglist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_ccglist_bkntno";	// 数据库中字段名称
        var $bwfd_title = "银联交易请求码";	// 字段标题
}

class fd_paycard_no extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// 数据库中字段名称
        var $bwfd_title = "刷卡器设备号";	// 字段标题
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "用户名
";	// 字段标题
}

class fd_ccglist_paydate extends browsefield {
        var $bwfd_fdname = "fd_ccglist_paydate";	// 数据库中字段名称
        var $bwfd_title = "交易日期
";	// 字段标题
}
class fd_ccglist_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_ccglist_shoucardno";	// 数据库中字段名称
        var $bwfd_title = "还款卡号

";	// 字段标题
}
class fd_ccglist_fucardno extends browsefield {
        var $bwfd_fdname = "fd_ccglist_fucardno";	// 数据库中字段名称
        var $bwfd_title = "付款卡号
";	// 字段标题
}

class fd_ccglist_paymoney extends browsefield {
        var $bwfd_fdname = "fd_ccglist_paymoney";	// 数据库中字段名称
        var $bwfd_title = "交易本金";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// 数据库中字段名称
        var $bwfd_title = "所属商户";	// 字段标题
}   


class fd_ccglist_payfee extends browsefield {
        var $bwfd_fdname = "fd_ccglist_payfee";	// 数据库中字段名称
        var $bwfd_title = "收取终端<br>商户手续费";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_money extends browsefield {
        var $bwfd_fdname = "fd_ccglist_money";	// 数据库中字段名称
        var $bwfd_title = "交易总额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_sdcrpayfeemoney extends browsefield {
        var $bwfd_fdname = "fd_ccglist_sdcrpayfeemoney";	// 数据库中字段名称
        var $bwfd_title = "银联收取<br>手续费";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_sdcragentfeemoney extends browsefield {
        var $bwfd_fdname = "fd_ccglist_sdcragentfeemoney";	// 数据库中字段名称
        var $bwfd_title = "资金代付金额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_ccglist_arrivedate extends browsefield {
        var $bwfd_fdname = "fd_ccglist_arrivedate";	// 数据库中字段名称
        var $bwfd_title = "预计代付日期";	// 字段标题
        
        function makeshow(){	
        	  $arrivedate = $this->bwfd_value;        	         	  
        	  $arrivedate = substr($arrivedate,0,10);
        	     	  
        	  $this->bwfd_show = $arrivedate;        	     	               	       
		        return $this->bwfd_show ;
		    }  
}

class fd_ccglist_payfeedirct extends browsefield {
        var $bwfd_fdname = "fd_ccglist_payfeedirct";	// 数据库中字段名称
        var $bwfd_title = "终端商户手<br>续费是否到账";	// 字段标题
             
		 function makeshow() {	// 将值转为显示值
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "s") {
        		$this->bwfd_show = "否";    		
        	}else if($transStatus == "f"){
        	  $this->bwfd_show = "<span style='color:#0000ff'>是</span>";
        	}
        		   	     
		      return $this->bwfd_show ;
  	  }
}




class fd_ccglist_memo extends browsefield {
        var $bwfd_fdname = "fd_ccglist_memo";	// 数据库中字段名称
        var $bwfd_title = "交易摘要";	// 字段标题
}

class fd_ccglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_ccglist_isagentpay";	// 数据库中字段名称
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

class fd_ccglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_ccglist_agentdate";	// 数据库中字段名称
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
$tb_feedback_b_bu->browse_querywhere = "fd_ccglist_payrq='03'";


$db = new DB_test ;
global $fd_ccglist_paymoney,$fd_ccglist_payfee,$fd_ccglist_money,$fd_ccglist_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_ccglist_paymoney,fd_ccglist_payfee,fd_ccglist_money,fd_ccglist_sdcrpayfeemoney,fd_ccglist_sdcragentfeemoney
          from tb_creditcardglist ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_ccglist_paymoney += $db->f(fd_ccglist_paymoney);   
     $fd_ccglist_payfee += $db->f(fd_ccglist_payfee);   
     $fd_ccglist_money += $db->f(fd_ccglist_money);   
     $fd_ccglist_sdcrpayfeemoney += $db->f(fd_ccglist_sdcrpayfeemoney);   
     $fd_ccglist_sdcragentfeemoney += $db->f(fd_ccglist_sdcragentfeemoney);  
}

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

