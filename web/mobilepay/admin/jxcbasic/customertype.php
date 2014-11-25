<?
$thismenucode = "2k105";     
require("../include/common.inc.php");
$db=new db_test;
$gourl = "tb_customertype_b.php" ;
$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch ($action){  
	case "new":    //新增记录
	     $query="select * from tb_customertype where  fd_customertype_name='$cusname' ";
	     $db->query($query);
	     if ($db->nf()){
	     	$error = "此代理商已经存在，请重新输入！";
	     }else{
	     	$query="insert into tb_customertype (
	     			   fd_customertype_no, fd_customertype_name  
	     			    )values(     
	     		      '$cusno','$cusname'    
	     		      )";
	     	$db->query($query);
	      	  require("../include/alledit.2.php");
	     	  Header("Location: $gotourl");
	     }
	     break;
	      
	case "delete":   //删除记录
	          $query="delete  from tb_customertype where fd_customertype_id='$id'";
	          $db->query($query);
	          require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	     break;
	case "edit":     //编辑记录
	      $query="select * from tb_customertype where fd_customertype_name='$cusname'
	                       and fd_customertype_id<>'$id' ";
	      $db->query($query);
	      if($db->nf()){
	      	  $error = "这个代理商类型已经存在,请从新输入";
	      }else{
	      	  $query="update tb_customertype set 
					  fd_customertype_no = '$cusno',
					  fd_customertype_name='$cusname'  
	                  where fd_customertype_id ='$id' ";
	      	  $db->query($query);
	      	  require("../include/alledit.2.php");
	          Header("Location: $gotourl");
	      } 
	   
	      break;
}
      	   

$t = new Template(".","keep");
$t->set_file("customertype","customertype.html");

if(empty($id)){
	  $action = "new";
}else{ // 编辑
    $query = "select * from tb_customertype where fd_customertype_id ='$id'" ;
    $db->query($query);
    if($db->nf()){                                           //判断查询到的记录是否为空
	   $db->next_record();                                  //读取记录数据 
	    $id          = $db->f(fd_customertype_id);               //id      
        $cusno          = $db->f(fd_customertype_no);               //id
       	$cusname     = $db->f(fd_customertype_name);          //类别名              
       	$action = "edit";                   
     }  	
}



$t->set_var("id",$id); 
$t->set_var("cusname",$cusname);
$t->set_var("cusno",$cusno); 


$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);       // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php") ;
$t->set_var("skin",$loginskin);
$t->pparse("out", "customertype"); //最后输出界面

?>