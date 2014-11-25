<?
$thismenucode = "2k310";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");

class tb_feedback_b extends browse {
	//var $prgnoware = array ("会员钱包管理" );
	var $prgnoware = array (
		"商户管理",
		"商户资金账户"
	);
	var $prgnowareurl = array (
		"",
		""
	);

	var $browse_key = "fd_acc_id";

	var $browse_queryselect = "select * from tb_authoraccount
		                            left join tb_author on fd_author_id = fd_acc_authorid
		                            ";
	var $browse_edit = "authoraccount.php?listid=";
	var $browse_editname = "查看";
	var $browse_field = array (
		"fd_acc_id",
		"fd_author_truename",
		"fd_acc_money"
	);
	var $browse_find = array (// 查询条件
	"0" => array (
			"序号",
			"fd_acc_id",
			"TXT"
		),
		"1" => array (
			"用户名",
			"fd_author_truename",
			"TXT"
		),
		"1" => array (
			"金额",
			"fd_acc_money",
			"TXT"
		)
	);

}

class fd_acc_id extends browsefield {
	var $bwfd_fdname = "fd_acc_id"; // 数据库中字段名称
	var $bwfd_title = "序号"; // 字段标题
}

class fd_author_truename extends browsefield {
	var $bwfd_fdname = "fd_author_truename"; // 数据库中字段名称
	var $bwfd_title = "用户名"; // 字段标题
}
class fd_acc_typeno extends browsefield {
	var $bwfd_fdname = "fd_acc_typeno"; // 数据库中字段名称
	var $bwfd_title = "账户类型"; // 字段标题
}
class fd_acc_typename extends browsefield {
	var $bwfd_fdname = "fd_acc_typename"; // 数据库中字段名称
	var $bwfd_title = "账户类型名"; // 字段标题
}
class fd_acc_money extends browsefield {
	var $bwfd_fdname = "fd_acc_money"; // 数据库中字段名称
	var $bwfd_title = "金额"; // 字段标题
}

if (isset ($pagerows)) { // 显示列数
	$pagerows = min($pagerows, 100); // 最大显示列数不超过100
} else {
	$pagerows = $loginbrowline;
}

$tb_feedback_b_bu = new tb_feedback_b();
$tb_feedback_b_bu->browse_skin = $loginskin;
$tb_feedback_b_bu->browse_delqx = $thismenuqx[3]; // 删除权限
$tb_feedback_b_bu->browse_addqx = $thismenuqx[1]; // 新增权限
$tb_feedback_b_bu->browse_editqx = $thismenuqx[2]; // 编辑权限
$tb_feedback_b_bu->main($now, $action, $pagerow, $order, $upordown, $checknote, $prgnoware, $prgnowareurl, $pagerows, $whatdofind, $howdofind, $findwhat, $allcondition);
?>

