<?
$thismenucode = "1c103";
require ("../include/common.inc.php");

$t = new Template(".", "keep"); 
$t->set_file("image","image.html"); 

$db = new DB_test;
$gourl = "tb_upload_categorylist_b.php" ;
$gotourl = $gourl.$tempurl ;
$query="SELECT * FROM tb_upload_category_list where fd_cat_id='$id'";
$db->query($query);
if($db->nf()){
	$db->next_record();
  $picname= $db->f(fd_cat_thumurl);
  $name=$db->f(fd_cat_name);	
}

$t->set_var('name'  ,$name);
$t->set_var('picname',$picname);
$t->set_var('gotourl',$gotourl);  
$t->set_var("skin",$loginskin);
$t->pparse("out", "image");    # 最后输出页面