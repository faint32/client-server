<?
require ("../include/common.inc.php");

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("top","top.html"); 
$t->set_block("top", "MAILBK", "mailbks");  //ʹ��һ����



$year  = date( "Y", mktime()) ;
$month = date( "m", mktime()) ;
$day   = date( "d", mktime()) ;

$t->set_var("loginpartname"     , $loginpartname);
$t->set_var("loginname"         , $loginname);
$t->set_var("year"         , $year         );
$t->set_var("month"        , $month        );
$t->set_var("day"          , $day          );
$t->set_var("skin",$loginskin);  // ����Ƥ��
$t->pparse("out", "top");    # ������ҳ��
?>