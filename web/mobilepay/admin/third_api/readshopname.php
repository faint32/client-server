<?php

function getauthorshop($shopid) {
	$db = new DB_shop ();
	$query = "select fd_shop_name as shopname from tb_shop where fd_shop_id='$shopid'";
	
	$arr_msg =  $db->get_one ( $query ) ;
	
	$msg = $arr_msg['shopname'];
	return $msg;
	
}

function getauthorshopbyname($shopname) {
	$db = new DB_shop ();
	$query = "select fd_shop_name as shopname from tb_shop where fd_shop_name like '%{$shopid}%' limit 10";
	$db->query ( $query );
	$arr_msg =  $db->getFiledData (1) ;
	return $arr_msg;
};
//echo getauthorshop(100);

?>