<?
$thismenucode = "2k503";
require ("../include/common.inc.php");

$db = new DB_test ( );
$t = new Template ( ".", "keep" ); //����һ��ģ��
$t->set_file ( "arrive", "arrive.html" );
$gourl = "tb_arrive_b.php";

$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");
$addday = str_replace("������T+","",$arrivename);
switch ($action) {
	case "delete" : // �޸ļ�¼	
		$query = "delete from tb_arrive  where fd_arrive_id = '$arriveid'";
		$db->query ( $query );
		echo ("<script>alert('ɾ���ɹ�!');location.href='$gotourl'</script>");
		
		break;
	case "new" :
		$query = "select * from tb_arrive where fd_arrive_name='$arrivename'";
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "���������Ѵ���!";
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
	case "edit" : // �޸ļ�¼	
		
		$query = "select * from tb_arrive where fd_arrive_name = '$arrivename' and fd_arrive_id <> '$arriveid'";
		
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "�������ڲ����ظ�,���֤!";
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
$t->set_var ( "gotourl", $gotourl ); // ת�õĵ�ַ


// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "arrive" ); # ������ҳ��


?>

