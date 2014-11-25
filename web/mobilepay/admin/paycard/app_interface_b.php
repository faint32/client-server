<?
$thismenucode = "2k508";
require ("../include/common.inc.php");
require ("../include/browse.inc.php");
$db = new db_test ( );
$db2 = new db_test ( );
$db3 = new db_test ( );
$gotourl = "tb_appmenu_b.php";
require("../include/alledit.1.php");
$t = new Template ( ".", "keep" );
$t->set_file("app_interface_b","app_interface_b.html");
$t->set_block ("app_interface_b", "prolist", "prolists" );
if(!empty($listid)){
	$query="select  fd_interface_name as intfname,
					fd_interface_no as intfno,
					fd_interface_apinamefunc as apinamefunc,
					fd_interface_apiname as apiname,
					fd_interface_active as active,
					fd_interface_ischeck as ischeck,
					fd_interface_id as intfid
					from web_test_interface where fd_interface_appmenuid = $listid order by fd_interface_sortorder desc";
	$db->query($query);
	$arr_result = $db->getFiledData('');
	
	//print_r($arr_result);
	foreach($arr_result as $val)
	{
	if($val['active']=='1'){$val['active']='已激活';}
	if($val['active']=='0'){$val['active']='未激活';}
	if($val['ischeck']=='0'){$val['ischeck']='否';}
	if($val['ischeck']=='1'){$val['ischeck']='是';}
	$t->set_var($val);
	$t->parse("prolists", "prolist", true);	
	}
	if(empty($arr_result))
	{
	$t->parse("prolists", "", true);	
	}
	}
	
	$t->set_block ("app_interface_b", "prolisti", "prolistis" );
if(!empty($listid)){
	$query="select  fd_interface_name as intfname,
					fd_interface_no as intfno,
					fd_interface_apinamefunc as apinamefunc,
					fd_interface_apiname as apiname,
					fd_interface_active as active,
					fd_interface_ischeck as ischeck,
					fd_interface_id as intfid
					from web_test_interface where fd_interface_appmenuid = 0 order by fd_interface_sortorder desc";
	$db->query($query);
	$arr_result = $db->getFiledData('');
	//print_r($arr_result);
	foreach($arr_result as $val)
	{
	if($val['active']=='1'){$val['active']='已激活';}
	if($val['active']=='0'){$val['active']='未激活';}
	if($val['ischeck']=='0'){$val['ischeck']='否';}
	if($val['ischeck']=='1'){$val['ischeck']='是';}
	$t->set_var($val);
	$t->parse("prolistis", "prolisti", true);	
	}
	if(empty($arr_result))
	{
	$t->parse("prolistis", "", true);	
	}
	}
$t->set_var ( "listid", $listid );
$t->set_var ( "intfid", $intfid );
$t->set_var ( "gotourl", $gotourl );
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );
$t->pparse ( "out", "app_interface_b" );

?>