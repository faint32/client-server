<?
$thismenucode = "2k226";
require("../include/common.inc.php");

$db=new db_test;

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("sel_storageview","sel_storageview.html");

echo "<script>location.href='storageview.php';</script>";	
$t->set_var("gotourl",$gotourl);       // ת�õĵ�ַ
$t->set_var("error",$error);

include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);

$t->pparse("out", "sel_storageview");       # ������ҳ��


?>