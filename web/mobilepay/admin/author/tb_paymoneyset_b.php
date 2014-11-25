<?
$thismenucode = "2k501";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	var $prgnoware = array ("交易设置", "授权刷卡额度" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_paymset_id";
	
	var $browse_queryselect = "select * from tb_paymoneyset 
	                           left join tb_authortype on fd_authortype_id = fd_paymset_authortypeid ";
	var $browse_edit = "paymoneyset.php?listid=";
	var $browse_new = "paymoneyset.php";
	var $browse_delsql = "delete from tb_paymoneyset where fd_paymset_id = '%s'";
	var $browse_field = array ("fd_paymset_no", "fd_authortype_name","fd_paymset_mode","fd_paymset_nallmoney","fd_paymset_sallmoney");
	var $browse_find = array (// 查询条件
"0" => array ("商户类型", "fd_authortype_name", "TXT" ), "1" => array ("类型", "fd_paymset_mode", "TXT" ), "2" => array ("正常额度", "fd_paymset_nallmoney", "TXT" ), "3" => array ("审核额度", "fd_paymset_sallmoney", "TXT" ) );

}
class fd_paymset_no extends browsefield {
	var $bwfd_fdname = "fd_paymset_no"; // 数据库中字段名称
	var $bwfd_title = "套餐号"; // 字段标题
}
class fd_authortype_name extends browsefield {
	var $bwfd_fdname = "fd_authortype_name"; // 数据库中字段名称
	var $bwfd_title = "商户类型"; // 字段标题
}
class fd_paymset_mode extends browsefield {
	var $bwfd_fdname = "fd_paymset_mode"; // 数据库中字段名称
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
class fd_paymset_nallmoney extends browsefield {
	var $bwfd_fdname = "fd_paymset_nallmoney"; // 数据库中字段名称
	var $bwfd_title = "正常额度（万）"; // 字段标题
	var $bwfd_align ="center";
}
class fd_paymset_sallmoney extends browsefield {
	var $bwfd_fdname = "fd_paymset_sallmoney"; // 数据库中字段名称
	var $bwfd_title = "审批额度（万）"; // 字段标题
	var $bwfd_align = "center";
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

