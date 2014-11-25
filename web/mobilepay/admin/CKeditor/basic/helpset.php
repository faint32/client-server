<?
$thismenucode = "1c602";     
require("../include/common.inc.php");


$db=new db_test;
$gourl = "tb_helpset_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");

switch ($toaction){  
	case "new":    //新增记录
	     $query="select * from web_helpset where fd_helpset_name='$name'";
	     $db->query($query);
	     if ($db->nf()>0){
	      	$error = "此类型已经存在，请重新输入！";
	     }else{
	      	$query="insert into web_helpset (
	     	        fd_helpset_name   
	     	        )values(
	     	        '$name'   
	     	        )";
	       	$db->query($query);
	        if($loginbacktype==1){  //判断跳转页面
	     	   	 Header("Location: helpset.php");
	     	   }else{
	     	     Header("Location: $gotourl");
	         }
	     }
	     break;
	      
	case "delete":   //删除记录
	     $query = "select * from web_help where fd_help_type = '$id'";
	     $db->query($query);
	     if($db->nf()){
	      $error = "此类型已绑定，不能删除";
	    }
	    
	    if(empty($error)){
	     $query="delete  from web_helpset where fd_helpset_id='$id'";
	     $db->query($query);
	     require("../include/alledit.2.php");
	     Header("Location: $gotourl");
	   }
	     break;
	case "edit":     //编辑记录
	      $query="select * from web_helpset where fd_helpset_id<>'$id' 
	              and fd_helpset_name='$name'";
	      $db->query($query);
	      if($db->nf()>0){
	      	  $error = "此类型已经存在,请从新输入";
	      }else{
	      	  $query="update web_helpset set 
	      	          fd_helpset_name   = '$name'  
	                  where fd_helpset_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
	default:
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("template","helpset.html");

if(empty($id)){
	  $toaction = "new";
}else{ // 编辑
    $query = "select * from web_helpset where fd_helpset_id ='$id'" ;
    $db->query($query);
    if($db->nf()){                                       //判断查询到的记录是否为空
	      $db->next_record();                              //读取记录数据 
	      $id         = $db->f(fd_helpset_id);                         
       	$name       = $db->f(fd_helpset_name);                    
       	$toaction = "edit";                   
     }  	
}



$t->set_var("id",$id); 
$t->set_var("name",$name);

$t->set_var("toaction",$toaction);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "template"); //最后输出界面

?>