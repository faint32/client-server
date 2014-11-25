<?
$thismenucode = "1c103";
require ("../include/common.inc.php");
$db=new DB_test;
$gourl = "tb_upload_categorylist_b.php" ;
$gotourl = $gourl.$tempurl ;
switch ($action)
{
     case "delete":  // ¼ÇÂ¼É¾³ý
	 $query="select *from tb_upload_category_list where fd_cat_id=$fd_cat_id";
	 $db->query($query);
	 $db->next_record();
	 $url=$db->f(fd_cat_url);
	 $thumurl=$db->f(fd_cat_thumurl);
	 @unlink($url);
	 @unlink($thumurl);
     $query="delete from tb_upload_category_list where fd_cat_id='$fd_cat_id'";
     $db->query($query);
     Header("Location: $gotourl");       
	 break;
     default:
     break;
}
?>

