<?
$thismenucode = "2k302";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;

if($type=="check"){$gourl="tb_author_b.php";}
if($type=="author_sp"){$gourl="tb_author_sp_b.php";}
if($type=="checkdetail"){$isdisplay="none";$title='查看刷卡器已绑定信用卡';}else{$title='查看商户已登记信用卡';}

$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","checkauthorbank.html");




$arr_text = array('银行名',"绑定的刷卡器",'卡号','卡片名称','持卡人',"信用额度","账单日","还款日","免息期","卡片到期日","业务提醒时间","银行卡激活","绑定时间");


for($i=0;$i<count($arr_text);$i++)
{

	$theadth .=' <th >'.$arr_text[$i].'</th>';
	
	
}
$t->set_var("theadth"     , $theadth     ); 

$t->set_var("type",$type); 
$t->set_var("title",$title); 
$t->set_var("isdisplay",$isdisplay);  
$t->set_var("listid",$listid);  
$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址  
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面


?>