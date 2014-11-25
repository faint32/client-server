<?
$thismenucode = "2k311";
require ("../include/common.inc.php");


$db = new DB_test;
$gourl = "tb_authorindustry_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	

	   $query="INSERT INTO tb_authorindustry(
			   fd_auindustry_name,fd_auindustry_no,fd_auindustry_memo) VALUES(
			   '$authorindustry_name','$authorindustry_no','$authorindustry_memo')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
   
	   break;
	 case "edit":
	   $query="update tb_authorindustry set fd_auindustry_name='$authorindustry_name',fd_auindustry_no='$authorindustry_no',fd_auindustry_memo='$authorindustry_memo' where fd_auindustry_id='$listid'";
	   $db->query($query);
	   //echo $query;
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
	   break;
	 case "delete":
	   $query="delete from tb_authorindustry where fd_auindustry_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","authorindustry.html"); 

if(!isset($listid)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_authorindustry where fd_auindustry_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$listid = $db->f(fd_auindustry_id);
		$authorindustry_name =$db->f(fd_auindustry_name);	
		$authorindustry_no =$db->f(fd_auindustry_no);	
		$authorindustry_memo =$db->f(fd_auindustry_memo);		
	}
		
	
}

$t->set_var("id"           , $id           );           //listid
$t->set_var("listid"     , $listid           );           //listid
$t->set_var("authorindustry_name"         , $authorindustry_name         );
$t->set_var("authorindustry_no"         , $authorindustry_no         );
$t->set_var("authorindustry_memo"         , $authorindustry_memo         );


$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址    
$t->set_var("fckeditor"    , $fckeditor    );          
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

?>

