<?
$thismenucode = "10n007";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;


$t = new Template(".","keep");
$t->set_file("template","bankpaymoney.html");


	

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("","银行流水号","刷卡器设备号","用户","支付类型","代付金额","交易时间","T+N周期","预计出款日","出款账户","出款人信息","入款账户","入款人信息","操作");

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