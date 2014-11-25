<?
$thismenucode = "2n105";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_repaymoneyset_b extends browse 
{
	 var $prgnoware    = array("刷卡器管理","信贷还款设置");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_repaymoneyset_id";
	 
	 var $browse_queryselect = "select * from tb_repaymoneyset
								left join tb_bank on fd_repaymoneyset_bankid=fd_bank_id
								left join tb_arrive on fd_repaymoneyset_arrivetime=fd_arrive_id
								";
	 var $browse_edit   = "repaymoneyset.php?listid=" ;
	  var $browse_new   = "repaymoneyset.php" ;
	

	 var $browse_field = array("fd_repaymoneyset_id","fd_bank_name","fd_repaymoneyset_fee","fd_repaymoneyset_tradememo","fd_arrive_name",);
 	 var $browse_find = array(		// 查询条件
				"0" => array("编号" , "fd_repaymoneyset_id","TXT"),
				"1" => array("银行名称" , "fd_bank_name","TXT"),
				); 
}

class  fd_repaymoneyset_id  extends browsefield {
        var $bwfd_fdname = "fd_repaymoneyset_id";	// 数据库中字段名称
        var $bwfd_title = "编号";	// 字段标题
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// 数据库中字段名称
        var $bwfd_title = "银行名称";	// 字段标题
}
class fd_repaymoneyset_fee  extends browsefield {
        var $bwfd_fdname = "fd_repaymoneyset_fee";	// 数据库中字段名称
        var $bwfd_title = "手续费";	// 字段标题
			 function makeshow() {	// 将值转为显示值
        
			$this->bwfd_show=$this->bwfd_value."%";
		      return $this->bwfd_show ;
  	    }
}
class fd_repaymoneyset_tradememo  extends browsefield {
        var $bwfd_fdname = "fd_repaymoneyset_tradememo";	// 数据库中字段名称
        var $bwfd_title = "交易额度";	// 字段标题
}

class fd_arrive_name  extends browsefield {
        var $bwfd_fdname = "fd_arrive_name";	// 数据库中字段名称
        var $bwfd_title = "到账时间";	// 字段标题
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_repaymoneyset_id") 
   			    );
   var $bwlk_prgname = "repaymoneyset.php?listid=";
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

$tb_repaymoneyset_b_bu = new tb_repaymoneyset_b ;
$tb_repaymoneyset_b_bu->browse_skin = $loginskin ;
$tb_repaymoneyset_b_bu->browse_delqx = 1;  // 删除权限
$tb_repaymoneyset_b_bu->browse_addqx = 1;  // 新增权限
$tb_repaymoneyset_b_bu->browse_editqx = 1;  // 编辑权限
//$tb_repaymoneyset_b_bu->browse_link  = array("lk_view0");
$tb_repaymoneyset_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
