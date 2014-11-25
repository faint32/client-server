<?
$thismenucode = "7n001";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
$db = new DB_test;

$gourl = "tb_author_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	   $phone = $_POST[FCKeditor1];
	   $query="INSERT INTO tb_author(
	           fd_author_username,
	           fd_author_truename,
	           fd_author_mobile,
	           fd_author_regtime,
	           fd_author_parcardbegintime,
	           fd_author_logcount) VALUES(
	           '$name','$truename','$phone','$zcdate','$ktdate','$usagelog')";
	   $db->query($query);
	   $id = $db->insert_id(); 
	   Header("Location: $gotourl");	
	   break;
	 case "edit":
	   $phone = $_POST[FCKeditor1];
	   $query="update tb_author set fd_author_username='$name'  , 
	           fd_author_truename='$truename' ,
	           fd_author_mobile='$phone' ,
	           fd_author_regtime='$zcdate'    ,
	           fd_author_parcardbegintime='$ktdate', fd_author_logcount='$usagelog' where fd_author_id='$id'";
	   $db->query($query);

	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");	
	   break;
	 case "delete":
	   $query="delete from tb_author where fd_author_id='$id'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("salersp","salersp.html"); 

if(!isset($id)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_author where fd_author_id='$id' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$name    =$db->f(fd_help_name);
		$no      =$db->f(fd_author_truename);
		$content =$db->f(fd_author_mobile);
		$zcdate    =$db->f(fd_author_regtime);
		$state   =$db->f(fd_author_parcardbegintime);
		$usagelog   =$db->f(fd_author_logcount);
		}
	
}

$oFCKeditor = new FCKeditor('FCKeditor1')  ; 
$oFCKeditor->BasePath = '../FCKeditor/' ;    
$oFCKeditor->ToolbarSet = 'Normal' ;  
$oFCKeditor->Width = '568' ; 
$oFCKeditor->Height = '440' ; 
$oFCKeditor->Value      = $content;    
$fckeditor = $oFCKeditor->CreateHtml();	

$query = "select fd_helpset_id,fd_helpset_name from tb_authorset" ;
$db->query($query);
while($db->next_record()){		   
		   $arr_deptid[]   = $db->f(fd_helpset_id); 
		   $arr_dept[]     = $db->f(fd_helpset_name);    
}
$zcdate = makeselect($arr_dept,$zcdate,$arr_deptid);




if($state=="1"){
	$ktdate="checked";
	$isout="";
}
else if($state=="2"){
  $ktdate="";
  $isout="checked";
}else{
  $ktdate="checked";
  $isout="";
}



$t->set_var("id"           , $id           );           //id
$t->set_var("truename"     , $truename           );           //id
$t->set_var("name"         , $name         );
$t->set_var("zcdate"         , $zcdate         );
$t->set_var("usagelog"         , $usagelog        );
$t->set_var("ktdate"        , $ktdate        );

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );  

$t->set_var("content"      , $content      );           //内容 
$t->set_var("allcontent"   , $phone   );           //内容       
$t->set_var("fckeditor"    , $fckeditor    );
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "salersp");    # 最后输出页面



?>

