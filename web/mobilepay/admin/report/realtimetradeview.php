<?php
$thismenucode = "10n002";
require ("../include/common.inc.php");
$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("agency_monthtable_view","realtimetradeview.html");
$indate=date("Y-m-d",time());

//获取代付收入金额
$t->set_block ("agency_monthtable_view", "prolist", "prolists" );
$query = "select
       case 
        when fd_agpm_payrq ='01' then '<font color=blue>请求交易</font>'
        when fd_agpm_payrq ='00' then '<font color=green>交易完成</font>'
        when fd_agpm_payrq ='03' then '<font color=red>交易取消</font>'
        else '无效状态' END  payrq,
		case 
        when fd_paycard_scope ='creditcard' then '信用卡'
        when fd_paycard_scope ='bankcard' then '借记卡'
		 END paycardscope,
		 
		fd_appmnu_name  as	 paytype,
		fd_agpm_id as payid,
		fd_paycard_key as paycardkey,
			fd_sdcr_name as sdcrname,
			fd_agpm_bkordernumber as bkordernumber,
			fd_author_truename as authorname,
			date_format(fd_agpm_paydate,'%m-%d') as paydate ,
		   date_format(fd_agpm_arrivedate,'%m-%d') as arrivedate,
			fd_agpm_bkmoney as paymoney,
		   fd_agpm_sdcrpayfeemoney as sdcrpayfeemoney,
		   fd_agpm_payfee as payfee ,
		   (fd_agpm_payfee -fd_agpm_sdcrpayfeemoney) as lirun  
			from tb_agentpaymoneylist
			left join tb_author on fd_author_id = fd_agpm_authorid
			left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
			left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
			left join tb_sendcenter on fd_agpm_sdcrid = fd_sdcr_id
			where fd_agpm_method = 'in' 
			order by fd_agpm_datetime desc limit 100";

$db->query($query);
$arr_result = $db->getFiledData('');
foreach($arr_result as $val)
{
$t->set_var($val);
$allpaymoney +=$val['paymoney'];
$allsdcrpayfeemoney +=$val['sdcrpayfeemoney'];
$allpayfee +=$val['payfee'];
$alllirun +=$val['lirun'];
$t->parse("prolists", "prolist", true);	
}
if(empty($arr_result))
{
$t->parse("prolists", "", true);	
}
$query = "select
			fd_agpm_paydate as paydate,
			fd_agpm_bkmoney as paymoney
			from tb_agentpaymoneylist
			where fd_agpm_method = 'in' 
			and fd_agpm_paydate='$indate'";

$db->query($query);
$arr_data = $db->getFiledData('');
foreach($arr_data as $val)
{
$all_paymoney += $val['paymoney'];
}
if(empty($arr_data))
{
$all_paymoney="0";	
}

//获取代付支出金额
$t->set_block ("agency_monthtable_view", "outpay", "outpays" );
$query = "select
        case  
        when fd_agpm_payrq ='01' then '<font color=blue>请求交易</font>'
        when fd_agpm_payrq ='00' then '<font color=green>交易完成</font>'
       when fd_agpm_payrq ='03' then '<font color=red>交易取消</font>'
        else '无效状态' END  payrq, 
		fd_agpm_id as out_payid,
		fd_agpm_bkordernumber as out_bkordernumber,
		fd_paycard_key as out_paycardkey,
		fd_appmnu_name  as	 out_paycardscope,
		fd_author_truename as out_authorname,
		(fd_agpm_agentdate) as out_paydate,
		  fd_agpm_arrivedate as arrivedate,
			fd_agpm_paymoney as out_paymoney
			from tb_agentpaymoneylist
			left join tb_author on fd_author_id = fd_agpm_authorid
			left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
			left join tb_appmenu on fd_appmnu_no = fd_agpm_paytype
			where fd_agpm_isagentpay = '1'  
			order by fd_agpm_agentdate desc limit 100";

$db->query($query);
$arr_result1 = $db->getFiledData('');

foreach($arr_result1 as $val)
{
$t->set_var($val);
$out_allpaymoney +=$val['out_paymoney'];
$t->parse("outpays", "outpay", true);	
}
if(empty($arr_result1))
{
$t->parse("outpays", "", true);	
}
	$query = "select
				fd_agpm_paydate as out_paydate,
				fd_agpm_bkmoney as out_paymoney
				from tb_agentpaymoneylist
				where fd_agpm_method = 'out' and fd_agpm_payrq=1
				and fd_agpm_paydate='$indate'";
	
	$db->query($query);
	$arr_data1 = $db->getFiledData('');
	foreach($arr_data1 as $val)
	{
		$out_allmoney += $val['out_paymoney'];
	}
	if(empty($arr_data1))
	{
		$out_allmoney = "0";	
	}
$allpaymoney=sprintf("%.2f", $allpaymoney);
$allpayfee=sprintf("%.2f", $allpayfee);
$allsdcrpayfeemoney=sprintf("%.2f", $allsdcrpayfeemoney);
$alllirun=sprintf("%.2f", $alllirun);
$out_allpaymoney=sprintf("%.2f", $out_allpaymoney);


$t->set_var ( "error", $error );
$t->set_var ( "all_paymoney", $all_paymoney );
$t->set_var ( "out_allmoney", $out_allmoney );

$t->set_var ( "allpaymoney", $allpaymoney );
$t->set_var ( "allpayfee", $allpayfee );
$t->set_var ( "allsdcrpayfeemoney", $allsdcrpayfeemoney );
$t->set_var ( "alllirun", $alllirun );
$t->set_var ( "out_allpaymoney", $out_allpaymoney );

$t->set_var("skin",$loginskin);
$t->pparse("out", "agency_monthtable_view");    # 最后输出页面

?>