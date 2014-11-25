<?php
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;

$t = new Template(".","keep");
$t->set_file("template","consp_data.html");

if($type=="paycard")
{
	$name='刷卡器设备号';
	$gourl='paycardconsp_view.php';
	$title='财务报表 -->刷卡器消费统计详细表';
}
elseif($type=="author")
{
	$name='商户名';
	$gourl='authorconsp_view.php';
	$title='财务报表 -->商户消费统计详细表';
}
elseif($type=="sdcr")
{
	$name='明盛公户';
	$gourl='sdcrconsp_view.php';
	$title='财务报表 -->明盛公户统计详细表';
}
elseif($type=="checkdetail")
{
	$name='刷卡器设备号';
	$title='刷卡器交易详细表';
	$display='style=display:none';
}
else
{
	$name='刷卡器设备号';
	$gourl='payfeelist_view.php';
	$title='财务报表 -->手续费统计详细表';
}



$gotourl = $gourl.$tempurl ;
$arr_text = array($name,'银联订单号',"交易类型","交易日期","交易金额","手续费","利润额","到帐日期");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}

if (empty($listid))
{
	$listid = '';
}

if (empty($paytype))
{
	$paytype = '';
}

$t->set_var("theadth" , $theadth );
$t->set_var("listid" , $listid );
$t->set_var("type" , $type );
$t->set_var("paytype" , $paytype );
$t->set_var("display" , $display );
$t->set_var("title" , $title);
$t->set_var("action" , $action );
$t->set_var("error" , $error );
$t->set_var("gotourl" , $gotourl );// 转用的地址
// 判断权限
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");// 最后输出界面
?>