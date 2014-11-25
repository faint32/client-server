<?
$thismenucode = "2n201";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("还贷款管理","还贷款审核");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_repmglist_id";
	 

	 var $browse_queryselect = "select * ,fd_repmglist_id as tdcount
	                            from tb_repaymoneyglist
	                            left join tb_paycard on fd_repmglist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_repmglist_authorid
	                            left join tb_sendcenter on fd_sdcr_id = fd_repmglist_sdcrid
	                           ";
	 var $browse_edit = "repaymoney_sp.php?listid=" ;
	 
   var $browse_editname = "审批";
    
   var $browse_state = array("fd_repmglist_payrq");
   var $browse_defaultorder = " fd_repmglist_paydate desc
                             ";	  
   
   

   
	 function makeedit($key) {	// 生成编辑连接
	   $returnval = "" ;
	   switch($this->arr_spilthfield[0]){  //判断记录是在那一个状态下，在那一个状态下就使用那一个连接
	  	
	 	case "00":
	 	  if(!empty($this->browse_edit)){
	 		$returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
	 	  }
	 	  break;
	 	default:
	 	  $returnval = "<a href=javascript:linkurl(\"repaymoney_sp.php?type=error&gotype=sp&listid=".$key."\")>查看</a>" ;;	
	 	  break;
	   }
	   return $returnval;
	 }
	 
	 
	 var $browse_field = array("tdcount","fd_repmglist_payrq","fd_author_truename","fd_repmglist_paydate","fd_repmglist_paymoney","fd_repmglist_payfee","fd_repmglist_money","fd_repmglist_sdcrpayfeemoney","fd_repmglist_payfeedirct","fd_repmglist_sdcragentfeemoney","fd_repmglist_arrivetime","fd_sdcr_name","fd_paycard_no","fd_repmglist_fucardno","fd_repmglist_fucardbank","fd_repmglist_shoucardno","fd_repmglist_shoucardbank","fd_repmglist_memo","fd_repmglist_bkordernumber","fd_repmglist_bkntno","fd_repmglist_isagentpay","fd_repmglist_agentdate");
 	 
 	 var $browse_hj = array("fd_repmglist_paymoney","fd_repmglist_payfee","fd_repmglist_money","fd_repmglist_sdcrpayfeemoney","fd_repmglist_sdcragentfeemoney");
 	 
 	 var $browse_find = array(		// 查询条件
				"0" => array("刷卡器编号", "fd_paycard_no","TXT"),
				"1" => array("用户名", "fd_author_truename","TXT"),
				"2" => array("交易日期", "fd_repmglist_paydate","TXT"),
				"3" => array("银联交易请求码", "fd_repmglist_bkntno","TXT"),
				"4" => array("预计代付日期", "fd_repmglist_arrivetime","TXT"),
				"5" => array("银联交易流水号", "fd_repmglist_bkordernumber","TXT"),
				"6" => array("已代付日期", "fd_repmglist_agentdate","TXT"),	
				"7" => array("付款卡号", "fd_repmglist_fucardno","TXT"),	
				"8" => array("还款卡号", "fd_repmglist_shoucardno","TXT"),		
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




class fd_repmglist_bkordernumber extends browsefield {
        var $bwfd_fdname = "fd_repmglist_bkordernumber";	// 数据库中字段名称
        var $bwfd_title = "银联交易流水号";	// 字段标题
}

class fd_repmglist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_repmglist_bkntno";	// 数据库中字段名称
        var $bwfd_title = "银联交易请求码";	// 字段标题
}


class fd_paycard_no extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// 数据库中字段名称
        var $bwfd_title = "刷卡器设备号";	// 字段标题
}

class fd_author_truename extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "用户名";	// 字段标题
}

class fd_repmglist_paydate extends browsefield {
        var $bwfd_fdname = "fd_repmglist_paydate";	// 数据库中字段名称
        var $bwfd_title = "交易日期";	// 字段标题
}
class fd_repmglist_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_repmglist_shoucardno";	// 数据库中字段名称
        var $bwfd_title = "还款卡号";	// 字段标题
}

class fd_repmglist_shoucardbank extends browsefield {
        var $bwfd_fdname = "fd_repmglist_shoucardbank";	// 数据库中字段名称
        var $bwfd_title = "还款卡所属银行";	// 字段标题
} 

class fd_repmglist_fucardno extends browsefield {
        var $bwfd_fdname = "fd_repmglist_fucardno";	// 数据库中字段名称
        var $bwfd_title = "付款卡号";	// 字段标题
}   

