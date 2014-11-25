<?php
header('Content-Type: application/x-www-form-urlencoded');
header('Content-Type: text/html;charset=gb2312'); 
require("../include/common.inc.php");
require_once('../include/json.php');
$db=new db_test;
$count=0;

	$db = new DB_test;
	$arr_type=explode("@",$type);
	if($arr_type[1]=="one")
	{
		$newdate=date("Y-m-d",time());
		$querydate="date_format(fd_agpm_paydate,'%Y-%m-%d')='$newdate'";
		$querydate1="date_format(fd_pmreq_reqdatetime,'%Y-%m-%d')='$newdate'";
	}else{
		$newdate=date("Y-m",time());
		$querydate="date_format(fd_agpm_paydate,'%Y-%m')='$newdate'";
		$querydate1="date_format(fd_pmreq_reqdatetime,'%Y-%m')='$newdate'";
	}
	if($arr_type[0]=="use"){
	$query="select 
	case 
        when fd_agpm_paytype ='coupon' then '购买抵用券'
        when fd_agpm_paytype ='creditcard' then '信用卡还款'
       when fd_agpm_paytype ='recharge' then        '充值'
       when fd_agpm_paytype ='repay' then       '还贷款'
       when fd_agpm_paytype ='order' then '订单付款'
       when fd_agpm_paytype ='tfmg' then '转账汇款'
        else '其他业务' END  paytype,
		case 
        when fd_agpm_payrq ='01' then '<font color=blue>请求交易</font>'
        when fd_agpm_payrq ='00' then '<font color=green>交易完成</font>'
       when fd_agpm_payrq ='03' then '<font color=red>交易取消</font>'
        else '无效状态' END  payrq,
	fd_paycard_key as paycardkey,
	fd_agpm_paymoney as paymoney,
	fd_agpm_payfee as payfee,
	fd_agpm_paydate as paydate
	  from  tb_agentpaymoneylist
	left join tb_paycard on fd_agpm_paycardid=fd_paycard_id
	where fd_agpm_authorid='$authorid' and $querydate  and fd_agpm_payrq='00'
	and fd_paycard_scope ='$scope'";
	$db->query($query);	
	
	$totoalcount=$db->nf()+0;
	$query = "$query limit $iDisplayStart,$iDisplayLength  ";
	$db->query($query);
	if($db->nf())
	{	
		while($db->next_record())
		{

		   $paycardkey             = $db->f(paycardkey);            
		   $paytype    = g2u($db->f(paytype));  
		   $payrq    = g2u($db->f(payrq)); 
		   $paymoney       = $db->f(paymoney);
		   $paydate       = $db->f(paydate);
		   $payfee      = $db->f(payfee);
 
	

		   $arr_list[] = array("DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$paycardkey,
							$paytype,
							$payrq,
							$paydate,
							$paymoney,
							$payfee
							);
		 }
	}
	else
	{     
		 $vmember  = "暂无数据";
		 $arr_list[] = array(
							"DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$paycardkey,
							$paytype,
							$payrq,
							$paydate,
							$paymoney,
							$payfee
							);
	}
}else{
	
		$query="select 
		date_format(fd_pmreq_reqdatetime,'%Y-%m-%d') as reqdatetime ,
		fd_pmreq_repmoney as  repmoney , fd_pmreq_reqmoney as reqmoney
		
	   from tb_slotcardmoneyreq 
	   left join tb_slotcardmoneyset on fd_scdmset_id=fd_pmreq_paymsetid
	where fd_pmreq_authorid='$authorid' and fd_pmreq_paymsetid='$scdmsetid' and  $querydate1 and fd_pmreq_state <>'0' and fd_scdmset_scope='$scope'
	";
		$db->query($query);	
	$totoalcount=$db->nf()+0;
	$query = "$query limit $iDisplayStart,$iDisplayLength  ";		
	$db->query($query);	
	if($db->nf())
	{
		while($db->next_record())
		{
		   $reqdatetime = $db->f(reqdatetime);            
		   $repmoney    = $db->f(repmoney);  
		   $reqmoney    = $db->f(reqmoney); 

		   $arr_list[] = array("DT_RowId" => $vid ,
				"DT_RowClass" => "",
				$reqmoney,
				$repmoney,
				$reqdatetime
				);
		}
	}else
	{     
		 $vmember  = "暂无数据";
		 $arr_list[] = array(
							"DT_RowId" => $vid ,
							"DT_RowClass" => "",
							$reqmoney,
							$repmoney,
							$reqdatetime
							);
	}
	
}
		$returnarray['sEcho']=intval($sEcho);
		$returnarray['iTotalRecords']=$totoalcount;
		$returnarray['iTotalDisplayRecords']=$totoalcount;
	    $returnarray['aaData']=$arr_list;
		$returnvalue = json_encode($returnarray);
	    echo  json_encode($returnarray);

?>