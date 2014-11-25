<?php
class ApiOrderInfo extends TfbxmlResponse {
	//版本号管理		    
	public function readOrderList() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$msgdisplay = g2u(trim($arr_body['msgdisplay']));
		$appversion = g2u(trim($arr_body['appversion']));
		$orderno = g2u(trim($arr_body['orderno']));
		$orderstate = g2u(trim($arr_body['orderstate']));   // nopay/pay/all
		$querycondi = g2u(trim($arr_body['querywhere']));   // 近一个月使用符号：@  一个月前：#  
		$msgstart = g2u(trim($arr_body['msgstart']));
		$authorid = g2u(trim($arr_channelinfo['authorid']));
		$arr_authorinfo = AuToken::getauthorusername($authorid);
		$ordermemid = $arr_authorinfo['memid'];
		if(!$ordermemid)
		{
			$arr_message = array (
				"result" => "failure",
				"message" => "还未开通商家，没有订单信息！"
			);
			$retcode = "200";  //反馈状态 0 成功 200 自定义错误
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
		exit;
			
		}
		$today= date('Y-m-d');
		$premonthday= date('Y-m-d', strtotime("$today -1 month -0 day"));
		
		// $querywhere     = g2u(trim($arr_body['querywhere']));
		switch ($orderstate) {
			case "nopay" :
				$querywhere = " and  fd_order_memeberid = '$ordermemid' and  fd_order_state = 6  and fd_order_no like '%$orderno%'";
				break;
			case "pay" :
				$querywhere = " and  fd_order_memeberid = '$ordermemid'  and fd_order_state = 7 and  fd_order_no like '%$orderno%' ";
				break;
			case "all" :
				$querywhere = " and fd_order_memeberid = '$ordermemid' and (fd_order_state = 7 or fd_order_state = 6 )  and fd_order_no like '%$orderno%' ";
				break;
			default :
				$querywhere = " and fd_order_memeberid = '$ordermemid' and (fd_order_state = 7 or fd_order_state = 6 )  and fd_order_no like '%$orderno%'";
				break;
		}
		switch($querycondi)
		{
			case "@":  //近一个月
			$querywhere .= " and fd_order_date>'$premonthday'";
			break;
			case "#":  //一个月前：# 
			$querywhere .= " and fd_order_date<='$premonthday'";
			break;
			
		}
		$Clsmssale = new APImssale();
		$arr_msg = $Clsmssale->getorderinfo($querywhere, $msgstart, $msgdisplay);
		$arr_msg['msgbody'] = auto_charset($arr_msg, 'gbk', 'utf-8');
		
		//echo var_dump($arr_msg ['msgbody']);
		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	public function orderPayBankCardStar() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$orderid = g2u(trim($arr_body['orderid']));
		$orderno = g2u(trim($arr_body['orderno']));
		$paymoney = g2u(trim($arr_body['paymoney']));
		$bankcardno = g2u(trim($arr_body['bankcardno']));
		$bankname = g2u(trim($arr_body['bankname']));

