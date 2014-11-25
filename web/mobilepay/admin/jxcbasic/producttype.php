<?
$thismenucode = "2k113";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test ( );
$gourl = "tb_producttype_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :
		$producttype_name = $_POST ['producttype_name'];
		$producttype_no = $_POST ['producttype_no'];
		$producttype_remark = $_POST ['producttype_remark'];
		$query = "select * from tb_producttype where fd_producttype_no='$producttype_no' or fd_producttype_name='$producttype_name' ";
		$db->query($query);
		if($db->nf()){
			$error='该分类已存在!不需要重复添加！';
		}else{
		$query = "INSERT INTO tb_producttype(
			   fd_producttype_name,fd_producttype_no,fd_producttype_remark) VALUES(
			   '$producttype_name','$producttype_no','$producttype_remark')";
		$db->query ( $query );
		$listid = $db->insert_id ();
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		}
		$action="";
		break;
	case "edit" :
		$producttype_name = $_POST ['producttype_name'];
		$producttype_no = $_POST ['producttype_no'];
		$producttype_remark = $_POST ['producttype_remark'];
		$query="select * from tb_producttype where (fd_producttype_no='$producttype_no'or fd_producttype_name='$producttype_name') and fd_producttype_id<>'$listid'";
		$db->query($query);
		if($db->nf()){
			$error='该分类已存在!请查证！';
		}else{
		$query = "update tb_producttype set fd_producttype_name='$producttype_name',fd_producttype_no='$producttype_no',fd_producttype_remark='$producttype_remark' where fd_producttype_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		}
		$action="";
		break;
	case "delete" :
		$query = "delete from tb_producttype where fd_producttype_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "author", "producttype.html" );

if (empty ( $listid )) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_producttype where fd_producttype_id='$listid' ";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$producttype_id = $db->f ( fd_producttype_id );
		$producttype_name = $db->f ( fd_producttype_name );
		$producttype_no = $db->f ( fd_producttype_no );
		$producttype_remark = $db->f ( fd_producttype_remark );
	}

}

$t->set_var ( "listid", $listid ); //listid
$t->set_var ( "producttype_id", $producttype_id ); //listid
$t->set_var ( "producttype_name", $producttype_name );
$t->set_var ( "producttype_no", $producttype_no );
$t->set_var ( "producttype_remark", $producttype_remark );

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址    
$t->set_var ( "fckeditor", $fckeditor );
$t->set_var ( "error", $error );
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "author" ); # 最后输出页面


?>

