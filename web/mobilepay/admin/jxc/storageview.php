<?
$thismenucode = "2k226";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
$t = new Template(".","keep");
$t->set_file("template","storageview.html");


	

$checkall= '<INPUT onclick=CheckAll(this) type=checkbox class=checkbox  name=chkall>';
$arr_text = array("序号","商品编号","商品名称","数量","最近入货","最近出货","操作");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
}echo $commid;
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