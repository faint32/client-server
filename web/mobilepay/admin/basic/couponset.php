<?
$thismenucode = "2n301";
require ("../include/common.inc.php");

$db = new DB_test;
$t = new Template(".", "keep");          //����һ��ģ��
$t->set_file("couponset","couponset.html"); 
switch ($action)
{	
	case "new":

			$query="INSERT INTO tb_couponset (
 		            fd_couponset_sxf
  		          )VALUES (
  		          '$sxf'    
				        )";
		   $db->query($query);
		  $listid = $db->insert_id();
		break;
	case "edit":   // �޸ļ�¼	

         $query = "update tb_couponset set
 		               fd_couponset_sxf   = '$sxf' 
  		             where fd_couponset_id = '$listid' ";
	      $db->query($query);
		break;
}

	$query="select * from tb_couponset";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$listid=$db->f(fd_couponset_id);
		$sxf=$db->f(fd_couponset_sxf);
		$action="edit";
	}else{
		$action="new";
	}


$t->set_var("listid"     , $listid     );
$t->set_var("sxf"     , $sxf     );

$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // ת�õĵ�ַ


// �ж�Ȩ�� 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "couponset");    # ������ҳ��


?>

