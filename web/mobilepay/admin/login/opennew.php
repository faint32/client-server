<?
session_start();
require ("../include/common.inc.php");
 

if($openmain == 1){
      $disbutton = "onClick='winopen()' style='CURSOR: hand'";  //修改测试使用 
     
}else if($openmain == ""){
    $disbutton = "onClick='winopen()' style='CURSOR: hand'";
     
}
 

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("opennew","opennew.html"); 

$t->set_var("disbutton",$disbutton); 
$t->set_var("skin",$loginskin);  // 调用皮肤
$t->set_var("gotourl",$gotourl);  // 转用的地址
$t->pparse("out", "opennew");    # 最后输出页面
?>