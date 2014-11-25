<?
require("../include/common.inc.php");
require("../include/json.php");
$db=new db_test;
      	  
$t = new Template(".","keep");
$t->set_file("likegetsupp","likegetsupp.html");



$t->set_var("value",$value);


// 判断权限 
$t->pparse("out", "likegetsupp"); //最后输出界面

?>