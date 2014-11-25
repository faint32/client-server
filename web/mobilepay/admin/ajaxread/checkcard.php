<?
header('Content-Type:text/html;charset=GB2312'); 

require ("../include/common.inc.php");

$db   = new DB_test;
$query="select * from web_salercard where fd_salercard_no  = '$mcardno'";
$db->query($query);
if($db->nf())
{
	$db->next_record();
	$mcardid = $db->f(fd_salercard_id);
	
}
$query = "select fd_organmem_mcardid from tb_organmem where fd_organmem_id='$listid' and fd_organmem_mcardid ='$mcardid'";
$db->query($query);
if($db->nf())
{
	echo "1";
    exit;
}
$query = "select * from web_salercard 
left join web_saler on fd_saler_id = fd_salercard_salerid
where fd_salercard_no  = '$mcardno' and fd_salercard_state = 1 and fd_salercard_memberid = 0 
and fd_salercard_id not in (select fd_organmem_mcardid from tb_organmem where fd_organmem_id<>'$listid')";
$db->query($query);

if($db->nf()){
	$db->next_record();
	$mcardid = $db->f(fd_salercard_id);
	$mcardno = $db->f(fd_salercard_no);
	$dgman = $db->f(fd_saler_truename);
	
$query = "update tb_organmem set fd_organmem_mcardid = '$mcardid' where fd_organmem_id = '$listid'";	
$db->query($query);
	
	echo $mcardid."@@@".$mcardno."@@@".$dgman;
}else{
	echo "0";
}

?>