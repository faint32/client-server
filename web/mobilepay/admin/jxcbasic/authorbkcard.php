<?
$thismenucode = "2k114";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("authorbkcard","authorbkcard.html"); 
$gourl = "tb_authorbkcard_b.php";
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
 switch ($action)
{	
	case "new":
	   $bankid = $_POST['bankid'];
	   $authorbkcardkey = $_POST['authorbkcardkey'];
	   $authorbkcardtype = $_POST['authorbkcardtype'];
	   $authorbkcard_active = $_POST['authorbkcard_active'];
	   $isnew = $_POST['isnew'];
	   $authorid = $_POST['authorid'];
	   $datetime = $_POST['datetime'];
		$query="select * from tb_authorbkcard where fd_authorbkcard_key='$authorbkcardkey'";
		$db->query($query);
		if($db->nf()){
			$error="银行该已存在!不需要重复添加！";
		}else{
	   $query="INSERT INTO tb_authorbkcard(
			   fd_authorbkcard_key,fd_authorbkcard_type,fd_authorbkcard_authorid,fd_authorbkcard_bankid,
			   fd_authorbkcard_isnew,fd_authorbkcard_active,fd_authorbkcard_datetime) VALUES(
			   '$authorbkcardkey','$authorbkcardtype','$authorid','$bankid',
			   '$isnew','$authorbkcard_active','$datetime')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;

	case "edit":   // 修改记录	
		 $bankid = $_POST['bankid'];
	   	 $authorbkcardkey = $_POST['authorbkcardkey'];
	   	 $authorbkcardtype = $_POST['authorbkcardtype'];
	   	 $authorbkcard_active = $_POST['authorbkcard_active'];
	   	 $isnew = $_POST['isnew'];
	   	 $authorid = $_POST['authorid'];
	   	 $datetime = $_POST['datetime'];
		 $query="select * from tb_authorbkcard where fd_authorbkcard_key='$authorbkcardkey' and fd_authorbkcard_id <>'$listid'";
		$db->query($query);
		if($db->nf()){
			$error="银行该已存在!请查证！";
		}else{
	     $query = "update tb_authorbkcard set
		 
						fd_authorbkcard_bankid='$bankid',
						fd_authorbkcard_key='$authorbkcardkey',
						fd_authorbkcard_type='$authorbkcardtype',
						fd_authorbkcard_active='$authorbkcard_active',
						fd_authorbkcard_datetime='$datetime',
						fd_authorbkcard_isnew='$isnew',
						fd_authorbkcard_authorid='$authorid'

  		             where fd_authorbkcard_id ='$listid'";
	      $db->query($query);
		  //echo $query;exit;
	      Header("Location: $gotourl");	    
		}
		$action="";
		break;
	   
	   case "delete":
	   $query="delete from tb_authorbkcard where fd_authorbkcard_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	   default:
	   break;
} 


if(empty($listid)){
  $action = "new";
  
}else{
  $action = "edit"; 

	$query="select * from tb_authorbkcard 
			left join tb_saler on fd_saler_id = fd_authorbkcard_salerid
			left join tb_bank on  fd_bank_id = fd_authorbkcard_bankid
			left join tb_author on fd_authorbkcard_authorid=fd_author_id
			where fd_authorbkcard_id ='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$bankname=$db->f(fd_bank_name);
		$bankid=$db->f(fd_bank_id);
		$authorbkcardkey=$db->f(fd_authorbkcard_key);
		$authorbkcardtype=$db->f(fd_authorbkcard_type);
		$activedate=$db->f(fd_pcdactive_activedate);
		$active= $db->f(fd_authorbkcard_active);
		$datetime= $db->f(fd_authorbkcard_datetime);
        $isnew= $db->f(fd_authorbkcard_isnew);
		$authortruename=$db->f(fd_author_truename);
		$authorid=$db->f(fd_author_id);		
		$mobile= $db->f(fd_author_mobile);	
		
	}
}

    $arractiveid = array('0','1'); 
	$arractiveval = array('未激活','已激活'); 
	$selactive = makeselect($arractiveval,$active,$arractiveid);
	
	$arraisnew = array('0','1'); 
	$arraisnewval = array('否','是'); 
	$selisnew = makeselect($arraisnewval,$isnew,$arraisnew);
	



$t->set_var("listid"              ,  $listid           );
$t->set_var("authorbkcardkey"              ,  $authorbkcardkey           );
$t->set_var("authorbkcardtype"     , $authorbkcardtype     );

$t->set_var("authortruename"     , $authortruename     );
$t->set_var("authorid"     , $authorid    );
$t->set_var("mobile"     , $mobile     );
$t->set_var("bankname"     , $bankname     );
$t->set_var("bankid"     , $bankid     );
$t->set_var("salertruename"     , $salertruename     );
$t->set_var("datetime"     , $datetime     );

$t->set_var("selactive"     , $selactive     );
$t->set_var("selisnew"     , $selisnew     );
$t->set_var("error"     , $error     );
$t->set_var("action"     , $action     );
$t->set_var("gotourl"    , $gotourl    );  // 转用的地址


// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "authorbkcard");    # 最后输出页面


?>

