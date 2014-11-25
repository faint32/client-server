<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
require ("../function/changekg.php");
$db=new db_test;
$query="select * from web_orderdetail 
        left join  web_order on fd_order_id                = fd_orderdetail_orderid
        where fd_orderdetail_id='$id'";	           
$db->query($query);
if($db->nf())
{
	$db->next_record();
	$state = $db->f(fd_order_state);
	
}
if($state>1)
{
	echo "0@@".$query;
    exit;
}

$query = "update web_orderdetail set fd_orderdetail_quantity = '$value'  where fd_orderdetail_id = '$id'";
$db->query($query);	

echo "1@@".$value.$query;        


?>
