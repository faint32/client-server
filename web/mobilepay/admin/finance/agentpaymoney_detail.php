<?php
require("../include/common.inc.php");

$db=new db_test;
$t = new Template(".","keep");
$t->set_file("template","agentpaymoney_detail.html");
if(!empty($type)){
	switch ($type){
		case "all":
			$where = "and (fd_pymylt_state ='9' or fd_pymylt_state ='3')";
			$tabtype="总代付额";
		break;
		case "ysp":
			$where = "and fd_pymylt_state ='9'";
			$tabtype="已核对出款";
		break;
		case "wsp":
			$where ="and fd_pymylt_state ='3'";
			$tabtype="未核对出款";
		break;
	}
}
if(!empty($typekind)){
	switch ($typekind){
		case "pay":
			$thname="支付额";
		break;
		case "cost":
			$thname="成本";
		break;
		case "fee":
			$thname="手续费";
		break;
	}
}
if(!empty($datetype)){
	switch($datetype){
		case "year":
		$gourl='agentpaymoneyyear_view.php?year='.$year.'&month='.$month;
		$title="资金代付年报表";
		break;
		case "month":
		$title="资金代付月报表";
		$gourl='agentpaymoneymonth_view.php?year='.$year.'&month='.$month;
		break;
	}
}
$gotourl = $gourl.$tempurl ;

$arr_text = array('编号','银联订单号',"交易类型","交易日期","{thname}","终端商户","收款人<br>账号","收款人<br>开户银行","收款<br>人姓名","收款人<br>手机","刷卡器<br>设备号");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}

$t->set_var("theadth" , $theadth );

$t->set_var("gotourl",$gotourl);// 转用的地址
$t->set_var("error",$error);
$t->set_var("allmoney",$allmoney);
$t->set_var("skin",$loginskin);
$t->set_var("month",$month);
$t->set_var("year",$year);
$t->set_var("thname",$thname);
$t->set_var("title",$title);
$t->set_var("tabtype",$tabtype);
$t->set_var("type",$type);
$t->set_var("typekind",$typekind);
$t->set_var("listdate",$listdate);
$t->set_var("datetype",$datetype);

// 判断权限
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "template");// 最后输出界面
?>