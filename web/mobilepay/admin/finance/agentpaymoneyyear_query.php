<?
$thismenucode = "2k223";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("agentpaymoneyyear_query","agentpaymoneyyear_query.html");

$year = date("Y", mktime()) ;
for($i=5;$i>=0;$i--){
	$arr_year[] = $year-$i;
}
$year = makeselect($arr_year,$year,$arr_year);

$t->set_var("year",$year);

// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);

$t->pparse("out", "agentpaymoneyyear_query");       # 最后输出页面


?>