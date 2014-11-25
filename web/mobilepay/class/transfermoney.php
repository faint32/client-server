<?php
class TransferMoney
{
	public function CreateOrder($requestBody, $authorId, $payChannel)
	{
$logger = Logger::getLogger("transfermoney");
$logger->debug("开始创建转账付款订单");

		// 调用外部函数******
$logger->debug("正在创建转账付款订单 : 调用函数GetPayCalcuInfo::readpaycardid，传入参数(" . $requestBody["cardReaderId"] . ", " . $authorId . ")");
		$agentInfo = GetPayCalcuInfo :: readpaycardid($requestBody["cardReaderId"], $authorId);
$logger->debug("正在创建转账付款订单 : 调用函数GetPayCalcuInfo::readpaycardid，返回的结果" . print_r($agentInfo, true));
		// ******调用外部函数
		$cardReaderId = $agentInfo["paycardid"];														// 刷卡器ID
		$agentId = $agentInfo["cusid"];																	// 代理商ID
		
		$payChannelFee = 0;
		switch($payChannel)
		{
			case "YiBao":
				$payChannelFee = YiBaoPay :: GetPayFee($requestBody['payMoney']);
				break;
		}
		
		$orderId = "tfbtfs" . date("YmdHiss") . mt_rand(1000, 9999);
		// 调用外部函数******
		$orderListNumber = makeorderno("transfermoneyglist", "tfmglist", "tfs");
		// ******调用外部函数
		$payDate = date("Y-m-d H:i:s");
		$feeMoney = 0;
		// 调用外部函数******
$logger->debug("正在创建转账付款订单 : 调用函数getbankid，传入参数(" . $requestBody["receiveBankName"] . ")");
		$receiveBankId = getbankid(u2g($requestBody["receiveBankName"]));
		if($receiveBankId == "")	ErrorReponse :: reponError(array("retcode" => "200", "retmsg" => "不支持的银行"));
$logger->debug("正在创建转账付款订单 : 调用函数getbankid，返回的结果" . $receiveBankId);
$logger->debug("正在创建转账付款订单 : 调用函数GetPayCalcuInfo :: readPayFee，传入参数($authorId, $receiveBankId, " . $requestBody['transferMoney'] . ", " . $requestBody['arriveId'] . ", 2, " . $requestBody["payType"] . ")");
		$payChannelFeeInfo = GetPayCalcuInfo :: readPayFee($authorId, $receiveBankId, $requestBody['transferMoney'], $requestBody['arriveId'], 2, $requestBody["payType"]);
$logger->debug("正在创建转账付款订单 : 调用函数GetPayCalcuInfo :: readPayFee，返回的结果" . print_r($payChannelFeeInfo, true));
		if(is_array($payChannelFeeInfo))	$feeMoney = $payChannelFeeInfo["feemoney"];
		$arriveDate = GetPayCalcuInfo :: getfeedate($payDate, $payChannelFeeInfo['addday']);
$logger->debug("正在创建转账付款订单 : 调用函数GetPayCalcuInfo :: getfeedate，返回的结果" . $arriveDate);
		// ******调用外部函数
		$query = "INSERT INTO tb_transfermoneyglist (fd_tfmglist_no, fd_tfmglist_bkordernumber, fd_tfmglist_payrq, fd_tfmglist_paycardid, fd_tfmglist_authorid, fd_tfmglist_sdcrid, fd_tfmglist_sdcrpayfeemoney, fd_tfmglist_sdcragentfeemoney, fd_tfmglist_paytype, fd_tfmglist_paydate, fd_tfmglist_payfeedirct, fd_tfmglist_fucardno, fd_tfmglist_fucardbank, 
		fd_tfmglist_fucardmobile, fd_tfmglist_fucardman, 
		fd_tfmglist_shoucardno, fd_tfmglist_shoucardbank, 
		fd_tfmglist_shoucardman, fd_tfmglist_shoucardmobile, fd_tfmglist_sendsms, fd_tfmglist_shoucardmemo, fd_tfmglist_current, fd_tfmglist_paymoney, fd_tfmglist_payfee, fd_tfmglist_money, fd_tfmglist_feebankid, fd_tfmglist_arrivedate, fd_tfmglist_arriveid, fd_tfmglist_state) VALUES 
		('$orderListNumber', '$orderId', '01', 
		'$cardReaderId', $authorId, 3, $payChannelFee, 
		0.00, '" . $requestBody["payType"] . "', '$payDate', 'f', 
		'" . $requestBody["sendBankCardId"] . "', '" . $requestBody["sendBankName"] . "', 
		'" . $requestBody["sendPhone"] . "', '" . $requestBody["sendPersonName"] . "', 
		'" . $requestBody["receiveBankCardId"] . "', '" . $requestBody["receiveBankName"] . "', 
		'" . $requestBody["receivePersonName"] . "', '" . $requestBody["receivePhone"] . "', 0, '', 
		'RMB', " . $requestBody["transferMoney"] . ", $feeMoney, " . $requestBody["payMoney"] . ", $receiveBankId, 
		'$arriveDate', '" . $requestBody['arriveId'] . "', '0')";
$logger->debug("新增数据进 tb_transfermoneyglist 的sql" . $query);
		$db = new DB_test();
		$db->query(u2g($query));
		
