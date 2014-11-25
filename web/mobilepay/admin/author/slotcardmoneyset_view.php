<?
$thismenucode = "10n007";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;


$t = new Template(".","keep");
$t->set_file("template","slotcardmoneyset_view.html");

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("","编号","商户类型","卡类型","到帐周期","","网导","所属银行","录入日期","状态","是否新卡","已交易次数","交易金额","累计续费","操作");


$query = "";

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