<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type:text/html;charset=utf-8');           
require ("../include/common.inc.php");
error_reporting(0); 
$db  = new DB_test;	
		//print_r($db->execute($query));
//echo  "test".$interface_id."-------";
$interface_id = $_GET["interface_id"];
if($interface_id<1){$interface_id="1";}
$query = "select * from web_test_interface where fd_interface_id ='$interface_id'";
$db->query($query);
if($db->nf()){
		while($db->next_record()){
			//$interface_id  = $db->f(fd_interface_id);
			$interface_no  = $db->f(fd_interface_no);
			$interface_name  = $db->f(fd_interface_name);
			$interface_url  = $db->f(fd_interface_apinamefunc);
			$apiname  = g2u($db->f(fd_interface_demo));
		}
	}
$query = "select * from web_test_interface where fd_interface_active = 1 order by fd_interface_sortorder,fd_interface_no asc";         
$db->query($query);
if($db->nf()){
	while($db->next_record()){
		$arr_dept_easyname[]  = $db->f(fd_interface_no).g2u($db->f(fd_interface_name));
		$arr_dept_id[]        = $db->f(fd_interface_id);
	}
} 
// echo $interface_id;

$interface_id= makeselect($arr_dept_easyname,$interface_id,$arr_dept_id);
	//生成选择菜单的函数
function makeselect($arritem,$hadselected,$arry){ 
  for($i=0;$i<count($arritem);$i++){
     if ($hadselected ==  $arry[$i]) {
       	 $x .= "<option selected value='$arry[$i]'>".$arritem[$i]."</option>" ;
     }else{
       	 $x .= "<option value='$arry[$i]'>".$arritem[$i]."</option>"  ;	   	
     }
   } 
   return $x ; 
}

$t = new Template(".","keep");
$t->set_file("demo"       , "test.html");   //大框
	




$t->set_var('interface_id',$interface_id);
$t->set_var('apiname',$apiname);
$t->set_var('shopinvoice_kdno',$shopinvoice_kdno);
$t->set_var('shopinvoice_kdname',$shopinvoice_kdname);
$t->set_var('sel_checkbox',$sel_checkbox);
$t->pparse("out", "demo");    # 最后输出页面
?>