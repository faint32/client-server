<?
require("../include/common.inc.php");

$db=new db_test;
      	  
$t = new Template(".","keep");
$t->set_file("showlistpage","showlistpage.html");


$t->set_var("listid",$listid);
$t->set_var("idname",$idname);
$t->set_var("pagename",$pagename);

// �ж�Ȩ�� 
$t->pparse("out", "showlistpage"); //����������

?>