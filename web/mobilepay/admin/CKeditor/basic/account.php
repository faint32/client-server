<?
$thismenucode = "1c614";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
$db = new DB_test;

$gourl = "tb_account_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $query="INSERT INTO web_account(
	           fd_account_linkman,
	           fd_account_bank,
	           fd_account_bankno,
	           fd_account_sdcrid,
	           fd_account_zzsfp ,
	           fd_account_ptfp
	           ) VALUES(
	           '$name','$bank','$bankno','$sdcrid','$zzsfp','$ptfp')";
	   $db->query($query);
	   $id = $db->insert_id(); 
	   Header("Location: $gotourl");	
	   break;
	 case "edit":
	   $query="update web_account set fd_account_linkman='$name'  , 
	           fd_account_bank ='$bank' ,fd_account_bankno ='$bankno' ,
	           fd_account_sdcrid='$sdcrid'  ,fd_account_zzsfp ='$zzsfp' ,
	           fd_account_ptfp = '$ptfp'
	            where fd_account_id='$id'";
	   $db->query($query);

	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");	
	   break;
	 case "delete":
	   $query="delete from web_account where fd_account_id='$id'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("account","account.html"); 

if(!isset($id)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from web_account where fd_account_id='$id' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$name    =$db->f(fd_account_linkman);
		$bank =$db->f(fd_account_bank);
		$bankno    =$db->f(fd_account_bankno);
		$sdcrid   =$db->f(fd_account_sdcrid);
		$zzsfp   =$db->f(fd_account_zzsfp);
		$ptfp   =$db->f(fd_account_ptfp);
		}
	
}


$arr_id = "";
$arr_name="";
$arr_id[] ="";
$arr_name[] = "请选择";
$query = "select fd_sdcr_id,fd_sdcr_name from tb_sendcenter where fd_sdcr_isstop=0 and fd_sdcr_issdcr =0" ;
$db->query($query);
while($db->next_record()){		   
		   $arr_id[]   = $db->f(fd_sdcr_id); 
		   $arr_name[]     = $db->f(fd_sdcr_name);    
}
$sdcrid = makeselect($arr_name,$sdcrid,$arr_id);

$arr_id = "";
$arr_name="";
$arr_id[] ="";
$arr_name[] = "请选择";
$query = "select fd_fptype_id,fd_fptype_name from web_fptype" ;
$db->query($query);
while($db->next_record()){		   
		   $arr_id[]   = $db->f(fd_fptype_id); 
		   $arr_name[]     = $db->f(fd_fptype_name);    
}
$zzsfp = makeselect($arr_name,$zzsfp,$arr_id);
$ptfp = makeselect($arr_name,$ptfp,$arr_id);




$t->set_var("id"           , $id           );           //id
$t->set_var("name"         , $name         );
$t->set_var("bank"         , $bank         );
$t->set_var("bankno"        , $bankno        );
$t->set_var("sdcrid"        , $sdcrid        );
$t->set_var("zzsfp"         , $zzsfp         );      
$t->set_var("ptfp"        , $ptfp        );      


$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );  
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "account");    # 最后输出页面



?>

