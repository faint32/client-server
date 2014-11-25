<?
$thismenucode = "2k502";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("交易设置","商户费率设置");
	var $prgnoware = array ("基础设置","商户费率设置");
	var $prgnowareurl = array (
		"",
		""
	);

	var $browse_key = "fd_payfset_id";

	var $browse_queryselect = "select * from tb_payfeeset 
	                           left join tb_authorindustry on fd_auindustry_id = fd_payfset_auindustryid
	                          ";
	var $browse_edit = "payfeeset.php?listid=";
	var $browse_editname = "修改";
	var $browse_new = "payfeeset.php";
	var $browse_delsql = "delete from tb_payfeeset where fd_payfset_id = '%s'";
	var $browse_field = array (
		"fd_payfset_no",
		"fd_auindustry_name",
		"fd_payfset_scope",
		"fd_payfset_fixfee",
		"fd_payfset_fee",
		"fd_payfset_minfee",
		"fd_payfset_maxfee",
		"fd_payfset_datetime"
	);
	var $browse_find = array (// 查询条件
	"0" => array (
			"商户类型",
			"fd_auindustry_name",
			"TXT"
		)
		
	);
			
		function dodelete() { //  删除过程.
		for($i = 0; $i < count ( $this->browse_check ); $i ++) {
			$ishvaeflage = 0;
			$sql="select * from tb_author where fd_author_bkcardpayfsetid='" . $this->browse_check [$i] . "' or fd_author_slotpayfsetid='" . $this->browse_check [$i] . "'";
			$this->db->query ($sql);
			if($this->db->nf()){
				$ishvaeflage=1;
			} 
			if($ishvaeflage==1)
			{
				die ("<script>alert('该套餐已绑定商户,请先取消绑定!'); window.history.back();</script>" );
			}
			else{
					$query = sprintf ( $this->browse_delsql, $this->browse_check [$i] );
					$this->db->query ( $query ); //删除点击的记录
			}
		}
	}

}
class fd_payfset_no extends browsefield {
	var $bwfd_fdname = "fd_payfset_no"; // 数据库中字段名称
	var $bwfd_title = "套餐号"; // 字段标题
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // 数据库中字段名称
	var $bwfd_title = "商户类型"; // 字段标题
}
class fd_payfset_scope extends browsefield {
	var $bwfd_fdname = "fd_payfset_scope"; // 数据库中字段名称
	var $bwfd_title = "卡类型"; // 字段标题
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
class fd_paycardtype_name extends browsefield {
	var $bwfd_fdname = "fd_paycardtype_name"; // 数据库中字段名称
	var $bwfd_title = "刷卡器类型"; // 字段标题
}
class fd_arrive_name extends browsefield {
	var $bwfd_fdname = "fd_arrive_name"; // 数据库中字段名称
	var $bwfd_title = "到帐周期"; // 字段标题
}
class fd_payfset_mode extends browsefield {
	var $bwfd_fdname = "fd_payfset_mode"; // 数据库中字段名称
	var $bwfd_title = "费率类型"; // 字段标题
}
class fd_payfset_fixfee extends browsefield {
	var $bwfd_fdname = "fd_payfset_fixfee"; // 数据库中字段名称
	var $bwfd_title = "固定费率"; // 字段标题
	var $bwfd_align = "center";
}
class fd_payfset_fee extends browsefield {
	var $bwfd_fdname = "fd_payfset_fee"; // 数据库中字段名称
	var $bwfd_title = "收取费率"; // 字段标题
	var $bwfd_align = "center";
}
class fd_payfset_minfee extends browsefield {
	var $bwfd_fdname = "fd_payfset_minfee"; // 数据库中字段名称
	var $bwfd_title = "最低费率额"; // 字段标题
	var $bwfd_align = "center";
}
class fd_payfset_maxfee extends browsefield {
	var $bwfd_fdname = "fd_payfset_maxfee"; // 数据库中字段名称
	var $bwfd_title = "最高费率额"; // 字段标题
	var $bwfd_align = "center";
}
class fd_payfset_datetime extends browsefield {
	var $bwfd_fdname = "fd_payfset_datetime"; // 数据库中字段名称
	var $bwfd_title = "最近修改时间"; // 字段标题
	var $bwfd_align = "center";
}
if (isset ($pagerows)) { // 显示列数
	$pagerows = min($pagerows, 100); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_author_sp_b_bu = new tb_feedback_b();
$tb_author_sp_b_bu->browse_skin = $loginskin;
$tb_author_sp_b_bu->browse_delqx = $thismenuqx[3]; // 删除权限
$tb_author_sp_b_bu->browse_addqx = $thismenuqx[1]; // 新增权限
$tb_author_sp_b_bu->browse_editqx = $thismenuqx[2]; // 编辑权限

$tb_author_sp_b_bu->main($now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition);
?>

