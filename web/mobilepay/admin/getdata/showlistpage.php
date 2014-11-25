<?
require("../include/common.inc.php");

$db=new db_test;
      	  
$t = new Template(".","keep");
$t->set_file("showlistpage","showlistpage.html");


$t->set_var("listid",$listid);
$t->set_var("idname",$idname);
$t->set_var("pagename",$pagename);

// 判断权限 
$t->pparse("out", "showlistpage"); //最后输出界面

?>