class fd_repmglist_fucardbank extends browsefield {
        var $bwfd_fdname = "fd_repmglist_fucardbank";	// 数据库中字段名称
        var $bwfd_title = "付款卡所属银行";	// 字段标题
}   

class fd_repmglist_paymoney extends browsefield {
        var $bwfd_fdname = "fd_repmglist_paymoney";	// 数据库中字段名称
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


class fd_repmglist_payfee extends browsefield {
        var $bwfd_fdname = "fd_repmglist_payfee";	// 数据库中字段名称
        var $bwfd_title = "收取终端<br>商户手续费";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_money extends browsefield {
        var $bwfd_fdname = "fd_repmglist_money";	// 数据库中字段名称
        var $bwfd_title = "交易总额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_sdcrpayfeemoney extends browsefield {
        var $bwfd_fdname = "fd_repmglist_sdcrpayfeemoney";	// 数据库中字段名称
        var $bwfd_title = "银联收取<br>手续费";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_sdcragentfeemoney extends browsefield {
        var $bwfd_fdname = "fd_repmglist_sdcragentfeemoney";	// 数据库中字段名称
        var $bwfd_title = "资金代付金额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_repmglist_arrivetime extends browsefield {
        var $bwfd_fdname = "fd_repmglist_arrivetime";	// 数据库中字段名称
        var $bwfd_title = "预计代付日期";	// 字段标题
        
        function makeshow(){	
        	  $arrivetime = $this->bwfd_value;        	         	  
        	  $arrivetime = substr($arrivetime,0,10);
        	     	  
        	  $this->bwfd_show = $arrivetime;        	     	               	       
		        return $this->bwfd_show ;
		    }  
}


 
class fd_repmglist_payrq extends browsefield {
        var $bwfd_fdname = "fd_repmglist_payrq";	// 数据库中字段名称
        var $bwfd_title = "交易状态";	// 字段标题
             
		 function makeshow() {	// 将值转为显示值
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "01") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>请求交易</span>";    		
        	 	}else if($transStatus == "00"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>交易完成</span>";
        		 }
        		 else if($transStatus == "03"){
        	  		 $this->bwfd_show = "<span style='color=#ff0000'>交易取消</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}

class fd_repmglist_payfeedirct extends browsefield {
        var $bwfd_fdname = "fd_repmglist_payfeedirct";	// 数据库中字段名称
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

class fd_repmglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_repmglist_isagentpay";	// 数据库中字段名称
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

class fd_repmglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_repmglist_agentdate";	// 数据库中字段名称
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



class fd_repmglist_memo extends browsefield {
        var $bwfd_fdname = "fd_repmglist_memo";	// 数据库中字段名称
        var $bwfd_title = "交易摘要";	// 字段标题
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = 100 ;
}

$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_feedback_b_bu->browse_querywhere = "fd_repmglist_state= '0'";

if(!empty($seldofile)){
  $tb_feedback_b_bu->browse_querywhere .= " and fd_repmglist_payrq = '$seldofile' ";
  $tb_feedback_b_bu->browse_haveselectvalue = $seldofile ;   //选中的值
}else{
  $tb_feedback_b_bu->browse_querywhere .= " and fd_repmglist_payrq = '00'";
  $tb_feedback_b_bu->browse_haveselectvalue = "00";
}

$tb_feedback_b_bu->browse_firstval = "显示全部";

$tb_feedback_b_bu->browse_seldofile = array("交易完成","请求交易","交易取消");
$tb_feedback_b_bu->browse_seldofileval = array("00","01","03");


$db = new DB_test ;
global $fd_repmglist_paymoney,$fd_repmglist_payfee,$fd_repmglist_money,$fd_repmglist_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_repmglist_paymoney,fd_repmglist_payfee,fd_repmglist_money,fd_repmglist_sdcrpayfeemoney,fd_repmglist_sdcragentfeemoney
          from tb_repaymoneyglist ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_repmglist_paymoney += $db->f(fd_repmglist_paymoney);   
     $fd_repmglist_payfee += $db->f(fd_repmglist_payfee);   
     $fd_repmglist_money += $db->f(fd_repmglist_money);   
     $fd_repmglist_sdcrpayfeemoney += $db->f(fd_repmglist_sdcrpayfeemoney);   
     $fd_repmglist_sdcragentfeemoney += $db->f(fd_repmglist_sdcragentfeemoney);  
}


$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

