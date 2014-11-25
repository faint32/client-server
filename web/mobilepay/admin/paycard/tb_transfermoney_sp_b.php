<?
$thismenucode = "2n401";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");


class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("转账管理","转账汇款支付复核");
	 var $prgnowareurl =  array("","");
	 var $browse_key = "fd_tfmglist_id";			
	 var $browse_queryselect = "select * 
	                            from tb_transfermoneyglist
	                            left join tb_paycard on fd_tfmglist_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_tfmglist_authorid
	                            ";
	 var $browse_edit = "transfermoney_sp.php?listid=" ;
    var $browse_editname = "审核";
	 

	 
	var $browse_defaultorder = " fd_tfmglist_paydate desc,fd_tfmglist_payrq desc
                             ";	  	  
  
   var $browse_state = array("fd_tfmglist_payrq");
   	   function makeedit($key) {	// 生成编辑连接
			  $returnval = "" ;
			  switch($this->arr_spilthfield[0]){  //判断记录是在那一个状态下，在那一个状态下就使用那一个连接
				case "0":
				  if(!empty($this->browse_edit)){
					$returnval = "<a href=javascript:linkurl(\"".$this->browse_edit.$key."\") style='color:#0000ff'>".$this->browse_editname."</a>" ;
				  }
				  break;
				default:
				  $returnval = "<a href=javascript:linkurl(\"transfermoney_sp.php?type=error&gotype=sp&listid=".$key."\")>查看</a>" ;;	
				  break;
			  }
			  return $returnval;
			}
   var $browse_field = array("fd_tfmglist_bkordernumber","fd_tfmglist_paytype","fd_tfmglist_bkntno","fd_paycard_no","fd_author_truename","fd_tfmglist_paymoney","fd_tfmglist_payfee","fd_tfmglist_money","fd_tfmglist_sdcrpayfeemoney","fd_tfmglist_sdcragentfeemoney","fd_tfmglist_paydate","fd_tfmglist_payrq","fd_tfmglist_shoucardno","fd_tfmglist_fucardno","fd_tfmglist_current","fd_tfmglist_memo","fd_tfmglist_isagentpay","fd_tfmglist_agentdate");
   var $browse_hj = array("fd_tfmglist_paymoney","fd_tfmglist_payfee","fd_tfmglist_money","fd_tfmglist_sdcragentfeemoney","fd_tfmglist_sdcrpayfeemoney");
 	 var $browse_find = array(		// 查询条件
				"0" => array("刷卡器编号", "fd_paycard_no","TXT"),
				"1" => array("用户名", "fd_author_truename","TXT"),
				"2" => array("交易日期", "fd_tfmglist_paydate","TXT"),
				"3" => array("银行交易流水号", "fd_tfmglist_bkntno","TXT"),
				"4" => array("付款卡号", "fd_tfmglist_fucardno","TXT"),	
				"5" => array("还款卡号", "fd_tfmglist_shoucardno","TXT")				
				);

	 
	 
}

class fd_tfmglist_bkordernumber  extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_bkordernumber";	// 数据库中字段名称
        var $bwfd_title = "流水号
";	// 字段标题
}
class fd_tfmglist_bkntno extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_bkntno";	// 数据库中字段名称
        var $bwfd_title = "银行交易流水号
";	// 字段标题
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

class fd_tfmglist_paymoney extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_paymoney";	// 数据库中字段名称
        var $bwfd_title = "交易本金";	// 字段标题
		function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    } 
}
class fd_tfmglist_payfee extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_payfee";	// 数据库中字段名称
        var $bwfd_title = "收取终端<br>商户手续费";	// 字段标题
		function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }   
}

class fd_tfmglist_money extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_money";	// 数据库中字段名称
        var $bwfd_title = "交易总额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_tfmglist_sdcrpayfeemoney extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_sdcrpayfeemoney";	// 数据库中字段名称
        var $bwfd_title = "银联收取<br>手续费";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_tfmglist_sdcragentfeemoney extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_sdcragentfeemoney";	// 数据库中字段名称
        var $bwfd_title = "资金代付金额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_tfmglist_paydate extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_paydate";	// 数据库中字段名称
        var $bwfd_title = "交易日期
