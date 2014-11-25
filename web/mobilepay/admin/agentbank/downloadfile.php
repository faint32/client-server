<?php
	require ("../include/common.inc.php");
	require("collection.class.php");
	$arr_result = $arr = array();
	$db = new DB_test();

	$arr = array(
	 	array(
	 		'collectionNumber' => 'F' , //代收号
	 		'ersionNumber' => '03' , //版本号
	 		'merchantId' => '000191400201746' , //商户ID//银联网络分配
	 		'businessType' => '09900' , //业务类型
	 	),
	 	array(),
	 );
     $query = "select fd_couponset_fee as fee,fd_couponset_maxfee as maxfee from tb_couponset left join tb_arrive on fd_arrive_id =fd_couponset_arriveid";
	$arr_couponset = $db->get_one($query);
	$couponfee = substr($arr_couponset['fee'], 0, -1); //浮动手续费 
	
	$query = "select 
		fd_pymyltdetail_id				as vid,
		fd_author_truename				as author,
		fd_agpm_shoucardno				as shoucardno,
		fd_agpm_shoucardbank			as shoucardbank,
		fd_agpm_shoucardman			as shoucardman,
		fd_agpm_shoucardmobile			as shoucardmobile,
		fd_agpm_current				as current,
		fd_agpm_paymoney				as paymoney ,
		fd_agpm_payfee					as payfee,
		fd_agpm_money					as money,
		fd_paycardaccount_accountname	as accountname,
		fd_paycardaccount_accountnum	as accountnum,
		fd_bank_no						as bankno," .
	   "fd_agpm_paytype				    as paytype
		from tb_paymoneylistdetail
		left join  tb_agentpaymoneylist on fd_pymyltdetail_agpmid = fd_agpm_id
		left join  tb_bank on fd_bank_name = fd_agpm_shoucardbank
		left join tb_paycard on fd_agpm_paycardid = fd_paycard_id
		left join tb_author  on fd_author_id  = fd_agpm_authorid 
		left join  tb_paycardaccount  on fd_paycard_paycardaccount = fd_paycardaccount_id
		where fd_pymyltdetail_paymoneylistid = '$listid'
		order by fd_agpm_bkntno";
	$db->query($query);
	$arr_result = $db->getFiledData('');

	foreach ($arr_result as $key => $value)
	{
		
		
		
		if($value['paytype']=='coupon')
		{
			//$paymoney = round($value['paymoney'] * 100 * (1-$fee*0.01),0);
			
			$value['payfee'] = $value['paymoney']  * ($couponfee*0.01); 
			$value['payfee'] = $arr_couponset['maxfee']<$value['payfee']?$arr_couponset['maxfee']:$value['payfee'];
			$paymoney = $value['paymoney'] -$value['payfee'];
			$paymoney = round($paymoney * 100,0);
		}else
		{
			$paymoney = round($value['paymoney'] * 100,0);
		}
		
		
		
		$arr[1][$key]['e_user_code'] = '';//银联网络用户编号
		$arr[1][$key]['bank_code'] = $value['bankno'];//银行代码
		$arr[1][$key]['account_type'] = '00';//帐号类型
		$arr[1][$key]['account_no'] = $value['shoucardno'];//账号
		$arr[1][$key]['account_name'] = $value['shoucardman']; //账户名
		$arr[1][$key]['province'] = '';//开户行所在省
		$arr[1][$key]['city'] = '';//开户行所在市
		$arr[1][$key]['account_prop'] = '0';//开户行名称
		$arr[1][$key]['amount'] = $paymoney;//金额
		// $arr[1][$key]['currency'] = $value['current'];//货币类型
		$arr[1][$key]['currency'] = 'CNY';//货币类型
		$arr[1][$key]['protocol'] = '';//协议号
		$arr[1][$key]['protocol_userid'] = '';//协议用户编号
		$arr[1][$key]['id_type'] = '';//开户证件类型
		$arr[1][$key]['id'] = '';//证件号
		$arr[1][$key]['tel'] = $value['shoucardmobile'];//手机号
		$arr[1][$key]['cust_userid'] = '';//自定义用户号
		$arr[1][$key]['clearing_account'] = '';//清分账号
		$arr[1][$key]['remark'] = '';//备注
		$arr[1][$key]['remark_one'] = '';//备注1
		$arr[1][$key]['remark_two'] = '';//备注2
		$arr[1][$key]['feedback'] = '';//反馈码
		$arr[1][$key]['reason'] = '';//原因
	}

	$collection = new Collection();
	$date = '';
	$count = count($arr[1]);
	$collection->collectionNumber = $arr[0]['collectionNumber'];//代收号
	$collection->ersionNumber = $arr[0]['ersionNumber'];//版本号
	$collection->merchantId = $arr[0]['merchantId'];//商户ID//银联网络分配
	$collection->businessType = $arr[0]['businessType'];//业务类型
	$collection->recordsAll = $count;//总记录数

	for ($i = 0; $i < $count; $i++)
	{
		$date .= 
		str_pad( ( $i+1 ) , 7, '0', STR_PAD_LEFT ) . ',' . 
		$arr[1][$i]['e_user_code'] . ',' . 
		$arr[1][$i]['bank_code'] . ',' . 
		$arr[1][$i]['account_type'] . ',' . 
		$arr[1][$i]['account_no'] . ',' . 
		$arr[1][$i]['account_name'] . ',' . 
		$arr[1][$i]['province'] . ',' . 
		$arr[1][$i]['city'] . ',' . 
		$arr[1][$i]['bank_name'] . ',' . 
		$arr[1][$i]['account_prop'] . ',' . 
		$arr[1][$i]['amount'] . ',' . 
		$arr[1][$i]['currency'] . ',' . 
		$arr[1][$i]['protocol'] . ',' . 
		$arr[1][$i]['protocol_userid'] . ',' . 
		$arr[1][$i]['id_type'] . ',' . 
		$arr[1][$i]['id'] . ',' . 
		$arr[1][$i]['tel'] . ',' . 
		$arr[1][$i]['cust_userid'] . ',' . 
		$arr[1][$i]['clearing_account'] . ',' . 
		$arr[1][$i]['remark'] . ','.
		$arr[1][$i]['remark_one'] . ',' . 
		$arr[1][$i]['remark_two'] . ',' . 
		$arr[1][$i]['feedback'] . ',' . 
		$arr[1][$i]['reason'] . "\r\n";
		$collection->amountAll += $arr[1][$i]['amount'];
	}
	$collection->GetContent($date);
 ?>