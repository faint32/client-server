<?
$thismenucode = "2k226";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("sel_storageview","sel_storageview.html");

echo "<script>location.href='storageview.php';</script>";	
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);

include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->pparse("out", "sel_storageview");       # 最后输出页面


?>