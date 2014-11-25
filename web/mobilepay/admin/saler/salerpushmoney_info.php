<?
$thismenucode = "2n005";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("salerpushmoney_info","salerpushmoney_info.html"); 
$gourl = "salerpushmoney.php";

$gotourl = $gourl.$tempurl ;

$arr_text = array("流水号","刷卡器设备号","网导","收入","支出","交易方式","交易摘要","时间");

for($i=0;$i<count($arr_text);$i++)
{
	$theadth .=' <th>'.$arr_text[$i].'</th>';

}


$t->set_var("paycardid"     , $paycardid     );
$t->set_var("year"     , $year );
$t->set_var("month"     , $month );
$t->set_var("theadth"     , $theadth     ); 
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "salerpushmoney_info");    # 最后输出页面


?>

