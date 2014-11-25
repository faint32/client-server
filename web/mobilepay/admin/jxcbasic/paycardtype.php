<?
$thismenucode = "2k403";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test ( );
$gourl = "tb_paycardtype_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :
		$paycardtype_name = $_POST ['paycardtype_name'];
		$paycardtype_no = $_POST ['paycardtype_no'];
		$paycardtype_remark = $_POST ['paycardtype_remark'];
		$query="select * from tb_paycardtype where fd_paycardtype_name='$paycardtype_name' or fd_paycardtype_no='$paycardtype_no'";
		$db->query($query);
		if($db->nf()){
			$error="该刷卡器分类已存在!不需要重复添加！";
		}else{
		$query = "INSERT INTO tb_paycardtype(
			   fd_paycardtype_name,fd_paycardtype_no,fd_paycardtype_remark) VALUES(
			   '$paycardtype_name','$paycardtype_no','$paycardtype_remark')";
		$db->query ( $query );
		$listid = $db->insert_id ();
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		}
		$action="";
		break;
	case "edit" :
		$paycardtype_name = $_POST ['paycardtype_name'];
		$paycardtype_no = $_POST ['paycardtype_no'];
		$paycardtype_remark = $_POST ['paycardtype_remark'];
		$query="select * from tb_paycardtype where (fd_paycardtype_name='$paycardtype_name' or fd_paycardtype_no='$paycardtype_no') and fd_paycardtype_id<>'$listid'";
		$db->query($query);
		if($db->nf()){
			$error="该刷卡器分类已存在!请查证！";
		}else{
		$query = "update tb_paycardtype set fd_paycardtype_name='$paycardtype_name',fd_paycardtype_no='$paycardtype_no',fd_paycardtype_remark='$paycardtype_remark' where fd_paycardtype_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		}
		$action="";
		break;
	case "delete" :
		$query = "delete from tb_paycardtype where fd_paycardtype_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "author", "paycardtype.html" );

if (empty ( $listid )) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_paycardtype where fd_paycardtype_id='$listid' ";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$paycardtype_id = $db->f ( fd_paycardtype_id );
		$paycardtype_name = $db->f ( fd_paycardtype_name );
		$paycardtype_no = $db->f ( fd_paycardtype_no );
		$paycardtype_remark = $db->f ( fd_paycardtype_remark );
	}

}

$t->set_var ( "listid", $listid ); //listid
$t->set_var ( "paycardtype_id", $paycardtype_id ); //listid
$t->set_var ( "paycardtype_name", $paycardtype_name );
$t->set_var ( "paycardtype_no", $paycardtype_no );
$t->set_var ( "paycardtype_remark", $paycardtype_remark );

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址    
$t->set_var ( "fckeditor", $fckeditor );
$t->set_var ( "error", $error );
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "author" ); # 最后输出页面


?>

