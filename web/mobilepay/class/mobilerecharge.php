<?php
class MobileRecharge
{
	// 创建手机充值订单，20140728
	public function CreateOrder($msgBody, $authorId, $payType)
	{
		$now = time();
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始创建手机充值订单($now)");
		$arrPayCard = GetPayCalcuInfo :: readpaycardid($msgBody['paycardid'], $authorId);				// ******
$logger->debug("正在创建手机充值订单($now) : paycardid（" . $msgBody['paycardid'] . "），authorId（$authorId ），arrPayCard（" . print_r($arrPayCard, true) . "）");
		$payCardId = $arrPayCard['paycardid'];															// 刷卡器ID
		$cusId = $arrPayCard['cusid'];																	// 代理商ID
		
		$payfee = 0;
		
		// 利润 = 用户支付给通付宝的钱 - 通付宝从向上的进货价- 支付通道的费率
		$query = "SELECT (fd_recham_paymoney - fd_recham_costmoney) AS payfee 
				FROM tb_mobilerechamoney WHERE fd_recham_money = '" . $msgBody['rechargeMoney'] . "'";
		$db = new DB_test();
		$dataInDB = $db->get_all($query);
		if(is_array($dataInDB) && count($dataInDB) == 1)
		{
			$payfee = $dataInDB[0]['payfee'];
$logger->debug("正在创建手机充值订单($now) : 用户支付给通付宝的钱 - 通付宝从向上的进货价 = ($payfee)");
		}
		else
		{
$logger->error("正在创建手机充值订单($now) : 无法获取面额" . $msgBody['rechargeMoney'] . "元所对应的利润值");
		}
		
		$payTypeFee = 0;																				// 转账费率
		switch($payType)
		{
			case "YiBao":
				$payTypeFee = YiBaoPay :: GetPayFee($msgBody['payMoney']);
				break;
			case "upmp":
				$payTypeFee = BankPayInfoV3 :: GetPayFee($msgBody['payMoney']);
				break;
		}
		// ********
		$cusfeeResult = getcusfenrun :: get_cusfenrun($cusId, "mobilerecharge", $msgBody['payMoney'], 0, $payfee, 0, date("Y-m-d"), null, null);
		//$cusfeeResult = getcusfenrun :: get_cusfenrun($cusId, "mobilerecharge", $msgBody['payMoney'], $payTypeFee, $payfee, 0, date("Y-m-d"), null, null);
$logger->debug("正在创建手机充值订单($now) : cusId（$cusId ），cusfeeResult（" . print_r($cusfeeResult, true) . "）");
        $cusfee = $cusfeeResult["cusfee"];
		$cusfee = $cusfee > 0 ? $cusfee : 0;
$logger->debug("正在创建手机充值订单($now) : 通付宝的利润($payfee), 代理商的利润($cusfee)");

		$orderNumber = "tfbmrc" . date("YmdHiss") . mt_rand(1000, 9999);								// 创建通付宝订单号

		$listno = makeorderno("mobilerechargelist", "mrclist", "mrc");									// ******

		$sql = "INSERT INTO tb_mobilerechargelist(fd_mrclist_no, fd_mrclist_paycardid, fd_mrclist_cusid, 
				fd_mrclist_cusfee, fd_mrclist_authorid, fd_mrclist_paydate, 
				fd_mrclist_bkordernumber, fd_mrclist_sdcrid, fd_mrclist_payrq, fd_mrclist_paytypeid, 
				fd_mrclist_paytype, fd_mrclist_rechamoney, fd_mrclist_bkmoney, fd_mrclist_rechaphone, 
				fd_mrclist_paymoney, fd_mrclist_payfee, fd_mrclist_mobileprov, fd_mrclist_bankcardno, 
				fd_mrclist_state, fd_mrclist_date, fd_mrclist_datetime) VALUES 
				('$listno', '$payCardId', '$cusId', 
				'$cusfee', '$authorId', NOW(), 
				'$orderNumber', 3, '01', '1', 
				'mobilerecharge', '" . $msgBody['rechargeMoney'] . "', '" . $msgBody['payMoney'] . "', '" . $msgBody['rechargePhone'] . "', 
				'" . $msgBody['payMoney'] . "', '$payfee', '" . $msgBody['mobileProvince'] . "', '" . $msgBody['bankCardId'] . "', 
				'0', NOW(), NOW());";
$logger->debug("正在创建手机充值订单($now) : ($sql)");
		$sql = auto_charset($sql, 'utf-8', 'gbk');
		$db->query($sql);
		return $orderNumber;
	}
	
	// 处理易宝支付返回的数据
	public function YiBaoPayFeedback($payResult, $orderId)
	{
		$payResult["returnCode"] = $payResult['r1_Code'];
		$payResult["orderId"] = $payResult['r6_Order'];
		$payResult["transNumber"] = $payResult['r2_TrxId'];
		
		MobileRecharge :: PayFeedback($payResult, $orderId);
	}
	
	// 处理银联支付返回的数据
	public function UpmpPayFeedback($payResult, $orderId)
	{
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始处理银联支付($orderId)返回的数据");
		if($payResult == "cace2a1f74fa974808c185f17ef557de" || $payResult == "00")
		{
$logger->debug("正在处理银联支付($orderId)返回的数据 : 支付成功");
			$payResult = array();
			$payResult["returnCode"] = 1;
			MobileRecharge :: PayFeedback($payResult, $orderId);
		}
		else
		{
$logger->debug("结束处理银联支付($orderId)返回的数据 : 支付失败");
		}
	}
	
	/*
	* 处理支付通道返回的数据，20140728
	* orderId：通付宝订单号
	* payResult为数组，包括的信息有
	* orderId（银联或易宝通道返回的通付宝订单号）、returnCode（支付结果，1表示已经支付）、transNumber（银联或易宝通道返回的转账流水）
	*/
	public function PayFeedback($payResult, $orderId)
	{
		$now = time();
$logger = Logger::getLogger('mobilerecharge');
$logger->info("开始处理手机充值支付后返回数据($now) : 订单号($orderId), 返回的支付信息" . print_r($payResult, true));
		if(($payResult['orderId'] != "" && $orderId != "" && $payResult['orderId'] != $orderId) || ($payResult['orderId'] == "" && $orderId == ""))
		{
$logger->error("处理手机充值支付后返回数据出错($now) : 订单号有错误(" . $payResult['orderId'] . "不等于" . $orderId . ")");
			return;
		}
		$orderId = $orderId != "" ? $orderId : $payResult['orderId'];
$logger->debug("正在处理手机充值支付后返回数据($now) : 通付宝订单号($orderId)");
		
		if($payResult['returnCode'] != "1" && $payResult['transNumber'] == "") return;
		
		$db = new DB_test();
		if($payResult['returnCode'] == "1")
		{
			$query = "SELECT fd_mrclist_rechaphone, fd_mrclist_rechamoney, fd_mrclist_authorid, fd_mrclist_cusid, fd_mrclist_paycardid, fd_mrclist_bkmoney, fd_mrclist_payfee, fd_mrclist_cusfee, fd_mrclist_sdcrid FROM tb_mobilerechargelist 
			WHERE fd_mrclist_bkordernumber = '" . $orderId . "' AND fd_mrclist_payrq != '00'";
			$dataInDB = $db->get_all($query);
			if(is_array($dataInDB) && count($dataInDB) == 1)
			{
$logger->debug("正在处理手机充值支付后返回数据($now) : 通过通付宝订单号($orderId)获取数据" . print_r($dataInDB, true));
				// 给代理商分润
				$query = "SELECT 1 FROM tb_cus_fenrunglist WHERE fd_frlist_bkordernumber = '" . $orderId . "' LIMIT 1";
$logger->debug("正在处理手机充值支付后返回数据($now) : 判断是否需要添加数据到tb_cus_fenrunglist表 " . $query);
				$hasShareInterest = $db->execute($query);
				if(!$hasShareInterest)
				{
$logger->debug("正在处理手机充值支付后返回数据($now) : 需要添加数据到tb_cus_fenrunglist表");
					$query = "INSERT INTO tb_cus_fenrunglist (fd_frlist_authorid, fd_frlist_cusid, fd_frlist_paycardid, fd_frlist_paydate, fd_frlist_paymoney, fd_frlist_payfee, fd_frlist_cusfee, fd_frlist_bkordernumber, fd_frlist_payrq, fd_frlist_paytype, fd_frlist_datetime, fd_frlist_ifjsfenrun, fd_frlist_sdcrid) VALUES (" . $dataInDB[0]['fd_mrclist_authorid'] . ", " . $dataInDB[0]['fd_mrclist_cusid'] . ", '" . $dataInDB[0]['fd_mrclist_paycardid'] . "', NOW(), " . $dataInDB[0]['fd_mrclist_bkmoney'] . ", " . $dataInDB[0]['fd_mrclist_payfee'] . ", " . $dataInDB[0]['fd_mrclist_cusfee'] . ", '" . $orderId . "', '00', 'mobilerecharge', NOW(), 0, '" . $dataInDB[0]['fd_mrclist_sdcrid'] . "');";
					$db->query($query);
$logger->debug("正在处理手机充值支付后返回数据($now) : 代理商分润时执行的SQL语句" . $query);
				}
				else
				{
$logger->debug("正在处理手机充值支付后返回数据($now) : 代理商分润时执行的SQL语句");
				}
				
				$data = array('FUNC' => 'MOBILE_RECHARGE', 
							'phone' => $dataInDB[0]['fd_mrclist_rechaphone'], 
							'money' => $dataInDB[0]['fd_mrclist_rechamoney'], 
							'orderId' => $orderId);
$logger->info("完成处理手机充值支付后返回数据($now) : 发起充值 : " . print_r($data, true));
				AsyncCall($data);
			}
			else
			{
$logger->error("完成处理手机充值支付后返回数据($now) : 通过通付宝订单号($orderId)获取的数据有误 : " . print_r($dataInDB, true));
			}
		}
		
		// 返回的数据回写进通付宝数据库
		if($payResult['returnCode'] == "1")
		{
			if($payResult['transNumber'] != "")
			{																				// 支付成功，易宝交易号不为空
				$query = "UPDATE tb_mobilerechargelist SET fd_mrclist_bkntno = '" . $payResult['transNumber'] . "', fd_mrclist_payrq = '00' WHERE fd_mrclist_bkordernumber = '" . $orderId . "'";
			}
			else
			{																				// 支付成功，易宝交易号为空
				$query = "UPDATE tb_mobilerechargelist SET fd_mrclist_payrq = '00' WHERE fd_mrclist_bkordernumber = '" . $orderId . "'";
			}
		}
		else if($payResult['transNumber'] != "")
		{																					// 支付不成功，易宝交易号不为空
			$query = "UPDATE tb_mobilerechargelist SET fd_mrclist_bkntno = '" . $payResult['transNumber'] . "' WHERE fd_mrclist_bkordernumber = '" . $orderId . "'";
		}

$logger->debug("正在处理手机充值支付后返回数据($now) : query($query)");
		if($query != "") $db->query($query);
$logger->debug("正在处理手机充值支付后返回数据($now) : 通付宝订单号($orderId)回写进通付宝数据库");
		
	}
}

// 异步调用
function AsyncCall($data)
{
	global $weburl;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $weburl . "sever/AsyncInterface.php ");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	
	curl_exec($ch);
	curl_close($ch);
}

// ********
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