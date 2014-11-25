<?
require ("../include/common.inc.php");
require ("../include/findbrowse.inc.php");

class tb_customer_b extends findbrowse{
	 var $prgname = "客户选择" ;

	 var $brow_key = "fd_cus_id";
	 var $brow_queryselect = "select fd_cus_id , fd_cus_no , fd_cus_name , fd_cus_allname,fd_cus_custype , fd_cus_bank , fd_cus_account  from tb_customer";
	 var $brow_field = array("fd_cus_no","fd_cus_name","fd_cus_allname","fd_cus_custype");
   var $brow_getvalue = array(			// 所需数据库中字段名称
   			    "0" => "fd_cus_id",
   			    "1" => "fd_cus_no",
   			    "2" => "fd_cus_allname",
   			    "3" => "fd_cus_name",
   			    "4" => "fd_cus_bank",
   			    "5" => "fd_cus_account"
   			   );  
  
    function makefindsql($whatdofind,$howdofind,$findwhat){
      $show = "";
      $findwhat =strval($findwhat);
      if((!empty($whatdofind))&&(!empty($howdofind))&&(!empty($findwhat) or $findwhat==0)) {
      	if($whatdofind=="fd_cus_custype"){
      	  switch($findwhat){
      	  	case "零售":
      	  	   $findwhat = 0;
      	  	   break;
      	  	case "批发":
      	  	   $findwhat = 1;
      	  	   break;
      	  	case "大宗":
      	  	   $findwhat = 2;
      	  	   break;
      	  }
      	}
      	if($howdofind=="like" or $howdofind=="not like"){
           $show = $whatdofind." ".$howdofind." '%".$findwhat."%'";
        }else{
        	  $show = $whatdofind." ".$howdofind." '".$findwhat."'";
        }
      }
      
      return $show ;
   }
   			    
	 var $brow_find = array(		// 查询条件
			  "0" => array("编号", "fd_cus_no","TXT"),
			  "1" => array("名称", "fd_cus_name","TXT"),
			  "2" => array("全称", "fd_cus_allname","TXT"),
			   "3" => array("客户类型", "fd_cus_custype","TXT"),
			 );
}

class fd_cus_no extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_no";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}

class fd_cus_name extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_name";	// 数据库中字段名称
        var $bwfd_title = "名称";	// 字段标题
}

class fd_cus_allname extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_allname";	// 数据库中字段名称
        var $bwfd_title = "全称";	// 字段标题
}

class fd_cus_custype extends findbrowsefield {
        var $bwfd_fdname = "fd_cus_custype";	// 数据库中字段名称
        var $bwfd_title = "客户类型";	// 字段标题
        
        function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "零售";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "批发";
        		     break;
        		case "2":
        		    $this->bwfd_show = "纸行";
        	
        	}      		     
		      return $this->bwfd_show ;
  	    }
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = ceil($loginbrowline * 0.75) ;
}

$tb_customer_bu = new tb_customer_b ;
$tb_customer_bu->brow_skin = $loginskin ;
$tb_customer_bu->brow_querywhere .= " fd_cus_state = 9 ";

$tb_customer_bu->main($now,$act,$pagerow,$order,$upordown,$check,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat) ;
?>