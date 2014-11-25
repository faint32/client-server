<?
$thismenucode = "2k305";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");


$db = new DB_test;
$gourl = "tb_authortype_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $authortype_name = $_POST['authortype_name'];
	   $authortype_no = $_POST['authortype_no'];
	   $authortype_remark = $_POST['authortype_remark'];
	   $appmnuid= implode(",", $arr_appmnuid);
		$query="select * from tb_authortype where fd_authortype_name='$authortype_name' or fd_authortype_no='$authortype_no'";
		$db->query($query);
		if($db->nf()){
			$error = "该用户类型已经存在！不需要重复添加！";	
		}else{
	   $query="INSERT INTO tb_authortype(
			   fd_authortype_name,fd_authortype_no,fd_authortype_remark,fd_authortype_appmnuid) VALUES(
			   '$authortype_name','$authortype_no','$authortype_remark','$appmnuid')";
	   $db->query($query);
	   $listid = $db->insert_id(); 
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
   		$action="";
	   break;
	 case "edit":
	   $appmnuid= implode(",", $arr_appmnuid);
	   $authortype_name = $_POST['authortype_name'];
	   $authortype_no = $_POST['authortype_no'];
	   $authortype_remark = $_POST['authortype_remark'];
	   $query = "select * from tb_authortype where (fd_authortype_name='$authortype_name' or fd_authortype_no='$authortype_no') and fd_authortype_id<>'$listid'";
	   $db->query($query);
	   if($db->nf()){
		   $error = "该用户类型已经存在！请查证！";
		}else{
	   $query="update tb_authortype set fd_authortype_name='$authortype_name',fd_authortype_no='$authortype_no',fd_authortype_remark='$authortype_remark',fd_authortype_appmnuid='$appmnuid' where fd_authortype_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "delete":
	   $query="delete from tb_authortype where fd_authortype_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	 	$action="";
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("author","authortype.html"); 

if(empty($listid)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_authortype where fd_authortype_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$appmnuid = $db->f(fd_authortype_appmnuid );
		$authortype_id = $db->f(fd_authortype_id);
		$authortype_name =$db->f(fd_authortype_name);	
		$authortype_no =$db->f(fd_authortype_no);	
		$authortype_remark =$db->f(fd_authortype_remark);		
	}
		
	
}
$arr_app=explode(',', $appmnuid);

$checked="";
$showcheck="";
$query = "select * from tb_appmenu where fd_appmenu_active='1' order by fd_appmnu_id " ;
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$tmpappmnuid=$db->f(fd_appmnu_id);
		$appname=$db->f(fd_appmnu_name);
		$checked= "";
		foreach($arr_app as $value)
		{
		if($tmpappmnuid==$value)
		{
			$checked= "checked";
		}
		}
		$showcheck.='<input type="checkbox"  name="arr_appmnuid[]" value="'.$tmpappmnuid.'" '.$checked.'>'.$appname;
	
	}
}

$t->set_var("showcheck"  , $showcheck     );

$appmnuid = makeselect($arr_appmnu,$appmnuid,$arr_appmnuid); 
$t->set_var ( "appmnuid", $appmnuid );
$t->set_var("id"           , $id           );    
$t->set_var("listid"           , $listid           );        //listid
$t->set_var("authortype_id"     , $authortype_id           );        
$t->set_var("authortype_name"         , $authortype_name         );
$t->set_var("authortype_no"         , $authortype_no         );
$t->set_var("authortype_remark"         , $authortype_remark         );


$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址    
$t->set_var("fckeditor"    , $fckeditor    );          
$t->set_var("error"        , $error        );        
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "author");    # 最后输出页面

?>