";	// 字段标题
}

class fd_tfmglist_paytype extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_paytype";	// 数据库中字段名称
        var $bwfd_title = "转账类型";	// 字段标题
             
		 function makeshow() {	// 将值转为显示值
        
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "tfmg") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>普通转账</span>";    		
        	 	}else if($transStatus == "suptfmg"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>超级转账</span>";
        		 }
        		 else{
        	  		 $this->bwfd_show = "<span style='color=#ff0000'>普通转账</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}
class fd_tfmglist_payrq extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_payrq";	// 数据库中字段名称
        var $bwfd_title = "交易状态";	// 字段标题
             
		 function makeshow() {	// 将值转为显示值
          global $loginorganid; 
			    $transStatus = $this->bwfd_value;
			    
			    if($transStatus == "1") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>请求交易</span>";    		
        	 	}else if($transStatus == "0"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>交易完成</span>";
        		 }
        		 else if($transStatus == "3"){
        	  		 $this->bwfd_show = "<span style='color=#ff0000'>交易取消</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}
class fd_tfmglist_shoucardno extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_shoucardno";	// 数据库中字段名称
        var $bwfd_title = "还款卡号

";	// 字段标题
}
class fd_tfmglist_fucardno extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_fucardno";	// 数据库中字段名称
        var $bwfd_title = "付款卡号
";	// 字段标题
}
class fd_tfmglist_current extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_current";	// 数据库中字段名称
        var $bwfd_title = "币种";	// 字段标题
}

class fd_tfmglist_memo extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_memo";	// 数据库中字段名称
        var $bwfd_title = "交易摘要";	// 字段标题
}

class fd_tfmglist_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_isagentpay";	// 数据库中字段名称
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

class fd_tfmglist_agentdate extends browsefield {
        var $bwfd_fdname = "fd_tfmglist_agentdate";	// 数据库中字段名称
        var $bwfd_title = "已代付日期";	// 字段标题
        
        function makeshow(){	
        	  $arrivetime = $this->bwfd_value;  
			  if($arrivetime==""){
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
if(empty($order)){
	$order = "fd_tfmglist_paydate";
	$upordown = "desc";
}
$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_feedback_b_bu->browse_querywhere = "fd_tfmglist_state = '0'";
if(!empty($seldofile)){
  $tb_feedback_b_bu->browse_querywhere .= " and fd_tfmglist_payrq = '$seldofile' ";
  $tb_feedback_b_bu->browse_haveselectvalue = $seldofile ;   //选中的值
}else{
  $tb_feedback_b_bu->browse_querywhere .= " and fd_tfmglist_payrq = '00' ";
  $tb_feedback_b_bu->browse_haveselectvalue = "00";
}

$tb_feedback_b_bu->browse_firstval = "显示全部";

$tb_feedback_b_bu->browse_seldofile = array("交易完成","请求交易","交易取消");
$tb_feedback_b_bu->browse_seldofileval = array("00","01","03");

$db = new DB_test ;
global $fd_tfmglist_paymoney,$fd_tfmglist_payfee,$fd_tfmglist_money,$fd_tfmglist_sdcragentfeemoney,$fd_tfmglist_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_tfmglist_paymoney,fd_tfmglist_payfee,fd_tfmglist_sdcragentfeemoney,fd_tfmglist_sdcrpayfeemoney,fd_tfmglist_money
          from tb_transfermoneyglist ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_tfmglist_paymoney += $db->f(fd_tfmglist_paymoney);   
     $fd_tfmglist_payfee += $db->f(fd_tfmglist_payfee);
	 $fd_tfmglist_money += $db->f(fd_tfmglist_money);   
     $fd_tfmglist_sdcragentfeemoney += $db->f(fd_tfmglist_sdcragentfeemoney);   
	 $fd_tfmglist_sdcrpayfeemoney += $db->f(fd_tfmglist_sdcrpayfeemoney);      
}

$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

