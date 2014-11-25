<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=utf-8'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;
//$search_authorname=u2g(unescape($search_authorname));

if(!empty($search_authorname))
{
	$search_wherequery .="and fd_author_truename like '%$search_authorname%'";
}

if(!empty($search_begintime) and !empty($search_endtime))
{
	$search_wherequery .="and fd_paycard_newspaydata between  '$search_begintime' and '$search_endtime' ";
}

if(!empty($search_time))
{
	switch($search_time)
	{
		case 'now':
		$search_wherequery .="and  to_days(fd_paycard_newspaydata) = to_days(now())";
		break;
		case 'week':
		$search_wherequery .="and  DATE_SUB(CURDATE(), INTERVAL 7 DAY)<= date(fd_paycard_newspaydata)";
		break;
		case 'onemonth':
		$search_wherequery .="and DATE_SUB(CURDATE(), INTERVAL  1 MONTH)<=date(fd_paycard_newspaydata)";
		break;
		case 'threemonth':
		$search_wherequery .="and DATE_SUB(CURDATE(), INTERVAL  3 MONTH)<=date(fd_paycard_newspaydata)";
		break;
		
	}
	
}

if(empty($search_begintime) and empty($search_endtime) and empty($search_time))
{
	
	$search_wherequery .="and date_format(fd_paycard_newspaydata,'%Y-%m')=date_format(now(),'%Y-%m')";
}
if(!empty($search_possate))
{
	$search_wherequery .="and fd_paycard_posstate='$search_possate'";
}
$query="select fd_agpm_paycardid, sum(fd_agpm_paymoney) as allmoney from tb_agentpaymoneylist  group by fd_agpm_paycardid";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$paycardid=$db->f(fd_agpm_paycardid);
		
		$arr_allpaymoney[$paycardid]=$db->f(allmoney);
	}
}

$query="select fd_pmreq_authorid,fd_pmreq_paymsetid, sum(fd_pmreq_repmoney) as allmoney from tb_slotcardmoneyreq where fd_pmreq_state='9' group by fd_pmreq_authorid";
$db->query($query);
if($db->nf())
{
	while($db->next_record())
	{
		$authorid=$db->f(fd_pmreq_authorid);
		$paymsetid=$db->f(fd_pmreq_paymsetid);
		$arr_allrepmoney[$authorid][$paymsetid]=$db->f(allmoney);
	}
}

	
$query = "select * from tb_paycard 
left join tb_author on fd_paycard_authorid=fd_author_id
left join tb_slotcardmoneyset on fd_scdmset_id=fd_author_scdmsetid
where 1 $search_wherequery and fd_paycard_authorid <>''";
$db->query($query);
$totoalcount=$db->nf()+0;
$query = "$query limit $iDisplayStart,$iDisplayLength  ";
$db->query($query);

if($db->nf())
{	
	while($db->next_record())
	{

	   $vid             = $db->f(fd_paycard_id);            //id号  
	   $vauthorid       = $db->f(fd_author_id);            //id号
	   $vpaycardkey     = $db->f(fd_paycard_key);            //id号  
	   $authortruename  = g2u($db->f(fd_author_truename));
	   $posstate        = $db->f(fd_paycard_posstate);
	   $scope           = $db->f(fd_paycard_scope);
	   $paymsetid       = $db->f(fd_scdmset_id);
	   $paymset_name    = g2u($db->f(fd_scdmset_name));	   
		
		$creditcardmoney="0";
		$uescreditcardmoney="0";
		$bankcardmoney="0";
		$usebankcardmoney="0";	
		$warning="否";
		switch($scope)
		{
			case 'creditcard':
			$creditcardmoney=$nallmoney+$arr_allrepmoney[$vauthorid][$paymsetid];
			$uescreditcardmoney=$arr_allpaymoney[$vid];
			break;
			case 'bankcard':
			$bankcardmoney=$nallmoney+$arr_allrepmoney[$vauthorid][$paymsetid];
			$usebankcardmoney=$arr_allpaymoney[$vid];;
			break;
		}
		$vedit ='<a href="checkpaycard.php?paycardid='.$vid.'&search_begintime='.$search_begintime.'&search_endtime='.$search_endtime.'&search_time='.$search_time.'"  class="edit" >查看</a>';
		switch($posstate)
		{
			case "0":
			$posstate="停用";
			 $vedit .='<a href="#" onclick="startup(\''.$vid.'\')" class="edit" >启用</a>';
			break;
			case "1":
			$posstate="警告";
			break;
			case "2":
			$posstate="启用";
			break;
			case "3":
			$posstate="冻结结算账户";
			$vedit .='<a href="#" onclick="startup(\''.$vid.'\')" class="edit" >启用</a>';
			break;
		}
		
		$checkall= '<INPUT  type=checkbox class=checkbox value='.$vid.' rel=arr_paycard name=arr_list[]>';
		
	  
	   	$count++;   
	   $arr_list[] = array("DT_RowId" => $vid ,
                        "DT_RowClass" => "",
		                $checkall,
						$authortruename,
						$vpaycardkey,
						$paymset_name,
						$creditcardmoney,
						$bankcardmoney,
						$uescreditcardmoney,
						$usebankcardmoney,
						g2u($warning),
						g2u($posstate),
						g2u($vedit)
						);
     }
   }
   else
   {     
     $vmember  = "暂无数据";
	 $arr_list[] = array(
	                    "DT_RowId" => $vid ,
                        "DT_RowClass" => "",
		                $checkall,
						$authortruename,
						$vpaycardkey,
						$paymset_name,
						$creditcardmoney,
						$bankcardmoney,
						$uescreditcardmoney,
						$usebankcardmoney,
						$warning,
						g2u($posstate),
						g2u($vedit)
						);
   }
      
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>