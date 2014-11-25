<?php
	require("../include/common.inc.php");
	$db = new db_test;
	if ( !empty($listid) )
	{
		$query = "UPDATE tb_paymoneylist SET fd_pymylt_times = ".($times + 1)." WHERE fd_pymylt_id = ".$listid;
		$db->query($query);
		echo 'success';
	}
	else
	{
		echo 'failure';
	}
?>