<?
$thismenucode = "2k112";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test ( );
$gourl = "tb_product_b.php";
$gotourl = $gourl . $tempurl;
require ("../include/alledit.1.php");

switch ($action) {
	case "new" :

		$query="select * from tb_product where fd_product_name='$product_name' or fd_product_no='$product_no'";
		$db->query($query);
		if($db->nf())
		{
			$error='����Ʒ�Ѵ���!����Ҫ�ظ���ӣ�';
		}else{
		$query = "INSERT INTO tb_product(
			   fd_product_name,fd_product_producttypeid,fd_product_productscope,fd_product_no,
			   fd_product_suppid,fd_product_suppno,fd_product_suppname) VALUES(
			   '$product_name','$producttypeid','$productscope','$product_no',
			   '$suppid','$suppno','$suppname'
			   )";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		Header ( "Location: $gotourl" );
		/*echo "<script>alert('����ɹ�!')location.href='$gotourl'</script>";*/
		}
		$action="";
		break;
	case "edit" :
		$query="select * from tb_product where (fd_product_name='$product_name' or fd_product_no='$product_no') and fd_product_id<>'$listid'";
		$db->query($query);
		if($db->nf())
		{
			$error='����Ʒ�Ѵ���!���֤��';
		}else{
		$query = "update tb_product set 
			fd_product_name='$product_name',fd_product_producttypeid='$producttypeid',fd_product_productscope='$productscope',
				fd_product_suppid='$suppid',fd_product_suppno='$suppno',
				fd_product_suppname='$suppname',fd_product_no='$product_no'
		where fd_product_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		/*echo "<script>alert('�޸ĳɹ�!')location.href='$gotourl'</script>";*/
		//echo $gotourl;
		Header ( "Location: $gotourl" );
		}
		$action="";
		break;
	case "delete" :
		$query = "delete from tb_product where fd_product_id='$listid'";
		$db->query ( $query );
		require ("../include/alledit.2.php");
		
		Header ( "Location: $gotourl" );
		break;
	default :
		break;
}

$t = new Template ( ".", "keep" ); //����һ��ģ��
$t->set_file ( "product", "product.html" );

if (empty($listid)){
	$action = "new";
}else{
	$action = "edit";
	$query = "select * from tb_product where fd_product_id='$listid' ";

	$db->query ($query);
	if ($db->nf ()) {
		$db->next_record();

		$product_name = $db->f (fd_product_name);
		$producttypeid = $db->f (fd_product_producttypeid);
		$productscope = $db->f (fd_product_productscope);
		$suppno = $db->f (fd_product_suppno);
		$suppname = $db->f (fd_product_suppname);
		$suppid = $db->f (fd_product_suppid);
		$product_no = $db->f (fd_product_no);
	}

}


//ˢ������������
$query="select * from tb_producttype";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$producttype_id[]=$db->f(fd_producttype_id);
		$producttype_name[]=$db->f(fd_producttype_name);
	}
}


$producttypeid = makeselect($producttype_name,$producttypeid,$producttype_id);

$arr_scopecode=array("creditcard","bankcard");
$arr_scopename=array("���ÿ�","���");
$productscope = makeselect($arr_scopename,$productscope,$arr_scopecode); 


$t->set_var ("listid", $listid ); //listid
$t->set_var ("product_name", $product_name ); //listid
$t->set_var ("product_no", $product_no ); //listid
$t->set_var ("producttypeid", $producttypeid );
$t->set_var ("productscope", $productscope );
$t->set_var ("suppno", $suppno );
$t->set_var ("suppname", $suppname );
$t->set_var ("suppid", $suppid );
$t->set_var ("action", $action );
$t->set_var ("gotourl", $gotourl ); // ת�õĵ�ַ    
$t->set_var ("fckeditor", $fckeditor );
$t->set_var ("error", $error );
// �ж�Ȩ�� 
include ("../include/checkqx.inc.php");
$t->set_var ( "skin", $loginskin );

$t->pparse ( "out", "product" ); # ������ҳ��


?>

