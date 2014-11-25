<?php
require ("../include/config.inc.php");
$db = new DB_test;
$db1 = new DB_test;
$db2 = new DB_test;

$query = "select fd_order_memeberid from web_order where fd_order_id = '$orderid'";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$memid = $db->f(fd_order_memeberid);
}

$query = "select max(fd_order_date) as lastbuy,sum(fd_order_allmoney) as allmoney from web_order where (fd_order_state = 6 or fd_order_state = 7) and fd_order_zf = 0 and fd_order_memeberid = '$memid' group by fd_order_memeberid";
$db->query($query);
if($db->nf()){
	$db->next_record();
	$lastbuy = $db->f(lastbuy);
	$allmoney = $db->f(allmoney);
	
	$query = "update tb_organmem set fd_organmem_lastsaletime = '$lastbuy' , fd_organmem_allmoney = '$allmoney' where fd_organmem_id = '$memid'";
	$db->query($query);
}
?>