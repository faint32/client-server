<?

$thismenucode = "7n003";     
require("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");

$db=new db_test;

$gourl = "tb_apprules_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch ($action){  
	case "new":    //新增记录
		 $allcontent = $_POST[FCKeditor1];
		$query="insert into tb_apprules (
							fd_apprules_no    ,  fd_apprules_title    ,		fd_apprules_content,
							fd_apprules_type)values(
							'$no'            ,  '$title'   	,    '$allcontent' ,'$type'
							)"; 
		$db->query($query);
	      Header("Location: $gotourl");
	     break;
	      
	case "delete":   //删除记录
	      $query="delete  from tb_apprules where fd_apprules_id='$id'";
	      $db->query($query);
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
	      
	     break;
	case "edit":     //编辑记录
			$allcontent = $_POST[FCKeditor1];
		  $query="update tb_apprules set 
						 fd_apprules_no='$no'                    ,  fd_apprules_content='$allcontent'		, fd_apprules_title='$title'	,fd_apprules_type='$type'
						 where fd_apprules_id ='$id'	";
		  $db->query($query);
	      require("../include/alledit.2.php");
	      Header("Location: $gotourl");
 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("apprules","apprules.html");

if(empty($id)){
	
	  $checked1 = "checked";
	
	  $action = "new";
}else{ // 编辑
    $query = "select * from tb_apprules
               where fd_apprules_id ='$id'
             " ;
    $db->query($query);
    if($db->nf()){                                            //判断查询到的记录是否为空
	      $db->next_record();                                   //读取记录数据 
       	$no            = $db->f(fd_apprules_no);               //编号  
       	$title          = $db->f(fd_apprules_title);
		$content		   = $db->f(fd_apprules_content);
		$type          = $db->f(fd_apprules_type);
		
		
       	$action = "edit";                   
     }  	
}

$oFCKeditor = new FCKeditor('FCKeditor1')  ; 
$oFCKeditor->BasePath = '../FCKeditor/' ;    
$oFCKeditor->ToolbarSet = 'Normal' ;  
$oFCKeditor->Width = '568' ; 
$oFCKeditor->Height = '440' ; 
$oFCKeditor->Value      = $content;    
$fckeditor = $oFCKeditor->CreateHtml();	




$t->set_var("no"         ,$no         );
$t->set_var("title"       ,$title       );
$t->set_var("content"      , $content      );           //内容 
$t->set_var("allcontent"   , $allcontent   );           //内容       
$t->set_var("fckeditor"    , $fckeditor    );
$t->set_var("type"    , $type    );


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "apprules"); //最后输出界面

?>