<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

if($year)
{
	$whereyear="";
}
if($month)
{
	$wheremonth="";
}


$query = "select *  from tb_authoraccountglist 
left join tb_paycard on fd_paycard_id = fd_accglist_paycardid  
left join tb_saler on fd_saler_id = fd_paycard_salerid  
where fd_accglist_paycardid='$paycardid' and year(fd_accglist_datetime) ='$year' and month(fd_accglist_datetime) ='$month' order by fd_accglist_datetime";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
	
		$vpayno      = $db->f(fd_accglist_no);  
		$vpaycardid      = $db->f(fd_accglist_paycardid); 
		$vpaycardno     = $db->f(fd_paycard_no);            //id鍙�
		$vsalername      = g2u($db->f(fd_saler_truename));  
		$vaddmoney      = $db->f(fd_accglist_addmoney);  
		$vlessmoney      = $db->f(fd_accglist_lessmoney);  
		
		$vpaydatetime      = $db->f(fd_accglist_datetime); 
		$vpaymode      = g2u($db->f(fd_accglist_paymode)); 
		$vpaymemo      = g2u($db->f(fd_accglist_memo)); 
		
		$vlessmoney=abs($vlessmoney);
		$vaddmoney=abs($vaddmoney);
	   
	   $arr_list[] = array(
		                $vpayno,
						$vpaycardno,
						$vsalername,
						$vaddmoney,
						$vlessmoney,
						$vpaymode,
						$vpaymemo,
						$vpaydatetime);
    
	}
	}

  
  if($arr_list=="")
{
	 $arr_list[] = array(
		                "",
						"",
						"",
						"",
						"",
						"",
						"",
						"");
						//echo $arr_list;
						
}

        $returnarray['aaData']=$arr_list;
	    echo  json_encode($returnarray);

?>