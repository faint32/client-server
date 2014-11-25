<?
$thismenucode = "2k221";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("datetable_query","datetable_query.html");

$year = date("Y", mktime()) ;
$month = date("m", mktime()) ;
$day = date("d", mktime()) ;
for($i=2;$i>=0;$i--){
	$arr_year[] = $year-$i;
}
$year = makeselect($arr_year,$year,$arr_year);

for($i=1;$i<=12;$i++){
	$arr_month[] = $i;
}
$month = makeselect($arr_month,$month,$arr_month);

for($i=1;$i<=31;$i++){
	$arr_day[] = $i;
}
$day = makeselect($arr_day,$day,$arr_day);
$t->set_var("year",$year);
$t->set_var("month",$month);
$t->set_var("day",$day);


// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);

$t->pparse("out", "datetable_query");       # 最后输出页面


?>