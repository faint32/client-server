<?
$thismenucode = "2n107";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;


$t = new Template(".","keep");
$t->set_file("template","paycard.html");





$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("","序号","刷卡器key","刷卡器类型","会员名","会员电话","网导","所属银行","录入日期","状态","是否新卡","操作");

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