<?
require("../include/common.inc.php");
require("../include/json.php");
$db=new db_test;
      	  
$t = new Template(".","keep");
$t->set_file("getauthor","likegetauthor.html");



$t->set_var("authorid",$authorid);

// �ж�Ȩ�� 
$t->pparse("out", "getauthor"); //����������

?>