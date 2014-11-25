<?
$thismenucode = "2k112";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("yeartable_query","monthtable_query.html");

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

$t->pparse("out", "yeartable_query");       # 最后输出页面


?>