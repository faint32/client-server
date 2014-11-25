<?
$thismenucode = "2k109";
require ("../include/common.inc.php");
$db = new DB_test;
$db1 = new DB_test;

//if($dimission != 2 && $type != 3){
// $gourl = "tb_staffer_b.php" ;
  //$dimissiondate = "";
//}
//if($dimission == 2){
	$gourl  = "tb_dimission_b.php" ;
	$gourl2 = "tb_staffer_b.php" ;
//}

//if($type == 3){
	//$gourl = "tb_retire_b.php" ;
//}

$gotourl = $gourl.$tempurl ;
$gotourl2 = $gourl2.$tempurl ;

require("../include/alledit.1.php");


switch ($action)
{
  case "delete":   // 记录删除
     $query="delete from tb_staffer where fd_sta_id='$id'";
     $db->query($query);
     Header("Location: $gotourl");       
	break;
  
  case "edit":   // 修改记录      
         $query = "update tb_staffer set
 		               fd_sta_dimissiondate  = '$dimissiondate'  ,  fd_sta_dimimemo   =  '$dimimemo'   ,  fd_sta_dimission  = '2'   
  		             where fd_sta_id = '$id' ";
	       $db->query($query);
	       
	    
	      
	      Header("Location: $gotourl");	  

   break;
   default:
   break;
}

$t = new Template(".", "keep");          //调用一个模版
$t->set_file("dimission_set","dimission_set.html"); 

		// 编辑
$query = "select * from tb_staffer where fd_sta_id = '$staid' " ;
$db->query($query);
if($db->nf())
{                                //判断查询到的记录是否为空
    $db->next_record();                                 //读取记录数据 
    $id              = $db->f(fd_sta_id);                  //id号 
  
 	  $dimissiondate   = $db->f(fd_sta_dimissiondate);       //离职日期
 	  $dimimemo        = $db->f(fd_sta_dimimemo);            //离职原因   	  
    $staffname       = $db->f(fd_sta_name);                //员工姓名
    $staffno         = $db->f(fd_sta_stano);               //员工编号
        
   
              
    $action = "edit";      	                     
}
 
if($dimissiondate=="0000-00-00")
{
	$dimissiondate=date("Y-m-d");
}
                  
$t->set_var("id"              ,  $staid           );
$t->set_var("staffname"       ,  $staffname       );
$t->set_var("staffno"         ,  $staffno         );
$t->set_var("dimimemo"        ,  $dimimemo        );
$t->set_var("dimission"       ,  $dimission       );
$t->set_var("dyear"           ,  $dyear           );
$t->set_var("dmonth"          ,  $dmonth          );
$t->set_var("dimissiondate"   ,  $dimissiondate            );
$t->set_var("display1"        ,  $display1        );   
$t->set_var("display2"        ,  $display2        );   

$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址  
$t->set_var("gotourl2"      , $gotourl2      );  // 转用的地址                  
$t->set_var("error"        , $error        );        

// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "dimission_set");    # 最后输出页面


?>

