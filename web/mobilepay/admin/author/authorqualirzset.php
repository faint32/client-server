<?
$thismenucode = "2k307";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test ( );
$gourl = "tb_authorqualirzset_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :
		//$auqid = $_POST ['auqid'];
		$authorqualirzset_no = $_POST ['authorqualirzset_no'];
		$authortypeid = $_POST ['authortypeid'];
		$auqid= implode(",", $arr_auqid);
		$query="select * from tb_authorqualirzset where fd_auqrz_no='$authorqualirzset_no'";
		$db->query($query);
		if($db->nf()){
			$error = "提款申请资质已经存在！不需要重复添加！";
		}else{
		$query = "INSERT INTO tb_authorqualirzset(
			   fd_auqrz_auqid,fd_auqrz_no,fd_auqrz_authortypeid) VALUES(
			   '$auqid','$authorqualirzset_no','$authortypeid')";
		$db->query ( $query );
		$listid = $db->insert_id ();
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		}
		$action="";
		
		break;
	case "edit" :
	
		$auqid= implode(",", $arr_auqid);
		$query="select * from tb_authorqualirzset where fd_auqrz_no='$authorqualirzset_no' and fd_auqrz_id<>'$listid'";
		$db->query($query);
		if($db->nf()){
			$error = "提款申请资质已经存在！请查证！";
		}else{
		$query = "update tb_authorqualirzset set fd_auqrz_auqid='$auqid',fd_auqrz_no='$authorqualirzset_no',fd_auqrz_authortypeid='$authortypeid' where fd_auqrz_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		}
		$action="";
		break;
	case "delete" :
		$query = "delete from tb_authorqualirzset where fd_auqrz_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" ); //调用一个模版
$t->set_file ( "author", "authorqualirzset.html" );

if (empty( $listid )) {
	$action = "new";
} else {
	$action = "edit";
	$query = "select * from tb_authorqualirzset where fd_auqrz_id='$listid' ";
	$db->query ( $query );
	if ($db->nf ()) {
		$db->next_record ();
		$authorqualirzset_id = $db->f ( fd_auqrz_id );
		$auqid = $db->f ( fd_auqrz_auqid );
		$authorqualirzset_no = $db->f ( fd_auqrz_no );
		$authortypeid = $db->f ( fd_auqrz_authortypeid );
	}
}
//
$arr_auq=explode(',', $auqid);
//$arr_auq = array_slice()
$checked="";
$showcheck="";
$query = "select * from tb_authorquali " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$tmpauqid=$db->f(fd_auq_id);
		$agencyname=$db->f(fd_auq_name);
		$checked= "";
		foreach($arr_auq as $value)
		{
		if($tmpauqid==$value)
		{
			$checked= "checked";
		}
		}
		$showcheck.='<input type="checkbox"  name="arr_auqid[]" value="'.$tmpauqid.'" '.$checked.'>'.$agencyname;
	
	}
}

$t->set_var("showcheck"  , $showcheck     );

$auqid = makeselect($arr_authorquali,$auqid,$arr_authorqualiid); 

$query = "select * from tb_authortype " ;
$db->query($query);
if($db->nf()){
   while($db->next_record()){		
		   $arr_authortypeid[]     = $db->f(fd_authortype_id)  ; 
		   $arr_authortype[]       = $db->f(fd_authortype_name);    
   }
}
$authortypeid = makeselect($arr_authortype,$authortypeid,$arr_authortypeid); 

$t->set_var ( "id", $id ); //listid
$t->set_var ( "listid", $listid ); //listid
$t->set_var ( "auqid", $auqid );
$t->set_var ( "authorqualirzset_no", $authorqualirzset_no );
$t->set_var ( "authortypeid", $authortypeid );

$t->set_var ( "action", $action );
$t->set_var ( "gotourl", $gotourl ); // 转用的地址    
$t->set_var ( "fckeditor", $fckeditor );
$t->set_var ( "error", $error );
// 判断权限 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "author" ); # 最后输出页面


?>

