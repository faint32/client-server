<?
require("../include/common.inc.php");
require("../include/json.php");
$db=new db_test;
      	  
$t = new Template(".","keep");
$t->set_file("getproduct","likegetproduct.html");



$t->set_var("suppid",$suppid);
$t->set_var("productno",$productno);

// �ж�Ȩ�� 
$t->pparse("out", "getproduct"); //����������

?>