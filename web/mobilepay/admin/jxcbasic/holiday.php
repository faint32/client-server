<?
$thismenucode = "2k115";
require ("../include/common.inc.php");
require ("../FCKeditor/fckeditor.php");
require ("../function/AutogetFileimg.php");
require ("../third_api/readshopname.php");

$getFileimg = new AutogetFile;
$db = new DB_test;
if($type=="check")
{
$gourl = "monthtable_view.php" ;	
}else{
$gourl = "tb_holiday_b.php" ;
}

$gotourl = $gourl.$tempurl ;
require("../include/alledit.1.php");
switch($action){
	 case "new":
	 $query = "select * from tb_holiday where fd_holiday_name = '$holiday_name' and  fd_holiday_date='$holiday_date' and fd_holiday_year='$holiday_year' ";
	   $db->query($query);
	   if($db->nf()){
		  $error = "该节日已存在，请重新查证！"; 
		}else{
		   $query="insert into tb_holiday
				(fd_holiday_name,fd_holiday_year,fd_holiday_date,fd_holiday_active,fd_holiday_staid)
					values 
				('$holiday_name','$holiday_year','$holiday_date','$holiday_active','$loginstaid')";
		   $db->query($query);
			   require("../include/alledit.2.php");
		   Header("Location: $gotourl");
		}
	 break;
	 case "edit":
	   $query = "select * from tb_holiday where fd_holiday_name = '$holiday_name' and  fd_holiday_date='$holiday_date' and fd_holiday_year='$holiday_year' and fd_holiday_id <> '$listid'";
	   $db->query($query);
	   if($db->nf()){
		  $error = "该节日已存在，请重新查证！"; 
		}else{
	  
	   $query="update tb_holiday set 
				fd_holiday_name='$holiday_name'  , 
	           fd_holiday_year='$holiday_year' ,
	           fd_holiday_date='$holiday_date' ,
			   fd_holiday_active='$holiday_active'
			   where fd_holiday_id='$listid'";
	   $db->query($query);
	  	   require("../include/alledit.2.php");
	   Header("Location: $gotourl");
		}
		$action="";
	   break;
	 case "delete":
	   $query="delete from tb_holiday where fd_holiday_id='$listid'";
	   $db->query($query);
	   require("../include/alledit.2.php");

	   Header("Location: $gotourl");	
	   break;
	 default:
	   break;
}
	
$t = new Template(".", "keep");          //调用一个模版
$t->set_file("holiday","holiday.html"); 

if(empty($listid)){

	$action="new";
	}
else{
	$action="edit";
	$query="select * from tb_holiday
	 		where fd_holiday_id='$listid' ";
	$db->query($query);
	if($db->nf()){
		$db->next_record();
		$holiday_name    =$db->f(fd_holiday_name);
		$holiday_year    =$db->f(fd_holiday_year);
		$holiday_date    =$db->f(fd_holiday_date);
		$holiday_active  =$db->f(fd_holiday_active);
		
		}
	
}

$arr_id=array(0,1);
$arr_name=array("取消假期","正常");

$holiday_active = makeselect($arr_name, $holiday_active, $arr_id);
$t->set_var("holiday_name"       , $holiday_name       );   
$t->set_var("holiday_year"       , $holiday_year       );   
$t->set_var("holiday_date"       , $holiday_date       );   
$t->set_var("holiday_active"       , $holiday_active       );   
  
$t->set_var("listid"       , $listid       ); 
$t->set_var("action"       , $action       );                             
$t->set_var("gotourl"      , $gotourl      );  // 转用的地址              
$t->set_var("error"        , $error        );        
$t->set_var("fckeditor"    , $fckeditor    );
// 判断权限 
include("../include/checkqx.inc.php");
$t->set_var("skin",$loginskin);

$t->pparse("out", "holiday");    # 最后输出页面

?>

