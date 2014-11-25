<?
$thismenucode = "7n001";
require ("../include/common.inc.php");
//require ("../FCKeditor/fckeditor.php");
$db = new DB_test;

$gourl = "tb_help_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch($action){
	 case "new":
	  // $allcontent = $_POST[FCKeditor1];
	   $query="INSERT INTO web_help(
	           fd_help_name,
	           fd_help_no,
	           fd_help_contect,
	           fd_help_type,
	           fd_help_state,
	           fd_help_date) VALUES(
	           '$name','$no','$content','$type','$isuse','$date')";
	   $db->query($query);
	   $id = $db->insert_id(); 
	   Header("Location: $gotourl");	
	   break;
	 case "edit":
	   $allcontent = $_POST[FCKeditor1];
	  /* $query="update web_help set fd_help_name='$name'  , 
	           fd_help_no='$no' ,
	           fd_help_contect='$allcontent' ,
	           fd_help_type='$type'    ,
	           fd_help_state='$isuse', fd_help_date='$date' where fd_help_id='$id'";*/
		$query="update web_help set fd_help_name='$name'  , 
	           fd_help_no='$no' ,
	           fd_help_contect='$content' ,
	           fd_help_type='$type'    ,
	           fd_help_state='$isuse', fd_help_date='$date' where fd_help_id='$id'";
	   $db->query($query);

	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");	
	   break;
	 case "delete":
	   $query="delete from web_help where fd_help_id='$id'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("help","help.html"); 

if(!isset($id)){
	$action="new";
	}
else{
	$action="edit";
	$query="select * from web_help where fd_help_id='$id' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$name    =$db->f(fd_help_name);
		$no      =$db->f(fd_help_no);
		$content =$db->f(fd_help_contect);
		$type    =$db->f(fd_help_type);
		$state   =$db->f(fd_help_state);
		$datetime=$db->f(fd_help_date);
		}
	
}

/*$oFCKeditor = new FCKeditor('FCKeditor1')  ; 
$oFCKeditor->BasePath = '../FCKeditor/' ;    
$oFCKeditor->ToolbarSet = 'Normal' ;  
$oFCKeditor->Width = '568' ; 
$oFCKeditor->Height = '440' ; 
$oFCKeditor->Value      = $content;    
$fckeditor = $oFCKeditor->CreateHtml();	*/

$query = "select fd_helpset_id,fd_helpset_name from web_helpset" ;
$db->query($query);
while($db->next_record()){		   
		   $arr_deptid[]   = $db->f(fd_helpset_id); 
		   $arr_dept[]     = $db->f(fd_helpset_name);    
}
$type = makeselect($arr_dept,$type,$arr_deptid);




if($state=="1"){
	$isuse="checked";
	$isout="";
}
else if($state=="2"){
  $isuse="";
  $isout="checked";
}else{
  $isuse="checked";
  $isout="";
}

$t->set_var("id"           , $id           );           //id
$t->set_var("no"           , $no           );           
$t->set_var("name"         , $name         );
$t->set_var("type"         , $type         );
$t->set_var("date"         , $date        );
$t->set_var("isuse"        , $isuse        );
$t->set_var("isout"        , $isout        );
$t->set_var("datetime"         , $datetime         );      

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );  

$t->set_var("content"      , $content      );           //内容 
//$t->set_var("allcontent"   , $allcontent   );           //内容   
//$t->set_var("fckeditor"    , $fckeditor    );
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "help");    # 最后输出页面



?>

