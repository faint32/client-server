<?
$thismenucode = "2n302";
require ("../include/common.inc.php");
require ("../include/browse_amount.inc.php");

class tb_feedback_b extends browse 
{
	 var $prgnoware    = array("抵用劵管理","抵用劵购买审核");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_couponsale_id";

	 var $browse_queryselect = "select *,fd_couponsale_id as tdcount
	                            from tb_couponsale
	                            left join tb_paycard on fd_couponsale_paycardid = fd_paycard_id
	                            left join tb_author on fd_author_id = fd_couponsale_authorid
	                            left join tb_sendcenter on fd_sdcr_id = fd_couponsale_sdcrid
	                           ";
	                            
	 var $browse_edit = "couponsale.php?listid=" ;
	 
   	 var $browse_editname = "审核";
     
     var $browse_state = array("fd_couponsale_payrq");
   
     var $browse_defaultorder = " fd_couponsale_datetime desc
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
				  $returnval = "<a href=javascript:linkurl(\"couponsale_error.php?gotype=sp&listid=".$key."\")>查看</a>" ;;	
				  break;
			  }
			  return $returnval;
			} 

	 var $browse_field = array("tdcount","fd_couponsale_payrq","fd_author_truename","fd_author_username","fd_author_idcard","fd_couponsale_paymoney","fd_couponsale_payfee","fd_couponsale_money","fd_couponsale_sdcrpayfeemoney","fd_couponsale_payfeedirct","fd_sdcr_name","fd_couponsale_datetime","fd_paycard_no","fd_couponsale_creditcardno","fd_couponsale_creditcardbank","fd_couponsale_bkordernumber","fd_couponsale_bkntno","fd_couponsale_isagentpay","fd_couponsale_agentdate");
 	 
 	 var $browse_hj = array("fd_couponsale_paymoney","fd_couponsale_payfee","fd_couponsale_money","fd_couponsale_sdcrpayfeemoney");
 	 
 	 var $browse_find = array(		// 查询条件				
				"0" => array("抵用劵金额", "fd_couponsale_money","TXT"),
				"1" => array("刷卡器号", "fd_paycard_no","TXT"),
				"2" => array("用户账号", "fd_author_username","TXT"),
				"3" => array("用户姓名", "fd_author_truename","TXT"),
				"4" => array("身份证号", "fd_author_idcard","TXT"),
				"5" => array("单据日期", "fd_couponsale_datetime","TXT"),
				"6" => array("银行交易流水号", "fd_couponsale_bkordernumber","TXT")
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



class fd_couponsale_bkntno extends browsefield {
        var $bwfd_fdname = "fd_couponsale_bkntno";	// 数据库中字段名称
        var $bwfd_title = "银联交易请求码";	// 字段标题
}

class fd_couponsale_bkordernumber extends browsefield {
        var $bwfd_fdname = "fd_couponsale_bkordernumber";	// 数据库中字段名称
        var $bwfd_title = "银联交易流水号";	// 字段标题
}

class fd_couponsale_paymoney extends browsefield {
        var $bwfd_fdname = "fd_couponsale_paymoney";	// 数据库中字段名称
        var $bwfd_title = "交易本金";	// 字段标题
        
        function makeshow(){	           	        	        		               	       
        	$this->bwfd_align  = "right";    
		      return $this->bwfd_value ;
		    }     
}

class fd_sdcr_name extends browsefield {
        var $bwfd_fdname = "fd_sdcr_name";	// 数据库中字段名称
        var $bwfd_title = "所属商户";	// 字段标题
} 

class fd_couponsale_payfee extends browsefield {
        var $bwfd_fdname = "fd_couponsale_payfee";	// 数据库中字段名称
        var $bwfd_title = "收取终端<br>商户手续费";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_couponsale_money extends browsefield {
        var $bwfd_fdname = "fd_couponsale_money";	// 数据库中字段名称
        var $bwfd_title = "交易总额";	// 字段标题
        
        function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		    }    
}

class fd_couponsale_sdcrpayfeemoney  extends browsefield {
     var $bwfd_fdname = "fd_couponsale_sdcrpayfeemoney";	// 数据库中字段名称
     var $bwfd_title = "银联收取<br>手续费";	// 字段标题
             
		 function makeshow(){	
        	  $money = $this->bwfd_value;        	         	  
        	  $money = number_format($money, 2, ".", "");         	  
        	  $this->bwfd_show = $money;        	     	               	       
        	  $this->bwfd_align  = "right";    
		        return $this->bwfd_show ;
		 }  
}

class fd_couponsale_payfeedirct extends browsefield {
        var $bwfd_fdname = "fd_couponsale_payfeedirct";	// 数据库中字段名称
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


class fd_paycard_no extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// 数据库中字段名称
        var $bwfd_title = "刷卡器号";	// 字段标题
}

class fd_couponsale_creditcardno extends browsefield {
        var $bwfd_fdname = "fd_couponsale_creditcardno";	// 数据库中字段名称
        var $bwfd_title = "银行卡号";	// 字段标题
}

class fd_couponsale_creditcardbank extends browsefield {
        var $bwfd_fdname = "fd_couponsale_creditcardbank";	// 数据库中字段名称
        var $bwfd_title = "刷卡银行";	// 字段标题
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


class fd_couponsale_payrq extends browsefield {
        var $bwfd_fdname = "fd_couponsale_payrq";	// 数据库中字段名称
        var $bwfd_title = "交易状态";	// 字段标题
             
		 function makeshow() {	// 将值转为显示值
        	  global $loginorganid; 
			     //$this->var = explode(",",$this->bwfd_value);
			     $transStatus = $this->bwfd_value;
			
			    if($transStatus == "01") {
        		 	$this->bwfd_show = "<span style='color=#ff0000'>请求交易</span>";    		
        	 	}else if($transStatus == "00"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>交易完成</span>";
        		 }
        		 else if($transStatus == "03"){
        	  		 $this->bwfd_show = "<span style='color=#0000ff'>交易取消</span>";
        		 }   	     
		       return $this->bwfd_show ;
  	  }
}




class fd_couponsale_datetime extends browsefield {
        var $bwfd_fdname = "fd_couponsale_datetime";	// 数据库中字段名称
        var $bwfd_title = "单据日期";	// 字段标题
}

class fd_couponsale_isagentpay extends browsefield {
        var $bwfd_fdname = "fd_couponsale_isagentpay";	// 数据库中字段名称
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

class fd_couponsale_agentdate extends browsefield {
        var $bwfd_fdname = "fd_couponsale_agentdate";	// 数据库中字段名称
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
  $pagerows = 100;
}


$tb_feedback_b_bu = new tb_feedback_b ;
$tb_feedback_b_bu->browse_skin = $loginskin ;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3];  // 删除权限
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1];  // 新增权限
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2];  // 编辑权限
$tb_feedback_b_bu->browse_querywhere = "fd_couponsale_state = 0";


