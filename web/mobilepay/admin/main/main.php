<?
require ("../include/common.inc.php");
$db = new DB_test ;

$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("main","main.html"); 
$t->set_block("main", "MAILBK", "mailbks");  //ʹ��һ����

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

$t->set_var("menutypephp",$menutypephp);  // ������ҳ����

$t->set_var("indexpagetype",$indexpagetype);  // ������ҳ����
$t->set_var("skin",$loginskin);  // ����Ƥ��
$t->set_var("gotourl",$gotourl);  // ת�õĵ�ַ
$t->pparse("out", "main");    # ������ҳ��
?>