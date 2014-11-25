<?
$thismenucode = "2k302";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep"); //调用一个模版
	$arr_type=explode("@",$type);

$t->set_file("authorpayrecords", "authorpayrecords.html");
if($arr_type[0]=="use")
{
	$arr_text = array (
		"刷卡器",
		"交易类型",
		"交易状态",	
		"交易时间",	
		"交易金额",	
		"手续费"
	);
}else{
	$arr_text = array (
		"申请金额",
		"审批金额",
		"申请时间"
	);
}
$theadth = "<thead>";
for ($i = 0; $i < count($arr_text); $i++) {

	$theadth .= ' <th>' . $arr_text[$i] . '</th>';
}
if($scope=="creditcard"){$title="信用卡";}
if($scope=="bankcard"){$title="借记卡";}
$t->set_var("authorid", $authorid);
$t->set_var("scope", $scope);
$t->set_var("scdmsetid", $scdmsetid);
$t->set_var("type", $type);
$t->set_var("title", $title);
$t->set_var("theadth", $theadth);
$t->set_var("gotourl", $gotourl); // 转用的地址

// 判断权限 
include ("../include/checkqx.inc.php");

$t->set_var("skin", $loginskin);
$t->pparse("out", "authorpayrecords"); # 最后输出页面
?>

