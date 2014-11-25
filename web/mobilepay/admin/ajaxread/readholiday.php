<?
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gbk'); 
require("../include/common.inc.php");
require_once('../include/json.php');

$db  = new DB_test;

$query="select fd_holiday_active as active from tb_holiday where fd_holiday_id ='$holidayid'";
$db->query($query);
$arr_state=$db->get_one();

if($arr_state['active'])
{
	$changestate=0;
	$returnvalue="Í£ÓÃ";
	
}else{
	$changestate=1;
	$returnvalue="ÆôÓÃ";
}

$query = "update tb_holiday set fd_holiday_active='$changestate'	
where fd_holiday_id ='$holidayid'";
$db->query($query);

echo $returnvalue;


	
	


?>