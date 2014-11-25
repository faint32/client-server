<?
$thismenucode = "10n008";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;


$t = new Template(".","keep");
$t->set_file("template","realtimetradeview.html");


	

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("","序号","刷卡器设备号","刷卡器类型","会员名","会员电话","明盛账户","交易时间","状态","交易类型","交易金额","已交易次数","交易金额","累计续费","操作");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';	
}	
$t->set_var("theadth"     , $theadth     );    
$t->set_var("action",$action);
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面


?>