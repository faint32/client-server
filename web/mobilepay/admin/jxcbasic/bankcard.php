<?
$thismenucode = "2k114";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");

$db = new DB_test;
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("bankcard","bankcard.html"); 
$gourl = "tb_bankcard_b.php";
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
 switch ($action)
{	
	case "new":
	   $bankid = $_POST['bankid'];
	   $bankcardkey = $_POST['bankcardkey'];
	   $bankcardtype = $_POST['bankcardtype'];
	   $bankcard_active = $_POST['bankcard_active'];
	   $isnew = $_POST['isnew'];
	   $authorid = $_POST['authorid'];
	   $datetime = $_POST['datetime'];
		$query="select * from tb_bankcard where fd_bankcard_key='$bankcardkey'";
		$db->query($query);
		if($db->nf()){
			$error="银行该已存在!不需要重复添加！";
		}else{
	   $query="INSERT INTO tb_bankcard(
			   fd_bankcard_key,fd_bankcard_type,fd_bankcard_authorid,fd_bankcard_bankid,
			   fd_bankcard_isnew,fd_bankcard_active,fd_bankcard_datetime) VALUES(
			   '$bankcardkey','$bankcardtype','$authorid','$bankid',
			   '$isnew','$bankcard_active','$datetime')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;

	case "edit":   // 修改记录	
		 $bankid = $_POST['bankid'];
	   	 $bankcardkey = $_POST['bankcardkey'];
	   	 $bankcardtype = $_POST['bankcardtype'];
	   	 $bankcard_active = $_POST['bankcard_active'];
	   	 $isnew = $_POST['isnew'];
	   	 $authorid = $_POST['authorid'];
	   	 $datetime = $_POST['datetime'];
		 $query="select * from tb_bankcard where fd_bankcard_key='$bankcardkey' and fd_bankcard_id <>'$listid'";
		$db->query($query);
		if($db->nf()){
			$error="银行该已存在!请查证！";
		}else{
	     $query = "update tb_bankcard set
		 
						fd_bankcard_bankid='$bankid',
						fd_bankcard_key='$bankcardkey',
						fd_bankcard_type='$bankcardtype',
						fd_bankcard_active='$bankcard_active',
						fd_bankcard_datetime='$datetime',
						fd_bankcard_isnew='$isnew',
						fd_bankcard_authorid='$authorid'

  		             where fd_bankcard_id ='$listid'";
	      $db->query($query);
		  //echo $query;exit;
	      Header("Location: $gotourl");	    
		}
		$action="";
		break;
	   
	   case "delete":
	   $query="delete from tb_bankcard where fd_bankcard_id='$listid'";
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

	$query="select * from tb_bankcard 
			left join tb_saler on fd_saler_id = fd_bankcard_salerid
			left join tb_bank on  fd_bank_id = fd_bankcard_bankid
			left join tb_author on fd_bankcard_authorid=fd_author_id
			where fd_bankcard_id ='$listid'";
	$db->query($query);
	if($db->nf())
	{
		$db->next_record();
		$bankname=$db->f(fd_bank_name);
		$bankid=$db->f(fd_bank_id);
		$bankcardkey=$db->f(fd_bankcard_key);
		$bankcardtype=$db->f(fd_bankcard_type);
		$activedate=$db->f(fd_pcdactive_activedate);
		$active= $db->f(fd_bankcard_active);
		$datetime= $db->f(fd_bankcard_datetime);
        $isnew= $db->f(fd_bankcard_isnew);
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
$t->set_var("bankcardkey"              ,  $bankcardkey           );
$t->set_var("bankcardtype"     , $bankcardtype     );

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

$t->pparse("out", "bankcard");    # 最后输出页面


?>

