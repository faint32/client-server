<?
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gbk'); 
require ("../include/common.inc.php");

$dbfile = new DB_file;
/*
$query = "select * from tb_scattojump where fd_scattojump_oldscatid = '$scatid'";
$dbfile->query($query);
if($dbfile->nf())
{
	$dbfile->next_record();
	$scatid = $dbfile->f(fd_scattojump_newscatid);
}*/
echo $g_uppic."@@".$g_upbackurl."@@".$scatid;
?>