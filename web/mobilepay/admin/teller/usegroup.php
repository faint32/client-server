<?
$thismenucode = "9102";
require ("../include/common.inc.php");

$db = new DB_test;
$gourl= "tb_usegroup_b.php" ;
$gotourl = $gourl.$tempurl ;

switch ($action){
    case "delete":   // 记录删除
       $query = "delete from web_usegroup  where fd_usegroup_id = $id ";
       $db->query($query);
       Header("Location: $gotourl");       
	     break;
    case "new":   // 进行编辑，要提供数据
       $query = "select * from web_usegroup where fd_usegroup_name = '$name' ";
	     $db->query($query);
	     if($db->nf()>0){
	         $error = "此组已经存在，请重新输入！";
	     }else{
           $query="INSERT INTO web_usegroup (
 		               fd_usegroup_name   , fd_usegroup_memo      
  		             )VALUES (
  		             '$name' ,'$memo'      )";
		       $db->query($query);
	         Header("Location: $gotourl");	
	     }
	     break;
    case "edit":   // 修改记录
       $query = " update web_usegroup set
 		            fd_usegroup_name ='$name' ,fd_usegroup_memo ='$memo'              
  		          where fd_usegroup_id = $id ";
	     $db->query($query);
	     Header("Location: $gotourl");	     
    	break;
    default:
        break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("usegroup","usegroup.ihtml"); 


if (!isset($id)){		// 新增
   $action = "new";
   
}else{			// 编辑
   $query = "select * from web_usegroup where fd_usegroup_id = $id " ;
   $db->query($query);
	 if($db->nf()){                                //判断查询到的记录是否为空
	     $db->next_record();                           //读取记录数据 
	     $id     = $db->f(fd_usegroup_id);           //ID                     
       $name   = $db->f(fd_usegroup_name);         //名称      
       $memo   = $db->f(fd_usegroup_memo);         //备注
       $action = "edit";                   
       	   	      	                     
    }
}  

       	   
$t->set_var("id"  ,$id  );
$t->set_var("name",$name);
$t->set_var("memo",$memo);

$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);  // 转用的地址
$t->set_var("error",$error);
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "usegroup");    # 最后输出页面

?>