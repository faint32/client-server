<?
$thismenucode = "2k503";
require ("../include/common.inc.php");

$db = new DB_test ( );
$t = new Template ( ".", "keep" ); //����һ��ģ��
$t->set_file ( "mobile", "mobile.html" );
$gourl = "tb_mobile_b.php";


$gotourl = $gourl . $tempurl;
require("../include/alledit.1.php");

switch ($action) {
	case "delete" : // �޸ļ�¼	
		$query = "delete from tb_mobile  where fd_mobile_id = '$listid'";
		$db->query ( $query );
		echo ("<script>alert('ɾ���ɹ�!');location.href='$gotourl'</script>");
		
		break;
	case "new" :
		$query = "select * from tb_mobile where fd_mobile_name='$name'";
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "�����Ѵ���!";
		} else {
			$query = "INSERT INTO tb_mobile (
 		            fd_mobile_name ,fd_mobile_time,fd_mobile_size ," .
 		           "fd_mobile_HVGA ,fd_mobile_os , fd_mobile_brandid,fd_mobile_allow
  		          )VALUES (
  		          '$name'     ,now(),'$sizes','$hvga','$os' ,'$brand','$allow'
				)";
			$db->query ( $query );
			Header ( "Location:$gotourl" );
		}
		break;
	case "edit" : // �޸ļ�¼	
	
		$query = "select * from tb_mobile where fd_mobile_name = '$name' and fd_mobile_id <> '$listid'";
		
		$db->query ( $query );
		if ($db->nf ()) {
			$error = "���Ͳ����ظ�,���֤!";
		} else {
			$query = "update tb_mobile set
 		               fd_mobile_name   = '$name' ,fd_mobile_size = '$sizes' ," .
 		              "fd_mobile_HVGA   = '$hvga'  ,fd_mobile_os   = '$os' ," .
 		              "fd_mobile_brandid  = '$brand' ,fd_mobile_allow='$allow'
  		             where fd_mobile_id = '$listid' ";
			$db->query ( $query );
			//echo $query;exit;
			Header ( "Location: $gotourl" );
		}
		
		break;
}
if (empty ( $listid )) {
	$action = "new";
} else {
	$query = "select * from tb_mobile where fd_mobile_id='$listid'";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$mobileid = $db->f ( fd_mobile_id );
		$name = $db->f ( fd_mobile_name );
		$sizes       = $db->f ( fd_mobile_size );
		$os         = $db->f ( fd_mobile_os   );
		$hvga       = $db->f ( fd_mobile_HVGA );
		$brand      = $db->f ( fd_mobile_brandid );
		$allow      = $db->f ( fd_mobile_allow );
		
		if($allow==1){
			$checked1 = "checked";
		} else {
			$checked2 = "checked";
		}
		$action = "edit";
	}
}


  	$arr_hvga = array('320*480','480*800',"960*540","1280*720","240*320","1920*1080","800*600","1240*768","2048*1536","1366*768","2560*1600"); 
	$hvga     = makeselect($arr_hvga,$hvga,$arr_hvga);

	$arr_size = array('3��','3.5��','3.7��','4��','4.3��','4.4��','4.5��','4.99��','5������'); 
	$sizes     = makeselect($arr_size,$sizes,$arr_size);
	$arr_os = array('android','IOS','wphone','����','����'); 
	$os     = makeselect($arr_os,$os,$arr_os);
	
	
	$query="select * from  tb_mobilebrand where fd_mobilebrand_active = '1'";
	$db->query($query);
	if($db->nf())
	{
	while($db->next_record())
	{
		$arr_mobilebranid[]=$db->f(fd_mobilebrand_id);
		$arr_mobilebrandname[]=$db->f(fd_mobilebrand_name);
	}
	}
$brand = makeselect($arr_mobilebrandname,$brand,$arr_mobilebranid); 
	
$t->set_var ( "mobileid", $mobileid );
$t->set_var ( "listid", $listid );
$t->set_var ( "name", $name );
$t->set_var ( "sizes", $sizes );
$t->set_var ( "os"  , $os );
$t->set_var ( "hvga", $hvga );
$t->set_var ( "brand", $brand );
$t->set_var ( "allow", $allow );
$t->set_var("checked1", $checked1);
$t->set_var("checked2", $checked2);
$t->set_var ( "error", $error );
$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // ת�õĵ�ַ


// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "mobile" ); # ������ҳ��


?>

