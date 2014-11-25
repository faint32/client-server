<?
$thismenucode = "2k511";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("基础设置", "商户交易预警设置" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_warnlevel_id";
	
	var $browse_queryselect = "select * from tb_warnlevel
								left join tb_authorindustry on fd_auindustry_id=fd_warnlevel_typeid
								 ";
	var $browse_edit = "poswarn.php?listid=";
	var $browse_editname = "修改";
	var $browse_new = "poswarn.php";
	var $browse_delsql = "delete from tb_warnlevel where fd_warnlevel_id = '%s'";
	var $browse_field = array ("fd_warnlevel_id","fd_warnlevel_level", "fd_auindustry_name","fd_warnlevel_creditcard",
								"fd_warnlevel_cashcard","fd_warnlevel_postnum","fd_warnlevel_average","fd_warnlevel_scale" );
	var $browse_find = array (// 查询条件
							"0" => array ("预警级别编号", "fd_warnlevel_id", "TXT" ), 
							"1" => array ("商户类型", "fd_auindustry_name", "TXT" ) );

}
class fd_warnlevel_id extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
}
class fd_warnlevel_level extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_level"; // 数据库中字段名称
	var $bwfd_title = "预警级别"; // 字段标题
			function makeshow() {	// 将值转为显示值
		switch ($this->bwfd_value) {
			case "highest":
				$this->bwfd_show = "<font color='#FF0000'>极高</font>";
				 break;       		
			case "high":
				$this->bwfd_show = "<font color='#00CC33'>高</font>";
				 break;
			case "middle":
				$this->bwfd_show = "<font color='#00CC66'>中</font>";
				 break;       		
			case "low":
				$this->bwfd_show = "<font color='#339999'>低</font>";
				 break;        		 								
	  }
		  return $this->bwfd_show ;
	}
}
class fd_auindustry_name extends browsefield {
	var $bwfd_fdname = "fd_auindustry_name"; // 数据库中字段名称
	var $bwfd_title = "商户类型"; // 字段标题

}
class fd_warnlevel_creditcard extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_creditcard"; // 数据库中字段名称
	var $bwfd_title = "信用卡刷卡总金额（万/月）"; // 字段标题
}
class fd_warnlevel_cashcard extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_cashcard"; // 数据库中字段名称
	var $bwfd_title = "储蓄卡刷卡总金额（万/月）"; // 字段标题
}
class fd_warnlevel_postnum extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_postnum"; // 数据库中字段名称
	var $bwfd_title = "POS刷卡次数（次/月）"; // 字段标题
}
class fd_warnlevel_average extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_average"; // 数据库中字段名称
	var $bwfd_title = "平均每笔刷卡金额（元/笔）"; // 字段标题
}
class fd_warnlevel_scale extends browsefield {
	var $bwfd_fdname = "fd_warnlevel_scale"; // 数据库中字段名称
	var $bwfd_title = "用卡刷卡占比（%）"; // 字段标题
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
