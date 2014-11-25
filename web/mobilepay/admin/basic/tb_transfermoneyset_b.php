<?
$thismenucode = "2n104";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_transfermoneyset_b extends browse 
{
	 var $prgnoware    = array("刷卡器管理","转账汇款设置");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_transfermoneyset_id";
	 
	 var $browse_queryselect = "select * from tb_transfermoneyset
								left join tb_bank on fd_transfermoneyset_bankid=fd_bank_id
								left join tb_arrive on fd_transfermoneyset_arrivemode=fd_arrive_id
								";
	 var $browse_edit   = "transfermoneyset.php?listid=" ;
	  var $browse_new   = "transfermoneyset.php" ;
	

	 var $browse_field = array("fd_transfermoneyset_id","fd_bank_name","fd_transfermoneyset_fee","fd_arrive_name","fd_transfermoneyset_arrivetime",);
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_transfermoneyset_id","TXT"),
				"1" => array("银行名称" , "fd_bankset_name","TXT"),
				); 
}

class  fd_transfermoneyset_id  extends browsefield {
        var $bwfd_fdname = "fd_transfermoneyset_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// 数据库中字段名称
        var $bwfd_title = "银行名称";	// 字段标题
}
class fd_transfermoneyset_fee  extends browsefield {
        var $bwfd_fdname = "fd_transfermoneyset_fee";	// 数据库中字段名称
        var $bwfd_title = "手续费";	// 字段标题
		
		 function makeshow() {	// 将值转为显示值
        
			$this->bwfd_show=$this->bwfd_value."%";
		      return $this->bwfd_show ;
  	    }
}
class fd_arrive_name  extends browsefield {
        var $bwfd_fdname = "fd_arrive_name";	// 数据库中字段名称
        var $bwfd_title = "到账模式";	// 字段标题
}

class fd_transfermoneyset_arrivetime  extends browsefield {
        var $bwfd_fdname = "fd_transfermoneyset_arrivetime";	// 数据库中字段名称
        var $bwfd_title = "到账时间";	// 字段标题
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_transfermoneyset_id") 
   			    );
   var $bwlk_prgname = "transfermoneyset.php?listid=";
   var $bwlk_title ="<span style='color:#0000ff'>查看</span>";  
} */
if(empty($order)){
	$order = "fd_bankset_id";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_transfermoneyset_b_bu = new tb_transfermoneyset_b ;
$tb_transfermoneyset_b_bu->browse_skin = $loginskin ;
$tb_transfermoneyset_b_bu->browse_delqx = 1;  // 删除权限
$tb_transfermoneyset_b_bu->browse_addqx = 1;  // 新增权限
$tb_transfermoneyset_b_bu->browse_editqx = 1;  // 编辑权限
//$tb_transfermoneyset_b_bu->browse_link  = array("lk_view0");
$tb_transfermoneyset_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
