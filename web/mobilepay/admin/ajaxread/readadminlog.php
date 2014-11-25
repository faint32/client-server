<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;



   
$query="select * from web_adminlog where fd_log_listid='$log_shopid'";   				  

$db->query($query);
$totoalcount=$db->nf()+0;
if($db->nf())
{
	while($db->next_record())
	{
		$zcpeopleid=$db->f(d_log_czpeopleid);
		$zcpeople=$db->f(fd_log_czpeole);
		$zctime=$db->f(fd_log_cztime);
		$czsql=$db->f(fd_log_sql);
		$zctype=$db->f(fd_log_cztype );
		$zcpeople="<span id='$zcpeopleid'>$zcpeople</span>";
	   $arr_list[] = array(
		                $zcpeople,
						$zctype,
						$czsql,
						$zctime,
						$vmember,						
					);	
     }
   }
   else
   {     
     $vmember  = "ÔÝÎÞÊý¾Ý";
	 $arr_list[] = array(
	                   
		                $zcpeople,
						$zctype,
						$czsql,
						$zctime,
						g2u($vmember) ,
					);	
   }
      
		 $returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>