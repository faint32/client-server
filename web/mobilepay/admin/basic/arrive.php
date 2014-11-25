<?
$thismenucode = "2k503";
require ("../include/common.inc.php");

$db = new DB_test ( );
$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "arrive", "arrive.html" );
$gourl = "tb_arrive_b.php";

$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
$addday = str_replace("工作日T+","",$arrivename);
switch ($action) {
	case "delete" : // 修改记录	
		$query = "delete from tb_arrive  where fd_arrive_id = '$arriveid'";
		$db->query ( $query );
		echo ("<script>alert('删除成功!');location.href='$gotourl'</script>");
		
		break;
	case "new" :
		$query = "select * from tb_arrive where fd_arrive_name='$arrivename'";
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "到帐周期已存在!";
		} else {
			$query = "INSERT INTO tb_arrive (
 		            fd_arrive_name ,fd_arrive_time,fd_arrive_addday
  		          )VALUES (
  		          '$arrivename'     ,now(),'$addday'
				)";
			$db->query ( $query );
			Header ( "Location:$gotourl" );
		}
		break;
	case "edit" : // 修改记录	
		
		$query = "select * from tb_arrive where fd_arrive_name = '$arrivename' and fd_arrive_id <> '$arriveid'";
		
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "到帐周期不能重复,请查证!";
		} else {
			$query = "update tb_arrive set
 		               fd_arrive_name   = '$arrivename' ,fd_arrive_addday = '$addday' 
  		             where fd_arrive_id = '$arriveid' ";
			$db->query ( $query );
			
			Header ( "Location: $gotourl" );
		}
		
		break;
}
if (empty ( $listid )) {
	$action = "new";
} else {
	$query = "select * from tb_arrive where fd_arrive_id='$listid'";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$arriveid = $db->f ( fd_arrive_id );
		$arrivename = $db->f ( fd_arrive_name );
		$action = "edit";
	}
}
$t->set_var ( "arriveid", $arriveid );
$t->set_var ( "listid", $listid );
$t->set_var ( "arrivename", $arrivename );
$t->set_var ( "arriverates", $arriverates );
$t->set_var ( "arrivebear", $arrivebear );
$t->set_var ( "error", $error );
$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址


// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "arrive" ); # 最后输出页面


?>

