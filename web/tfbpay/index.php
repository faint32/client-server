<?
error_reporting(0);
require ("include/common.inc.php");

	
$db  = new DB_test;
$db2 = new DB_test;
$db3 = new DB_test;

$activecmmupid = 0;

$t = new Template(".","keep");

$t->set_file("index"       ,"tfb.html");   //
$out_html = "http://www.tfbpay.cn/tfb/";
$t->set_var("out_html"   ,  $out_html  );
$t->parse("out"   ,  "index"   );  
$t->p("out" , "index") ;    # ������ҳ��


?>