<?
$thismenucode = "sys";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl = "tb_fcategory_b.php" ;
$gotourl = $gourl.$tempurl ;
$dir ="../file/";
$query="select * from tb_fcategory
		left join tb_scategoty on fd_scat_fcatid=fd_fcat_id ";
$db->query($query);
while($db->next_record())
{
 if(mkdirs($dir.$db->f(fd_fcat_foldername)."/".$db->f(fd_scat_foldername)))
  {
	echo "³É¹¦:".$dir.$db->f(fd_fcat_foldername)."/".$db->f(fd_scat_foldername)."<br/>";
  }else{
	echo "Ê§°Ü:".$dir.$db->f(fd_fcat_foldername)."/".$db->f(fd_scat_foldername)."<br/>";	
  }
}

function mkdirs($dir,$mode=0777)
{
if(is_dir($dir)||@mkdir($dir,$mode)) return true;
if(!mkdirs(dirname($dir),$mode)) return false;
return @mkdir($dir,$mode);
}

?>

