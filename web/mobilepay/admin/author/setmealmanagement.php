<?
$thismenucode = "10n010";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep"); //调用一个模版


$t->set_file("setmealmanagement", "setmealmanagement.html");
$arr_text = array (
	"所属套餐",
	"商户类型",
	"已绑定商户数量",	
	"正常额度(万)",	
	"超额金额（万）",	
	"卡范围",	
	"每笔限额",	
	"每日或每月刷卡次数",
	"日正常额度(万)",
	"日审批金额（万）"
);
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}

$arr_text1 = array (
	"所属套餐",
	"商户类型",
	"已绑定商户数量",
	"刷卡器类型",	
	"到账周期",	
	"费率扣款方向",	
	"费率类型",
	"固定手续费(元)",	
	"收取费率（%）",	
	"最低费率额",
	"最高费率额"
);
$theadth1 = "<thead>";
for ($i = 0; $i < count($arr_text1); $i++) {

	$theadth1 .= ' <th>' . $arr_text1[$i] . '</th>';
}

$t->set_var("theadth", $theadth);

$t->set_var("theadth1", $theadth1);


$t->set_var("gotourl", $gotourl); // 转用的地址

// 判断权限 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "setmealmanagement"); # 最后输出页面
?>

