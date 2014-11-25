<?
$thismenucode = "10n002";
require ("../include/common.inc.php");
$db = new DB_test ;
//$dbtj = new DB_test ;
$t = new Template('.', "keep");
$t->set_file("agency_monthtable_view","realtimetradeview.html");
$indate=date("Y-m-d",time());

//获取代付收入金额
$t->set_block ("agency_monthtable_view", "prolist", "prolists" );
$query = "select" .
		"  case 
        when fd_agpm_paytype ='coupon' then '购买抵用券'
        when fd_agpm_paytype ='creditcard' then '信用卡还款'" .
       "when fd_agpm_paytype ='recharge' then        '充值'" .
       "when fd_agpm_paytype ='repay' then       '还贷款'" .
       "when fd_agpm_paytype ='order' then '订单付款'" .
       "when fd_agpm_paytype ='tfmg' then '转账汇款'
        else '其他业务' END  paytype," .
        "case 
        when fd_agpm_payrq ='0' then '请求交易'
        when fd_agpm_payrq ='1' then '交易完成'
        else '取消交易' END  payrq,
			fd_agpm_id as payid,
			fd_paycard_key as paycardkey,
			fd_paycard_scope as paycardscope, 
			fd_author_truename as authorname,
			fd_agpm_paydate as paydate," .
		   "fd_agpm_arrivedate as arrivedate,
			fd_agpm_paymoney as paymoney
			from tb_agentpaymoneylist
			left join tb_author on fd_author_id = fd_agpm_authorid
			left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
<<<<<<< .mine
			where fd_agpm_method = 'in' $where
			order by fd_agpm_id desc limit 100";
=======
			where fd_agpm_method = 'in' 
			order by fd_agpm_datetime desc limit 100";
>>>>>>> .r3683

$db->query($query);
$arr_result = $db->getFiledData('');
<<<<<<< .mine
=======
foreach($arr_result as $val)
{
$t->set_var($val);
$t->parse("prolists", "prolist", true);	
}
if(empty($arr_result))
{
$t->parse("prolists", "", true);	
}
$query = "select
			fd_agpm_paydate as paydate,
			fd_agpm_paymoney as paymoney
			from tb_agentpaymoneylist
			where fd_agpm_method = 'in' 
			and fd_agpm_paydate='$indate'";
>>>>>>> .r3683

	foreach($arr_result as $val){
	$t->set_var($val);
	if($val[paydate]==$indate){
		$all_paymoney += $val['paymoney'];
	}
	$t->parse("prolists", "prolist", true);	
	}
	if(empty($arr_result))
	{
	$t->parse("prolists", "", true);	
	}


//获取代付支出金额
$t->set_block ("agency_monthtable_view", "outpay", "outpays" );
$query = "select
			fd_agpm_id as out_payid,
			fd_paycard_key as out_paycardkey,
			fd_paycard_scope as out_paycardscope, 
			fd_author_truename as out_authorname,
			SEC_TO_TIME(fd_agpm_paydate) as out_paydate," .
		  " fd_agpm_arrivedate as arrivedate,
			fd_agpm_paymoney as out_paymoney
			from tb_agentpaymoneylist
			left join tb_author on fd_author_id = fd_agpm_authorid
			left join tb_paycard on fd_paycard_id = fd_agpm_paycardid
			where fd_agpm_method = 'out' 
			order by fd_agpm_datetime desc limit 100";

$db->query($query);
$arr_result1 = $db->getFiledData('');
foreach($arr_result1 as $val){
	$t->set_var($val);
	if($val[out_paydate]==$indate){
		$out_allmoney += $val['out_paymoney'];
	}
	$t->parse("outpays", "outpay", true);	
	}
	if(empty($arr_result1))
	{
	$t->parse("outpays", "", true);	
}
$t->set_var ( "error", $error );
$t->set_var ( "all_paymoney", $all_paymoney );
$t->set_var ( "out_allmoney", $out_allmoney );
$t->set_var("skin",$loginskin);
$t->pparse("out", "agency_monthtable_view");    # 最后输出页面

?>