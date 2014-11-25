<?php
$thismenucode = "10n016";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","sdcrconsp_view.html");

$arr_paytype=getauthorpaycardmenu();

$allpaymoney=getallpaymoney();

$arr_text = array('明盛公户','刷卡金额',"最后一次刷卡时间");

foreach($arr_paytype as $value)
{
	$arr_text[]=$value;
}
$arr_text1 = array("刷卡金额");
for($i=0; $i<count($arr_text); $i++)
{
	$max=count($arr_text)-1;

	if(2<$i)
	{
		foreach($arr_text1 as $value)
		{
			$theadth1 .=' <th onclick="changeorderby(this,\''.$i.'\')">'.$value.'</th>';
		}
	}
	else
	{
		$theadth1 .='<th onclick="changeorderby(this,\''.$i.'\')"></th>';
	}
	$theadth .=' <th>'.$arr_text[$i].'</th>';
}
$t->set_var("theadth" , $theadth );
$t->set_var("theadth1" , $theadth1 );
$t->set_var("allpaymoney" , $allpaymoney );
$t->set_var("action", $action );
$t->set_var("error", $error );
$t->set_var("gotourl" , $gotourl );//转用的地址
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin", $loginskin );
$t->pparse("out", "template" );//最后输出界面
?>