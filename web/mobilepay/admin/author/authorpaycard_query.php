<?
//$thismenucode = "10n007";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","authorpaycard_query.html");


	

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("编号","商户名","刷卡器号码","刷卡器类型","刷卡器属性","购买批次","目前状态","交易次数","交易金额","产生收益");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
}echo $commid;
$t->set_var("theadth"     , $theadth     ); 
 $t->set_var("vid",$vid);  
$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址  
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面


?>