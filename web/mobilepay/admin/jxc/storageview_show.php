<?
//$thismenucode = "10n007";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gourl = "storageview.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$t = new Template(".","keep");
$t->set_file("template","storageview_show.html");


	

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("序号","刷卡器批次","刷卡器设备号","刷卡器状态","入库时间","供应商","销售时间","代理商");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
}
	
$t->set_var("theadth"     , $theadth     ); 
 $t->set_var("commid",$commid);  
$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址  
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面


?>