<?
$thismenucode = "2k404";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");


$db = new DB_test;
$gourl = "tb_paycardaccount_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $paycardaccount_company = $_POST['paycardaccount_company'];
	   $paycardaccount_accountname = $_POST['paycardaccount_accountname'];
	   $paycardaccount_accountnum = $_POST['paycardaccount_accountnum'];
	   $paycardaccount_bank = $_POST['paycardaccount_bank'];
       $query="select * from tb_paycardaccount where fd_paycardaccount_accountnum = '$paycardaccount_accountnum'";
	   $db->query($query);
	   if($db->nf()){
		   $error="该账户已存在!请查证！";
		}else{
	   $query="INSERT INTO tb_paycardaccount(
			   fd_paycardaccount_company,fd_paycardaccount_accountname,fd_paycardaccount_accountnum,fd_paycardaccount_bank) VALUES(
			   '$paycardaccount_company','$paycardaccount_accountname','$paycardaccount_accountnum','$paycardaccount_bank')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "edit":
	   $paycardaccount_company = $_POST['paycardaccount_company'];
	   $paycardaccount_accountname = $_POST['paycardaccount_accountname'];
	   $paycardaccount_accountnum = $_POST['paycardaccount_accountnum'];
	   $paycardaccount_bank = $_POST['paycardaccount_bank'];
	   
       $query="select * from tb_paycardaccount where fd_paycardaccount_accountnum = '$paycardaccount_accountnum' and fd_paycardaccount_id<>'$listid'";
	   $db->query($query);
	   if($db->nf()){
		   $error="该账户已存在!请查证！";
		}else{
       $query="update tb_paycardaccount set fd_paycardaccount_company='$paycardaccount_company',fd_paycardaccount_accountname='$paycardaccount_accountname',
	   fd_paycardaccount_accountnum='$paycardaccount_accountnum',fd_paycardaccount_bank='$paycardaccount_bank' 
	   where fd_paycardaccount_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "delete":
	   $query="delete from tb_paycardaccount where fd_paycardaccount_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","paycardaccount.html"); 

if(empty($listid)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_paycardaccount where fd_paycardaccount_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$paycardaccount_id = $db->f(fd_paycardaccount_id);
		$paycardaccount_company =$db->f(fd_paycardaccount_company);	
		$paycardaccount_accountname =$db->f(fd_paycardaccount_accountname);	
		$paycardaccount_accountnum =$db->f(fd_paycardaccount_accountnum);
		$paycardaccount_bank =$db->f(fd_paycardaccount_bank);		
	}
		
	
}


$t->set_var("id"           , $id           );           //listid
$t->set_var("listid"           , $listid           );  
$t->set_var("paycardaccount_id"     , $paycardaccount_id           );           //listid
$t->set_var("paycardaccount_company"         , $paycardaccount_company         );
$t->set_var("paycardaccount_accountname"         , $paycardaccount_accountname         );
$t->set_var("paycardaccount_accountnum"         , $paycardaccount_accountnum         );
$t->set_var("paycardaccount_bank"         , $paycardaccount_bank         );
$t->set_var("selbank"         , $selbank         );


$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址             
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

?>

