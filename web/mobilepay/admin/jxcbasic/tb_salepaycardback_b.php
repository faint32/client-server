<?
$thismenucode = "2k402";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;
$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","tb_salepaycardback_b.html");



$arr_text = array("操作",'编号','批次',"设备号","供应商","商品名称","采购价格","可刷卡类型","退货时间","退货原因");


for($i=0;$i<count($arr_text);$i++)
{

	$theadth .=' <th>'.$arr_text[$i].'</th>';
	
	
}
$t->set_var("theadth"     , $theadth     ); 


$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址  
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面



?>