		return $orderId;
	}
	
	public function CreateAgentPayOrder($orderId)
	{
		$db = new DB_test();
		
		$query = "INSERT INTO `tb_agentpaymoneylist` (`fd_agpm_bkntno`, `fd_agpm_payrq`, `fd_agpm_bkordernumber`, `fd_agpm_bkmoney`, `fd_agpm_sdcrid`, `fd_agpm_sdcrpayfeemoney`, `fd_agpm_sdcragentfeemoney`, `fd_agpm_paycardid`, `fd_agpm_authorid`, `fd_agpm_paytype`, `fd_agpm_paydate`, `fd_agpm_shoucardno`, `fd_agpm_shoucardbank`, `fd_agpm_shoucardman`, `fd_agpm_shoucardmobile`, `fd_agpm_current`, `fd_agpm_paymoney`, `fd_agpm_payfee`, `fd_agpm_payfeedirct`, `fd_agpm_money`, `fd_agpm_feebankid`, `fd_agpm_arrivemode`, `fd_agpm_arrivedate`, `fd_agpm_arriveid`, `fd_agpm_memo`, `fd_agpm_state`, `fd_agpm_fucardno`, `fd_agpm_fucardbank`, 
		`fd_agpm_fucardman`, `fd_agpm_fucardmobile`, `fd_agpm_listno`, `fd_agpm_datetime`, `fd_agpm_listid`, `fd_agpm_method`, `fd_agpm_isagentpay`)  SELECT fd_tfmglist_bkordernumber, '01', '$orderId', fd_tfmglist_money, 3, fd_tfmglist_sdcrpayfeemoney, fd_tfmglist_sdcragentfeemoney, fd_tfmglist_paycardid, 
		fd_tfmglist_authorid, fd_tfmglist_paytype, fd_tfmglist_paydate, fd_tfmglist_shoucardno, fd_tfmglist_shoucardbank, fd_tfmglist_shoucardman, fd_tfmglist_shoucardmobile, fd_tfmglist_current, fd_tfmglist_paymoney, fd_tfmglist_payfee, fd_tfmglist_payfeedirct, fd_tfmglist_money, fd_tfmglist_feebankid, fd_tfmglist_arrivemode, fd_tfmglist_arrivedate, fd_tfmglist_arriveid, fd_tfmglist_memo, fd_tfmglist_state, fd_tfmglist_fucardno, fd_tfmglist_fucardbank, fd_tfmglist_fucardman, fd_tfmglist_fucardmobile, fd_tfmglist_no, fd_tfmglist_paydate, fd_tfmglist_id, 
		'in', 0 FROM tb_transfermoneyglist WHERE fd_tfmglist_bkordernumber = '$orderId'";
				$db->query($query);
	}
	
	public function YiBaoPayFeedback($payResult, $orderId)
	{
		if($payResult["r1_Code"] == "1")
		{
			$db = new DB_test();
		
		$query = "update  tb_transfermoneyglist set fd_tfmglist_payrq ='00' ,fd_tfmglist_paydate = NOW(), fd_tfmglist_bkntno = '" . $payResult['r2_TrxId'] . "'  where fd_tfmglist_bkordernumber = '$orderId'";
				$db->query($query);
				
				$query = "update  tb_agentpaymoneylist set fd_agpm_payrq = '00', fd_agpm_bkntno = '" . $payResult['r2_TrxId'] . "'  where fd_agpm_bkordernumber = '$orderId'";
				$db->query($query);
				
			 $query = "select 1 from tb_cus_fenrunglist where 1 and  fd_frlist_bkordernumber = '".$orderId."' limit 1";
        if ($db->execute($query))
        {
            
        }else
        {
			$query = "SELECT fd_tfmglist_authorid AS authorid, fd_author_cusid AS cusid, fd_tfmglist_paycardid AS paycardid, fd_tfmglist_paydate AS paydate, fd_tfmglist_paymoney AS paymoney, fd_tfmglist_payfee AS payfee, fd_tfmglist_sdcrpayfeemoney AS sdcrpayfeemoney, fd_tfmglist_paytype AS paytype, fd_tfmglist_sdcrpayfeemoney FROM tb_transfermoneyglist WHERE fd_tfmglist_bkordernumber = '$orderId'";
			
			if ($db->execute($query)) 
			{
				$arr_info = $db->get_one($query);
				
				$cusfee = 0;
				$tfbfenrun = 0;
				
				$cusfeeResult = getcusfenrun :: get_cusfenrun($arr_info['cusid'], $arr_info['paytype'], $arr_info['payMoney'], $arr_info['fd_tfmglist_sdcrpayfeemoney'], $arr_info['fd_tfmglist_payfee'], 0, date("Y-m-d"), null, null);
				$cusfee = $cusfeeResult["cusfee"];
				$tfbfenrun = $arr_cusfee['tfbfenrun'];
				
				unset($dateArray);
				$dateArray['fd_frlist_authorid']       = $arr_info['authorid'];
				$dateArray['fd_frlist_cusid']          = $arr_info['cusid'];
				$dateArray['fd_frlist_paycardid']      = $arr_info['paycardid'];
				$dateArray['fd_frlist_paydate']        = $arr_info['paydate'];
				$dateArray['fd_frlist_paymoney']       = $arr_info['paymoney'];
				$dateArray['fd_frlist_payfee']         = $arr_info['payfee'];
				$dateArray['fd_frlist_cusfee']         = $cusfee;
				$dateArray['fd_frlist_bkordernumber'] = $orderId;
				$dateArray['fd_frlist_payrq']          = '00';
				$dateArray['fd_frlist_paytype']        = $arr_info['paytype'];
				$dateArray['fd_frlist_sdcrid']         = 3;
				$dateArray['fd_frlist_sdcrpayfeemoney']         = $arr_info['sdcrpayfeemoney'];
				$dateArray['fd_frlist_tfbfenrun']         = $tfbfenrun;

				$dateArray['fd_frlist_datetime']       = date("Y-m-d H:i:s");
				$db->insert("tb_cus_fenrunglist", $dateArray);
			}
			}
		}
	}
}

// ********需要清理********
function makeorderno($tablename, $fieldname, $preno = "pay") {
    $db = new DB_test();
    $db2 = new DB_test();
    $year = date("Y", mktime());
    $month = date("m", mktime());
    $day = date("d", mktime());

    $nowdate = $year . $month . $day;
    $query = "select fd_" . $fieldname . "_no as no from tb_" . $tablename . "   order by fd_" . $fieldname . "_no  desc";
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