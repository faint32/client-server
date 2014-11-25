<?php
require ("../include/common.inc.php");
$db = new db_test ( );
$gotourl = "app_interface_b.php?listid=$listid";
switch($type){
 case "save":
 	//echo $intfid."aaaaa</br>";
	$query="select * from web_test_interface where fd_interface_appmenuid = 0 and fd_interface_id =".$intfid;
	$db->query($query);
	if($db->nf()){
		$query="update web_test_interface set fd_interface_appmenuid = $listid where fd_interface_id =".$intfid;
		//echo $query;exit;
		$db->query($query);
		require("../include/alledit.2.php");
		Header("Location: $gotourl");
	}else{
		echo "<script>alert('该接口已经被绑定，请查证！');location.href='$gotourl'</script>";
	}
 break;
 case "jb":
 	$query="update web_test_interface set fd_interface_appmenuid = 0 where fd_interface_id = ".$intfid;
	$db->query($query);
	require("../include/alledit.2.php");
	Header("Location: $gotourl");
	break;
 case "delete":
 	$query="delete from web_test_interface where fd_interface_id='$intfid'";
	$db->query($query);
	require("../include/alledit.2.php");
	Header("Location: $gotourl");
	break;
}
?>