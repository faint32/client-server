<?
$thismenucode = "2k106";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_bank_b extends browse {
	//var $prgnoware = array ("刷卡器管理", "银行设置" );
	var $prgnoware = array ("基本设置","银行设置" );
	var $prgnowareurl = array ("", "" );
	
	var $browse_key = "fd_bank_id";
	
	var $browse_queryselect = "select * from tb_bank ";
	
	var $browse_edit = "bank.php?listid=";
	var $browse_new = "bank.php";
		 var $browse_delsql = "delete from tb_bank where fd_bank_id = '%s'" ;
		 
	var $browse_field = array ("fd_bank_id", "fd_bank_name", "fd_bank_activemobilesms", "fd_bank_smsphone","fd_bank_active" );
	var $browse_find = array (// 查询条件
"0" => array ("编号", "fd_bank_id", "TXT" ), "1" => array ("银行名称", "fd_bank_name", "TXT" ) );
}

class fd_bank_id extends browsefield {
	var $bwfd_fdname = "fd_bank_id"; // 数据库中字段名称
	var $bwfd_title = "编号"; // 字段标题
}
class fd_bank_name extends browsefield {
	var $bwfd_fdname = "fd_bank_name"; // 数据库中字段名称
	var $bwfd_title = "银行名称"; // 字段标题
}
class fd_bank_activemobilesms extends browsefield {
	var $bwfd_fdname = "fd_bank_activemobilesms"; // 数据库中字段名称
	var $bwfd_title = "是否支持手机银行短信功能"; // 字段标题
	function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
class fd_bank_smsphone extends browsefield {
	var $bwfd_fdname = "fd_bank_smsphone"; // 数据库中字段名称
	var $bwfd_title = "发送短信的号码"; // 字段标题
}
class fd_bank_active extends browsefield {
	var $bwfd_fdname = "fd_bank_active"; // 数据库中字段名称
	var $bwfd_title = "是否激活"; // 字段标题
	function makeshow() {	// 将值转为显示值
        	switch ($this->bwfd_value) {
        		case "0":
        		    $this->bwfd_show = "否";
        		     break;       		
        		case "1":
        		    $this->bwfd_show = "是";
        		     break;
        	}      		     
		      return $this->bwfd_show ;
  	    }
}
if (empty ( $order )) {
	$order = "fd_bank_id";
}

if (isset ( $pagerows )) { // 显示列数
	$pagerows = min ( $pagerows, 100 ); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_bank_b_bu = new tb_bank_b;
$tb_bank_b_bu->browse_skin = $loginskin;
$tb_bank_b_bu->browse_delqx = $thismenuqx [3]; // 删除权限
$tb_bank_b_bu->browse_addqx = $thismenuqx [1]; // 新增权限
$tb_bank_b_bu->browse_editqx = $thismenuqx [2]; // 编辑权限


$tb_bank_b_bu->main ( $now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition );
				
?>
