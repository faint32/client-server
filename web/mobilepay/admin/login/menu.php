<?

//$thismenucode = "9101"; 
require ("../include/common.inc.php");
$db = new DB_test;
$gourl = "tb_menu_b.php" ;
$gotourl = $gourl.$tempurl ;



switch ($action){
    case "delete":   // 记录删除
       $query = "delete from tb_menu where fd_menu_id = '$id'";
       $db->query($query);
       Header("Location: $gotourl");       
	break;
    case "new":   // 进行编辑，要提供数据
       $query = "select * from tb_menu where fd_menu_name ='$name' ";
	     $db->query($query);
       if($db->nf()>0){
	         $error = "此菜单名已经存在，请重新输入！";
       }else{
           $query="INSERT INTO tb_menu(
 		               fd_menu_name      , fd_menu_code       , fd_menu_upcode ,
 		               fd_menu_jpg      , fd_menu_hz , fd_menu_url ,
 		               fd_menu_sno    , fd_menu_active   
 		               )values(
  		             '$name'     ,  '$code'     , '$upcode' , 
  		             '$jpg'     ,  '$hz' , '$url'  ,
  		             '$sno'   ,  '$active'     
  		             )";
	   $db->query($query);
	   Header("Location: $gotourl");	
	}
	break;
    case "edit":   // 修改记录
    	   $query = "update tb_menu set
 	    	          fd_menu_name = '$name'  , fd_menu_upcode ='$upcode'   ,
 	    	          fd_menu_jpg  ='$jpg'  , fd_menu_url = '$url'   ,
 	    	          fd_menu_sno='$sno' , fd_menu_active ='$chgpass' ,
 	    	          fd_menu_hz = '$hz'  ,fd_menu_code = '$code'
  	    	        where fd_menu_id='$id' ";
  	     $db->query($query);
	       Header("Location: $gotourl");
    	   break;   
    default:
        break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("menu","menu.html"); 


if (!isset($id)){		// 新增
   $sno = 0 ;
   $chgpass = 1 ;
   $checkterm = 1;
   $action = "new";
   
}else{			// 编辑
   $query = "select * from tb_menu 
             where fd_menu_id = '$id'";
   $db->query($query);
	if($db->nf()){                                //判断查询到的记录是否为空
	   $db->next_record();                           //读取记录数据 
       	   $name         = $db->f(fd_menu_name) ;            //用户名称 
       	   $code       = $db->f(fd_menu_code);           //菜单代码 
       	   $upcode     = $db->f(fd_menu_upcode) ;           //上级菜单代码
       	   $jpg         = $db->f(fd_menu_jpg) ;            //菜单图片路径      
       	   $sno       = $db->f(fd_menu_sno) ;          //菜单sno      	       	   
       	   $hz     = $db->f(fd_menu_hz);       //菜单hz               
       	   $active      = $db->f(fd_menu_active);          //菜单的激活状态
       	   $url        = $db->f(fd_menu_url);          //菜单的url
       	   $action = "edit";
       	}
}

 //生成chgpass选择
       	   $arr_active = array("是","否") ;
       	   $arr_valueactive = array("1","0") ;
       	   $active = makeselect($arr_active,$active,$arr_valueactive);
		           	   
$t->set_var("id",$id); 
$t->set_var("name",$name);
$t->set_var("code",$code);
$t->set_var("upcode",$upcode); 
$t->set_var("jpg",$jpg);
$t->set_var("sno",$sno);                      
$t->set_var("url",$url);                     
$t->set_var("hz",$hz);
$t->set_var("active",$active);
$t->set_var("action",$action);
$t->set_var("gotourl",$gotourl);  // 转用的地址

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);
$t->pparse("out", "menu");    # 最后输出页面



?>