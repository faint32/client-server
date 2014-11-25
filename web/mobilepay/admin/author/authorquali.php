<?
$thismenucode = "2k306";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");


$db = new DB_test;
$gourl = "tb_authorquali_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $authorquali_name = $_POST['authorquali_name'];
	   $authorquali_no = $_POST['authorquali_no'];
	   $authorquali_remark = $_POST['authorquali_remark'];
		$query="select * from tb_authorquali where fd_auq_name='$authorquali_name' or fd_auq_no='$authorquali_no'";
		$db->query($query);
		if($db->nf()){
			$error = "商户资质已经存在！不需要重复添加！";
		}else{
	   $query="INSERT INTO tb_authorquali(
			   fd_auq_name,fd_auq_no,fd_auq_remark) VALUES(
			   '$authorquali_name','$authorquali_no','$authorquali_remark')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
   		$action="";
	   break;
	 case "edit":
	   $authorquali_name = $_POST['authorquali_name'];
	   $authorquali_no = $_POST['authorquali_no'];
	   $authorquali_remark = $_POST['authorquali_remark'];
	   $query="select * from tb_authorquali where (fd_auq_name='$authorquali_name' or fd_auq_no='$authorquali_no') and fd_auq_id<>'$listid'";
	   $db->query($query);
	   if($db->nf()){
		   $error = "商户资质已经存在！请查证！";
		 }else{
	   $query="update tb_authorquali set fd_auq_name='$authorquali_name',fd_auq_no='$authorquali_no',fd_auq_remark='$authorquali_remark' where fd_auq_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
	   }
	   $action="";
	   break;
	 case "delete":
	   $query="delete from tb_authorquali where fd_auq_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","authorquali.html"); 

if(empty($listid)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_authorquali where fd_auq_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$authorquali_id = $db->f(fd_auq_id);
		$authorquali_name =$db->f(fd_auq_name);	
		$authorquali_no =$db->f(fd_auq_no);	
		$authorquali_remark =$db->f(fd_auq_remark);		
	}
		
	
}

$t->set_var("id"           , $id           );  
$t->set_var("listid"           , $listid           );           //listid
$t->set_var("authorquali_id"     , $authorquali_id           );           //listid
$t->set_var("authorquali_name"         , $authorquali_name         );
$t->set_var("authorquali_no"         , $authorquali_no         );
$t->set_var("authorquali_remark"         , $authorquali_remark         );


$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址    
$t->set_var("fckeditor"    , $fckeditor    );          
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

?>

