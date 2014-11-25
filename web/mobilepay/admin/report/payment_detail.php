<?php
require("../include/common.inc.php");

$db=new db_test;
$t = new Template(".","keep");
$t->set_file("template","payment_detail.html");

if($type=="day")
{
	
	$gourl='tb_payment_dateview.php';
	$title='收支日报表';
}elseif($type=="month") {
	
	$gourl='tb_payment_monthview.php';
	$title='收支月报表';
}else{
	
	$gourl='tb_payment_yearview.php';
	$title='收支年报表';
}



$gotourl = $gourl.$tempurl ;
$arr_text = array('刷卡器设备号','银联订单号',"交易类型","交易日期","交易金额","支出","手续费","利润额","到帐日期");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}

$t->set_var("theadth" , $theadth );

$t->set_var("collect" ,$collect);
$t->set_var("datetime" ,$datetime);
$t->set_var("type" ,$type);
$t->set_var("paytype" , $paytype );


$t->set_var("title" , $title);
$t->set_var("action" , $action );
$t->set_var("error" , $error );
$t->set_var("gotourl" , $gotourl );// 转用的地址
// 判断权限
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");// 最后输出界面
?>