<?php
//$thisprgcode = "sys";
require ("../include/common.inc.php");

$t = new Template(".", "keep");
$t->set_file("rigth","right.html");


$t->set_var("skin",$loginskin);
$t->pparse("out", "rigth");    # 最后输出页面

?>