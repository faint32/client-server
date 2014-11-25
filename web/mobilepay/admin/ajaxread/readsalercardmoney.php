<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

if($year)
{
	$whereyear="and year(fd_accglist_datetime) ='$year'";
}
if($month)
{
	$wheremonth="and month(fd_accglist_datetime) ='$month'";
}


$query = "select * , count(*) as allnum ,CONCAT( month( fd_accglist_datetime ) )  as accmonth ,CONCAT( year( fd_accglist_datetime ) )  as accyear from tb_authoraccountglist 
left join tb_paycard on fd_paycard_id = fd_accglist_paycardid  
left join tb_saler on fd_saler_id = fd_paycard_salerid  
where 1 $whereyear $wheremonth group by year(fd_accglist_datetime), month(fd_accglist_datetime), fd_accglist_paymode, fd_accglist_paycardid";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$accmonth        =$db->f(accmonth); 
		$accyear        =$db->f(accyear); 
		$vpaycardid      = $db->f(fd_accglist_paycardid);            //idºÅ 
		$vpaycardno     = $db->f(fd_paycard_no);            //idºÅ 
		$vsalername      = g2u($db->f(fd_saler_truename));  
		$allnum			 = $db->f(allnum);
		$vaddmoney      = $db->f(fd_accglist_addmoney);  
		$vlessmoney      = $db->f(fd_accglist_lessmoney);  
		$vpaymode      = g2u($db->f(fd_accglist_paymode));  
		
		$money=abs($vaddmoney)+abs($vlessmoney);
		$vedit="<a class='edit' href='salerpushmoney_info.php?paycardid=$vpaycardid&year=$accyear&month=$accmonth'>²é¿´</a>";
	   
	   $arr_list[] = array(
		                $accyear."/".$accmonth,
						$vpaycardno,
						$vsalername,
						$allnum,
						$money,
						$vpaymode,
						g2u($vedit)
						);
    
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
						"");
						//echo $arr_list;
						
}

        $returnarray['aaData']=$arr_list;
	    echo  json_encode($returnarray);

?>