		$arr_message = array (
			"result" => "success",
			"message" => "获取成功!",
			"bankcardstar" => "信用优良"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$arr_msg['msgbody']['bankcardstar'] = $arr_message['bankcardstar'];

		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	public function orderPayReq() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$orderid = g2u(trim($arr_body['orderid']));
		$orderno = g2u(trim($arr_body['orderno']));
		$paymoney = (trim($arr_body['paymoney'] + 0));
		$fucardno = g2u(trim($arr_body['bankcardno']));
		$bankname = g2u(trim($arr_body['bankname']));
		$authorid = g2u(trim($arr_channelinfo['authorid']));
        //$paycardid = u2g(trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid']))); //银行类型
		$arr_message = array (
			"result" => "success",
			"message" => "成功获取订单交易流水号!"
		);
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$bkmoney = $paymoney;
		$arr_bkinfo = BankPayInfo :: bankpayorder($authorid,$paycardid,$paymoney, $fucardno);
        $bkntno     = trim($arr_bkinfo['bkntno']);
		$arr_feeinfo['bkordernumber']=$bkorderNumber     = $arr_bkinfo['bkorderNumber']; 
		$arr_feeinfo['sdcrid']=$sdcrid = trim($arr_bkinfo['sdcrid']);
		$sdcrpayfee = substr($arr_bkinfo['sdcrpayfee'], 0, -1);  //银联收取明盛浮动费率
		$arr_feeinfo['sdcrpayfeemoney']=$sdcrpayfeemoney = (($bkmoney*$sdcrpayfee)/100)>($arr_bkinfo['minsdcrpayfee'])?(($bkmoney*$sdcrpayfee)/100):($arr_bkinfo['minsdcrpayfee']);
		
		$paytype  = 'order';
		$arr_msg['msgbody']['bkntno'] = $bkntno;

		$ccgno = makeorderno("orderpayglist", "oplist", "opg");
		$query = "insert into tb_orderpayglist(
									fd_oplist_no			,fd_oplist_paycardid		,fd_oplist_authorid	,
						            fd_oplist_paydate		,fd_oplist_orderid   		,fd_oplist_fucardno	,
						            fd_oplist_bkntno		,fd_oplist_payrq           ,fd_oplist_paytype     ,
						            fd_oplist_current		,fd_oplist_paymoney		   ,fd_oplist_payfee		,
						            fd_oplist_money		,fd_oplist_orderno             ,fd_oplist_shopid ,
						            fd_oplist_fucardbank		,fd_oplist_fucardmobile,
						            fd_oplist_fucardman    ,fd_oplist_feebankid       ,fd_oplist_state ," .
						           "fd_oplist_bkorderNumber,fd_oplist_sdcrid		,fd_oplist_sdcrpayfeemoney 	
						            )values
								   ('$ccgno'		,'$paycardid'	,'$authorid'		,
								   '$paydate'		,'$orderid'		,'$fucardno'		,
								   '$bkntno'		,'01'			,'$paytype'			,
								   '$current'		,'$paymoney'	,'$payfee'			,
								   '$paymoney'		,'$orderno'		,'$shopid'			,
								   '$fucardbank'	,'$fucardmobile',
								   '$fucardman'		,'$feebankid'	,'0'," .
								  "'$bkorderNumber' ,'$sdcrid' 		, '$sdcrpayfeemoney')";
		$db->query($query);

		$returnvalue = array (
			
				"msgbody" => $arr_msg['msgbody']
			
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	//插入订单付款记录		    
	public function orderPayFeedback() {
		$db = new DB_test();
		$arr_header = $this->arr_header;
		$arr_body = $this->arr_body;
		$arr_channelinfo = $this->arr_channelinfo;
		$bkntno = trim($arr_body['bkntno']); //交易流水号
		//$paycardid = trim(GetPayCalcuInfo::readpaycardid($arr_body['paycardid'])); //刷卡器key
		$authorid = trim($arr_channelinfo['authorid']); //authorid
        $result = trim($arr_body['result']);
		$nowdate = date("Y-m-d H:i:s");
		if($result=='success')
		{
		//$query = "update tb_orderpayglist set fd_oplist_payrq ='00',fd_oplist_paydate = '$nowdate' where fd_oplist_bkntno = '$bkntno'";
		//$db->query($query);
		//$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00'  where fd_agpm_bkntno = '$bkntno'";
			//$db->query($query);
			//echo $query;
		$arr_message = array (
			"result" => "success",
			"message" => "支付成功!"
		);
		$retcode = "0";  //反馈状态 0 成功 200 自定义错误
		}else
		{
		$arr_message = array (
			"result" => "failure",
			"message" => "支付失败!"
		);
		$retcode = "200";  //反馈状态 0 成功 200 自定义错误	
		}
	
	
		$returnvalue = array (
			
				"msgbody" => array (
					'result' => $arr_message['result'],
					"message" => $arr_message['message']
				)
			
		);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}

}
function makeorderno($tablename, $fieldname, $preno = "pay") {
	$db = new DB_test();
	$db2 = new DB_test();
	$year = trim(date("Y", mktime()));
	$month = trim(date("m", mktime()));
	$day = trim(date("d", mktime()));

	$nowdate = $year . $month . $day;
	//echo $nowdate;
	$query = "select fd_" . $fieldname . "_no as no from tb_" . $tablename . "   order by fd_" . $fieldname . "_id  desc";
	$db2->query($query);
	if ($db2->nf()) {
		$db2->next_record();
		$orderno = $db2->f(no);
		$orderdate = substr($orderno, 3, 8); //截取前8位判断是否当前日期     
		if ($nowdate == $orderdate) {
			$orderno = substr($orderno, 11, 6) + 1; //是当前日期流水帐加1
			if ($orderno < 10) {
				$orderno = "00000" . $orderno;
			} else
				if ($orderno < 100) {
					$orderno = "0000" . $orderno;
				} else
					if ($orderno < 1000) {
						$orderno = "000" . $orderno;
					} else
						if ($orderno < 10000) {
							$orderno = "00" . $orderno;
						} else {
							$orderno = $orderno;
						}
			$orderno = $preno . $nowdate . $orderno;
		} else {
			$orderno = $preno . $nowdate . "000001"; //不是当前日期,为5位流水帐,开始值为1。
		}
	} else {
		$orderno = $preno . $nowdate . "000001";
	}
	return $orderno;
}
?>