<?
$thismenucode = "2k308";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");


$db = new DB_test;
$gourl = "tb_authorlevel_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $authorlevel_name = $_POST['authorlevel_name'];
	   $authorlevel_no = $_POST['authorlevel_no'];
	   $authorlevel_remark = $_POST['authorlevel_remark'];
		$query="select  * from tb_authorlevel where fd_authorlevel_no='$authorlevel_no' or fd_authorlevel_name='$authorlevel_name'";
		$db->query($query);
		if($db->nf()){
			$error = "该信息已经存在，不需要重复添加";
			}else{
	   $query="INSERT INTO tb_authorlevel(
			   fd_authorlevel_name,fd_authorlevel_no,fd_authorlevel_remark) VALUES(
			   '$authorlevel_name','$authorlevel_no','$authorlevel_remark')";
			   echo $query;
	   $db->query($query);
	  	$listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
   		}
		$action="";
	   break;
	 case "edit":
	
	   $authorlevel_name = $_POST['authorlevel_name'];
	   $authorlevel_no = $_POST['authorlevel_no'];
	   $authorlevel_remark = $_POST['authorlevel_remark'];
	   $query="select * from tb_authorlevel where (fd_authorlevel_name='$authorlevel_name' or fd_authorlevel_no='$authorlevel_no') and fd_authorlevel_id<>'$listid'";
	   $db->query($query);
	   if($db->nf()){
			$error = "该信息已经存在！请查证！";   
		}else{
	   $query="update tb_authorlevel set fd_authorlevel_name='$authorlevel_name',fd_authorlevel_no='$authorlevel_no',fd_authorlevel_remark='$authorlevel_remark' where fd_authorlevel_id='$listid'";
	   $db->query($query);
	   
	   require("../include/alledit.2.php");
	  Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "delete":
	   $query="delete from tb_authorlevel where fd_authorlevel_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	 	$action = "";
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","authorlevel.html"); 

if(empty($listid)){
	$action="new";
	}
else{
	
	$action="edit";
	$query="select * from tb_authorlevel where fd_authorlevel_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$authorlevel_id = $db->f(fd_authorlevel_id);
		$authorlevel_name =$db->f(fd_authorlevel_name);	
		$authorlevel_no =$db->f(fd_authorlevel_no);	
		$authorlevel_remark =$db->f(fd_authorlevel_remark);		
	}
		
	
}

$t->set_var("id"           , $id           );           //listid
$t->set_var("listid"     , $listid           );           //listid
$t->set_var("authorlevel_name"         , $authorlevel_name         );
$t->set_var("authorlevel_no"         , $authorlevel_no         );
$t->set_var("authorlevel_remark"         , $authorlevel_remark         );


$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址    
$t->set_var("fckeditor"    , $fckeditor    );          
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

?>

