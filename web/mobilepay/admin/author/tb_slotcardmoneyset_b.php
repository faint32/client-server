<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("交易设置", "授权刷卡额度" );
	var $prgnoware = array ("基础设置", "授权刷卡额度" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_scdmset_id";
	
	var $browse_queryselect = "select * from tb_slotcardmoneyset 
	                           left join tb_authorindustry on fd_auindustry_id = fd_scdmset_auindustryid ";
	var $browse_edit = "slotcardmoneyset.php?listid=";
	var $browse_new = "slotcardmoneyset.php";
	var $browse_delsql = "delete from tb_slotcardmoneyset where fd_scdmset_id = '%s'";
	var $browse_field = array ("fd_scdmset_no","fd_scdmset_name", "fd_auindustry_name","fd_scdmset_scope","fd_scdmset_nallmoney","fd_scdmset_sallmoney","fd_scdmset_everymoney","fd_scdmset_everycounts","fd_scdmset_datetime");
	var $browse_find = array (// 查询条件
"0" => array ("商户类型", "fd_auindustry_name", "TXT" ), "1" => array ("类型", "fd_scdmset_mode", "TXT" ), "2" => array ("正常额度", "fd_scdmset_nallmoney", "TXT" ), "3" => array ("审核额度", "fd_scdmset_sallmoney", "TXT" ) );
		
		function dodelete() { //  删除过程.
		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			$ishvaeflage = 0;
			$sql="select * from tb_author where fd_author_bkcardscdmsetid='" . $this->browse_check [$i] . "' or fd_author_slotscdmsetid='" . $this->browse_check [$i] . "'";
			$this->db->query ($sql);
			if($this->db->nf()){
				$ishvaeflage=1;
			} 
			if($ishvaeflage==1)
			{
				die ( "<script>alert('该套餐已绑定商户,请先取消绑定!'); window.history.back();</script>" );
			}
			else{
					$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
					$this->db->query ( $query ); //删除点击的记录
			}
		}
	}
}
class fd_scdmset_no extends browsefield {
	var $bwfd_fdname = "fd_scdmset_no"; // 数据库中字段名称
	var $bwfd_title = "套餐号"; // 字段标题
}
class fd_scdmset_name extends browsefield {
	var $bwfd_fdname = "fd_scdmset_name"; // 数据库中字段名称
	var $bwfd_title = "套餐名"; // 字段标题
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // 数据库中字段名称
	var $bwfd_title = "商户类型"; // 字段标题
}
class fd_scdmset_mode extends browsefield {
	var $bwfd_fdname = "fd_scdmset_mode"; // 数据库中字段名称
	var $bwfd_title = "限额类型"; // 字段标题
	function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {      		
        		case "date":
        		    $this->bwfd_show = "日套餐";
        		     break;
        		case "month":
        		    $this->bwfd_show = "月套餐";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_scdmset_scope extends browsefield {
	var $bwfd_fdname = "fd_scdmset_scope"; // 数据库中字段名称
	var $bwfd_title = "刷卡类型"; // 字段标题
	function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {      		
        		case "creditcard":
        		    $this->bwfd_show = "信用卡";
        		     break;
        		case "bankcard":
        		    $this->bwfd_show = "储蓄卡";
        		     break;        		 								
          }
		      return $this->bwfd_show ;
  	    }
}
class fd_scdmset_nallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_nallmoney"; // 数据库中字段名称
	var $bwfd_title = "正常额度"; // 字段标题
	var $bwfd_align ="center";
}
class fd_scdmset_sallmoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_sallmoney"; // 数据库中字段名称
	var $bwfd_title = "审批额度"; // 字段标题
	var $bwfd_align = "center";
}
class fd_scdmset_everymoney extends browsefield {
	var $bwfd_fdname = "fd_scdmset_everymoney"; // 数据库中字段名称
	var $bwfd_title = "每笔限额"; // 字段标题
	var $bwfd_align ="center";
}
class fd_scdmset_everycounts extends browsefield {
	var $bwfd_fdname = "fd_scdmset_everycounts"; // 数据库中字段名称
	var $bwfd_title = "限制刷卡次数"; // 字段标题
	var $bwfd_align = "center";
}
class fd_scdmset_datetime extends browsefield {
	var $bwfd_fdname = "fd_scdmset_datetime"; // 数据库中字段名称
	var $bwfd_title = "最近修改时间"; // 字段标题
	var $bwfd_align ="center";
}
if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_author_sp_b_bu = new tb_feedback_b ( );
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_author_sp_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_author_sp_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限


$tb_author_sp_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
?>

