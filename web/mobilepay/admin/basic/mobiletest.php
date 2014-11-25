<?
$thismenucode = "2k503";
require ("../include/common.inc.php");

$db = new DB_test ( );
$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "mobiletest", "mobiletest.html" );
$gourl = "tb_mobiletest_b.php";

$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
switch ($action) {
	case "delete" : // 修改记录	
		$query = "delete from tb_mobiletest  where fd_mt_id = '$listid'";
		$db->query ( $query );
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
		
		break;
	case "new" :

			$query = "INSERT INTO tb_mobiletest (
 		            fd_mt_mobileid ,fd_mt_tester,fd_mt_date ," .
 		           "fd_mt_appmnuid ,fd_mt_testcount,fd_mt_yescount ,fd_mt_memo 
  		          )VALUES (
  		          '$mobileid'     ,'$tester'	,	'$date'		," .
  		         "'$appmnuid'     ,'$testcount' ,   '$yescount' , '$memo'
				)";
			$db->query ( $query );
			Header ( "Location:$gotourl" );
		
		break;
	case "edit" : // 修改记录	

			$query = "update tb_mobiletest set
 		               fd_mt_mobileid   = '$mobileid' ,fd_mt_date = '$date' ," .
 		              "fd_mt_appmnuid   = '$appmnuid' ,fd_mt_testcount = '$testcount' ," .
 		              "fd_mt_yescount   = '$yescount' ,fd_mt_tester    = '$tester' ," .
 		              "fd_mt_memo       = '$memo'   
  		             where fd_mt_id = '$listid' ";
			$db->query ( $query );
			
			Header ( "Location: $gotourl" );
		
		
		break;
}
if (empty ( $listid )) {
	$action = "new";
} else {
	$query = "select * from tb_mobiletest where fd_mt_id='$listid'";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$listid   = $db->f ( fd_mt_id );
		$mobileid = $db->f ( fd_mt_mobileid );
		$date      = $db->f ( fd_mt_date );
		$appmnuid  = $db->f ( fd_mt_appmnuid );
		$testcount = $db->f ( fd_mt_testcount );
		$yescount  = $db->f ( fd_mt_yescount );
		$tester    = $db->f ( fd_mt_tester );
		$memo      = $db->f(fd_mt_memo);
		$action    = "edit";
		
	}
}

$query="select * from  tb_mobile where fd_mobile_active = '1'";
	$db->query($query);
	if($db->nf())
	{
	while($db->next_record())
	{
		$arr_mobileid[]=$db->f(fd_mobile_id);
		$arr_mobilename[]=$db->f(fd_mobile_name);
	}
	}
$mobileid = makeselect($arr_mobilename,$mobileid,$arr_mobileid); 

$query="select * from  tb_appmenu where fd_appmenu_active = '1' order by fd_appmnu_id ";
	$db->query($query);
	if($db->nf())
	{
	while($db->next_record())
	{
		$arr_appmnuid[]=$db->f(fd_appmnu_id);
		$arr_appmnuname[]=$db->f(fd_appmnu_name);
	}
	}
$appmnuid = makeselect($arr_appmnuname,$appmnuid,$arr_appmnuid); 

$t->set_var ( "listid", $listid );
$t->set_var ( "listid", $listid );
$t->set_var ( "mobileid"	, $mobileid );
$t->set_var ( "date"		, $date );
$t->set_var ( "appmnuid"	, $appmnuid );
$t->set_var ( "testcount"	, $testcount );
$t->set_var ( "yescount"	, $yescount );
$t->set_var ( "tester"		, $tester );
$t->set_var ( "memo"		, $memo );
$t->set_var ( "error"		, $error );
$t->set_var ( "action"		, $action );
$t->set_var ( "gotourl"		, $gotourl ); // 转用的地址


// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "mobiletest" ); # 最后输出页面


?>

