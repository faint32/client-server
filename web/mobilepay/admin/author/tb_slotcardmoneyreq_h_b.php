<?
$thismenucode = "2k510";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");



class tb_slotcardmoneyreq_sp_b extends browse {
	var $prgnoware = array ("基础设置", "审批额度历史" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_pmreq_id";
	//var $browse_edit  = "slotcardmoneyreq_sp.php?listid=" ;
  //var $browse_editname = "审批";
	var $browse_queryselect = "select * from tb_slotcardmoneyreq 
								left join tb_author on fd_author_id = fd_pmreq_authorid 
	                           left join tb_slotcardmoneyset on fd_scdmset_id = fd_pmreq_paymsetid ";
	var $browse_field = array ("fd_pmreq_reqno","fd_author_truename","fd_scdmset_name",
								"fd_scdmset_scope","fd_scdmset_nallmoney","fd_scdmset_sallmoney",
								"fd_pmreq_reqmoney" ,"fd_pmreq_repmoney", "fd_pmreq_reqdatetime","fd_pmreq_state");
	var $browse_find = array (// 查询条件
							"0" => array ("申请编号", "fd_pmreq_reqno", "TXT" ),
							"1" => array ("所属用户", "fd_author_truename", "TXT" ),
							"2" => array ("套餐", "fd_scdmset_name", "TXT" ) ,
							"3" => array ("卡类型", "fd_scdmset_scope", "TXT" ) ,
							);

}

class fd_pmreq_reqno extends browsefield {
	var $bwfd_fdname = "fd_pmreq_reqno"; // 数据库中字段名称
	var $bwfd_title = "申请编号"; // 字段标题
}
class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // 数据库中字段名称
	var $bwfd_title = "所属用户"; // 字段标题
}
class fd_scdmset_name extends browsefield {
	var $bwfd_fdname = "fd_scdmset_name"; // 数据库中字段名称
	var $bwfd_title = "套餐"; // 字段标题
}
class fd_scdmset_scope extends browsefield {
	var $bwfd_fdname = "fd_scdmset_scope"; // 数据库中字段名称
	var $bwfd_title = "卡类型"; // 字段标题
	 function makeshow() {	// 将值转为显示值
		switch($this->bwfd_value){
			case "creditcard":
			  $this->bwfd_show = "信用卡";
			  break;
			case "bankcard":
			  $this->bwfd_show = "储蓄卡";
			  break; 
		}
		  return $this->bwfd_show;
	}
}
class fd_scdmset_nallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_nallmoney"; // 数据库中字段名称
	var $bwfd_title = "正常额度（万/月）"; // 字段标题
}
class fd_scdmset_sallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_sallmoney"; // 数据库中字段名称
	var $bwfd_title = "最高审批额度（万/月）"; // 字段标题
}
class fd_pmreq_reqmoney extends browsefield {
	var $bwfd_fdname = "fd_pmreq_reqmoney"; // 数据库中字段名称
	var $bwfd_title = "本次申请额度（万/月）"; // 字段标题
}

class fd_pmreq_repmoney extends browsefield {
	var $bwfd_fdname = "fd_pmreq_repmoney"; // 数据库中字段名称
	var $bwfd_title = "审批金额"; // 字段标题
} 

class fd_pmreq_reqdatetime extends browsefield {
	var $bwfd_fdname = "fd_pmreq_reqdatetime"; // 数据库中字段名称
	var $bwfd_title = "申请日期"; // 字段标题
}
class fd_pmreq_state extends browsefield {
	var $bwfd_fdname = "fd_pmreq_state"; // 数据库中字段名称
	var $bwfd_title = "状态"; // 字段标题
	 function makeshow() {	// 将值转为显示值
        	switch($this->bwfd_value){
        		case "0":
        		  $this->bwfd_show = "未审批";
        		  break;
				case "1":
        		  $this->bwfd_show = "审批未通过";
        		  break;  
        		case "9":
        		  $this->bwfd_show = "审批通过";
        		  break;
        	}
		      return $this->bwfd_show;
  	    }
}

 // 链接定义
class lk_view0 extends browselink {
   var $bwlk_fdname = array(			// 所需数据库中字段名称
   			    "0" => array("fd_pmreq_id") ,
   			    );
   var $bwlk_prgname ="slotcardmoneyreq_h.php?listid=";
   var $bwlk_title ="明细查看";  
} 
if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_slotcardmoneyreq_sp_b_bu = new tb_slotcardmoneyreq_sp_b ();
$tb_slotcardmoneyreq_sp_b_bu->browse_skin = $loginskin;
$tb_slotcardmoneyreq_sp_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_slotcardmoneyreq_sp_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_slotcardmoneyreq_sp_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限
$tb_slotcardmoneyreq_sp_b_bu->browse_link  = array("lk_view0");
//显示有权限查看的机构资料
$tb_slotcardmoneyreq_sp_b_bu->browse_querywhere = "fd_pmreq_state <> 0";
$tb_slotcardmoneyreq_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>

