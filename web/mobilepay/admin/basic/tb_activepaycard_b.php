<?
$thismenucode = "2n108";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_paycardactivelist_b extends browse 
{
	 var $prgnoware    = array("刷卡器管理","已激活刷卡器");
	 var $prgnowareurl =  array("","");
	 
	 var $browse_key = "fd_pcdactive_id";
	 
	 var $browse_queryselect = "select * from  tb_paycardactivelist 
								left join tb_bank on fd_pcdactive_bankid=fd_bank_id
								left join tb_saler on fd_pcdactive_salerid=fd_saler_id
								left join tb_paycard on fd_pcdactive_paycardid=fd_paycard_id
								left join tb_author on fd_pcdactive_authorid=fd_author_id
								";
	var $browse_edit   = "pcdactive.php?listid=" ;
    var $browse_editname   = "查看" ;
	//var $browse_new   = "pcdactive.php" ;
	

	 var $browse_field = array("fd_pcdactive_id","fd_author_truename","fd_paycard_no","fd_paycard_type","fd_pcdactive_activedate","fd_saler_truename","fd_bank_name");
 	 var $browse_find = array(		// 查询条件
				"0" => array("刷卡器key" , "fd_paycard_no","TXT"),
				"1" => array("激活时间" , "fd_pcdactive_activedate","TXT"),
				"2" => array("网导" , "fd_saler_truename","TXT"),
				"3" => array("操作人" , "fd_author_truename","TXT"),
				); 
}

class  fd_pcdactive_id  extends browsefield {
        var $bwfd_fdname = "fd_pcdactive_id";	// 数据库中字段名称
        var $bwfd_title = "序号";	// 字段标题
}
class  fd_author_truename  extends browsefield {
        var $bwfd_fdname = "fd_author_truename";	// 数据库中字段名称
        var $bwfd_title = "激活操作人";	// 字段标题
}
class fd_bank_name  extends browsefield {
        var $bwfd_fdname = "fd_bank_name";	// 数据库中字段名称
        var $bwfd_title = "所属银行";	// 字段标题
}
class fd_paycard_no  extends browsefield {
        var $bwfd_fdname = "fd_paycard_no";	// 数据库中字段名称
        var $bwfd_title = "刷卡器key";	// 字段标题
			
}
class fd_paycard_type  extends browsefield {
        var $bwfd_fdname = "fd_paycard_type";	// 数据库中字段名称
        var $bwfd_title = "刷卡器类型";	// 字段标题
		 function makeshow() {	// 将值转为显示值
			switch($this->bwfd_value)
			{
				case "creditcard";
				$this->bwfd_show="信用卡";
				break;
				case "bankcard";
				$this->bwfd_show="储蓄卡";
				break;
			}
			
		      return $this->bwfd_show ;
  	    }
			
}
class fd_saler_truename  extends browsefield {
        var $bwfd_fdname = "fd_saler_truename";	// 数据库中字段名称
        var $bwfd_title = "网导";	// 字段标题
}

class  fd_pcdactive_activedate  extends browsefield {
        var $bwfd_fdname = "fd_pcdactive_activedate";	// 数据库中字段名称
        var $bwfd_title = "激活时间";	// 字段标题
}
/* class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_pcdactive_id") 
   			    );
   var $bwlk_prgname = "../saler/bangding.php?bangdingtype=saler&url=activepaycard&thismenucode=2n001&listid=";
   var $bwlk_title ="<span style='color:#0000ff'>重新判定网导</span>";  
   
  
 
} */
if(empty($order)){
	$order = "fd_pcdactive_id";
}


if(isset($pagerows)){	// 显示列数
       $pagerows = min($pagerows,100) ;  // 最大显示列数不超过100
}else{
       $pagerows = $loginbrowline ;
}

$tb_paycardactivelist_b_bu = new tb_paycardactivelist_b ;
$tb_paycardactivelist_b_bu->browse_skin = $loginskin ;
$tb_paycardactivelist_b_bu->browse_delqx = 1;  // 删除权限
$tb_paycardactivelist_b_bu->browse_addqx = 1;  // 新增权限
$tb_paycardactivelist_b_bu->browse_editqx = 1;  // 编辑权限
//$tb_paycardactivelist_b_bu->browse_link  = array("lk_view0");
$tb_paycardactivelist_b_bu->main($now,$action,$pagerow,$order,$upordown,$checknote,
  		$prgnoware,$prgnowareurl,$pagerows,$whatdofind,$howdofind,$findwhat,$allcondition) ;
?>
