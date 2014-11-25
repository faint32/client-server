<?
$thismenucode = "2k302";     
require("../include/common.inc.php");

$db=new db_test;
$db2=new db_test;
$db3=new db_test;

if($type=="check"){$gourl="tb_author_b.php";}
if($type=="author_sp"){$gourl="tb_author_sp_b.php";}
$gotourl = $gourl.$tempurl ;

$t = new Template(".","keep");
$t->set_file("template","checkauthorpaycard.html");


$arr_paytype=getauthorpaycardmenu();


$arr_text = array('刷卡器设备号',"刷卡器每笔限额","刷卡次数","日额度(元)","月额度(元)",'刷卡器所在代理商','刷卡器刷卡总次数','刷卡金额',"最后一次刷卡时间");

foreach($arr_paytype as $value)
{
	$arr_text[]=$value;
}
$arr_text1 = array("刷卡金额","刷卡次数");
for($i=0;$i<count($arr_text);$i++)
{
	$max=count($arr_text)-1;
	
	if(8<$i)
	{
		$colspan="colspan=2";
		foreach($arr_text1 as $value)
		{
			$theadth1 .=' <th>'.$value.'</th>';
		}
	}else{
		$colspan="";
		$theadth1 .='<th></th>';
	}
	$theadth .=' <th '.$colspan.'>'.$arr_text[$i].'</th>';
	
	
}
$t->set_var("theadth"     , $theadth     ); 
$t->set_var("theadth1"     , $theadth1     ); 

 $t->set_var("listid",$listid);  
$t->set_var("action",$action);
$t->set_var("error",$error);
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址  
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面


?>