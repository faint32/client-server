<?
require ("../include/common.inc.php");
$db = new DB_test ;

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("main","main.html"); 
$t->set_block("main", "MAILBK", "mailbks");  //使用一个块

session_register("openmain");
$openmain = 1;
$hors = 0;
if($hors == 0){
    echo "<script>location.href='../main_c/main.php';</script>";
}
if($loginindextype==0){
	$indexpagetype = "right.php";
}else{
  $indexpagetype = "right.php";
}

$menutype = 0;
$query = "select * from web_teller where fd_tel_name = '$loginname'";
$db->query($query);
if($db->nf()){
   $db->next_record();
   $menutype = $db->f(fd_tel_menulogintype);
}

if($menutype==0){
	$menutypephp = "left.php";
}else{
  $menutypephp = "shortcut.php";
}

$t->set_var("menutypephp",$menutypephp);  // 调用首页类型

$t->set_var("indexpagetype",$indexpagetype);  // 调用首页类型
$t->set_var("skin",$loginskin);  // 调用皮肤
$t->set_var("gotourl",$gotourl);  // 转用的地址
$t->pparse("out", "main");    # 最后输出页面
?>