if(!empty($seldofile)){
  $tb_feedback_b_bu->browse_querywhere .= " and fd_couponsale_payrq = '$seldofile' ";
  $tb_feedback_b_bu->browse_haveselectvalue = $seldofile ;   //选中的值
}else{
  $tb_feedback_b_bu->browse_querywhere .= " and fd_couponsale_payrq = '00' ";
  $tb_feedback_b_bu->browse_haveselectvalue = "00";
}

$tb_feedback_b_bu->browse_firstval = "显示全部";

$tb_feedback_b_bu->browse_seldofile = array("交易完成","请求交易","交易取消");
$tb_feedback_b_bu->browse_seldofileval = array("00","01","03");


$db = new DB_test ;
global $fd_couponsale_paymoney,$fd_couponsale_payfee,$fd_couponsale_money,$fd_couponsale_sdcrpayfeemoney; 

if(!empty($tb_feedback_b_bu->browse_querywhere)){
  $query_str = "where ";
}
                 
$query = "select fd_couponsale_paymoney,fd_couponsale_payfee,fd_couponsale_money,fd_couponsale_sdcrpayfeemoney
          from tb_couponsale ".$query_str.$tb_feedback_b_bu->browse_querywhere." 
         ";
$db->query($query); 
while($db->next_record()){
     $fd_couponsale_paymoney += $db->f(fd_couponsale_paymoney);   
     $fd_couponsale_payfee += $db->f(fd_couponsale_payfee);   
     $fd_couponsale_money += $db->f(fd_couponsale_money);   
     $fd_couponsale_sdcrpayfeemoney += $db->f(fd_couponsale_sdcrpayfeemoney);   
}


$tb_feedback_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>

