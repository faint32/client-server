<?
require("../include/common.inc.php");
require("../include/json.php");
$db=new db_test;
      	  
$t = new Template(".","keep");
$t->set_file("getauthor","likegetauthor.html");



$t->set_var("authorid",$authorid);

// 判断权限 
$t->pparse("out", "getauthor"); //最后输出界面

?>