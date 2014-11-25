<?
require ("../include/common.inc.php");

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("top","top.html"); 
$t->set_block("top", "MAILBK", "mailbks");  //使用一个块



$year  = date( "Y", mktime()) ;
$month = date( "m", mktime()) ;
$day   = date( "d", mktime()) ;

$t->set_var("loginpartname"     , $loginpartname);
$t->set_var("loginname"         , $loginname);
$t->set_var("year"         , $year         );
$t->set_var("month"        , $month        );
$t->set_var("day"          , $day          );
$t->set_var("skin",$loginskin);  // 调用皮肤
$t->pparse("out", "top");    # 最后输出页面
?>