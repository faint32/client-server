<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

require_once ("../third_api/upmp/BankPayInfoV3.php");
require_once ("../class/mobilerecharge.php");
require_once ("../third_api/yibao/YiBaoPay.php");
require_once ("../third_api/ofpay/ofpayV3.class.php");

class ApiMobileRechargeInfoV2 extends TfbxmlResponse
{
	// 读取充值面额，20140728
	public function ReadPerValue()
	{
		$query = "SELECT fd_recham_id AS rechaMoneyid, fd_recham_money AS rechamoney, 
				fd_recham_paymoney AS rechapaymoney, fd_recham_isdefault AS rechaisdefault, 
				fd_recham_memo AS rechamemo FROM tb_mobilerechamoney WHERE fd_recham_active = '1' 
				ORDER BY fd_recham_money";
		$db = new DB_test();
		$db->query($query);
		
		$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "恭喜您,读取充值面额成功!");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
        $arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// 判断是否可以充值，传入参数：手机号、面额
	public function CanRecharge()
	{
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始判断是否可以充值");
		$phone = trim($this->arr_body["phone"]);
		$money = trim($this->arr_body["money"]);
		
		if($phone == "" || $money == "")	ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '用户输入信息不完整'));
		
		$canRecharge = OfpayV3 :: CanMobileRecharge($phone, $money);
		$retcode = "0";
		if($canRecharge)
			$arr_message = array("result" => "success", "message" => "可以充值");
		else
			$arr_message = array("result" => "fail", "message" => "该面值暂不可以充值");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
$logger->debug("判断是否可以充值的结果" . print_r($returnvalue, true));
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	// 请求银联交易码，20140729
	public function RequestTransNumber()
	{
		$now = time();
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始请求银联交易码($now)");
		$authorId = trim($this->arr_channelinfo['authorid']);											// 用户ID
		if($authorId == "") ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '用户信息异常'));
		
		$msgBody = $this->arr_body;
		foreach($msgBody as $key => $value)
		{
			$msgBody[$key] = trim($value);
		}
		
		$msgBody["rechargeMoney"] = $msgBody["rechamoney"];
		$msgBody["payMoney"] = $msgBody["rechapaymoney"];
		$msgBody["rechargePhone"] = $msgBody["rechamobile"];
		$msgBody["bankCardId"] = $msgBody["rechabkcardno"];
		
		// 必填的字段：充值金额、实际支付金额、充值手机号、银行卡号
		$requiredField = array("rechargeMoney", "payMoney", "rechargePhone", "bankCardId");
		
		foreach($requiredField as $key => $value)
		{
			if($msgBody[$value] == "")	ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '用户输入信息不完整'));
		}
		$msgBody['mobileProvince'] = $msgBody['rechamobileprov'];
$logger->info("正在请求银联交易码($now) : 输入的信息包括" . print_r($msgBody, true));
		
		$orderId = MobileRecharge :: CreateOrder($msgBody, $authorId, "upmp");
$logger->info("正在请求银联交易码($now) : 通付宝订单号($orderId)");
		
		$transNumber = BankPayInfoV3 :: RequestBankTransNumber($orderId, $msgBody["payMoney"], $msgBody["bankCardId"]);
$logger->info("正在请求银联交易码($now), 返回的银联交易码($transNumber)");
		if($transNumber == "") ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '请求交易码失败，请稍后再试'));
		
		$query = "UPDATE tb_mobilerechargelist SET fd_mrclist_bkntno = '$transNumber' WHERE fd_mrclist_bkordernumber = '" . $orderId . "'";
		$db = new DB_test();
		$db->query($query);
		
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "请求交易码成功");
		
		$arr_msg['msgbody']['bkntno'] = $transNumber;
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	// 银联支付完成后，检查订单状态
	public function CheckTransStatus()
	{
		$now = time();
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始银联支付完成后，检查订单状态($now)");
		$msgBody = $this->arr_body;
		$transNumber = trim($msgBody['bkntno']);
		$result = trim($msgBody['result']);
		
		if($transNumber == "") ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '账单信息不能为空'));
$logger->info("正在银联支付完成后，检查订单状态($now) : transNumber($transNumber), result($result)");
		$query = "SELECT fd_mrclist_bkordernumber AS orderId, DATE_FORMAT(fd_mrclist_date, '%Y%m%d') AS orderTime FROM tb_mobilerechargelist 
				WHERE fd_mrclist_bkntno = '$transNumber'";
$logger->debug("正在银联支付完成后，检查订单状态($now) : query($query)");
		$db = new DB_test();
		$dataInDB = $db->get_all($query);
		if(!is_array($dataInDB) || count($dataInDB) != 1)
		{
$logger->error("完成银联支付完成后，检查订单状态($now) : 数据($query)有误(" . print_r($dataInDB, true) . ")");
			ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '账单信息异常'));
		}
		$orderId = $dataInDB[0]['orderId'];
		$orderTime = $dataInDB[0]['orderTime'];
$logger->debug("正在银联支付完成后，检查订单状态($now) : orderId($orderId), orderTime($orderTime)");
		if($orderId == "" || $orderTime == "")
		{
$logger->error("完成银联支付完成后，检查订单状态($now) : 订单号异常 orderId($orderId), orderTime($orderTime)");
			ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '订单号异常'));
		}
		$payResult = BankPayInfoV3 :: GetTransStatus($orderId, $orderTime);
$logger->debug("正在银联支付完成后，检查订单状态($now) : payResult(" . $payResult . ")");
		$payResult = md5("" . (string)$payResult . "");
		if($payResult == "cace2a1f74fa974808c185f17ef557de")
		{																								// bankReturnInfo为00
			$retcode = 0;
			$arr_message = array ("result" => "success", "message" =>  "支付成功，正在为您充值中!");
		}
		else
		{
			$arr_message = array ("result" => "fail", "message" =>  "支付失败，如有疑问，请联系客服!");
		}
		MobileRecharge :: UpmpPayFeedback($payResult, $orderId);
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
$logger->info("完成银联支付完成后，检查订单状态($now) : msgbody" . print_r($arr_msg['msgbody'], true));
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// 使用信用卡进行手机充值，20140728
	public function PayWithCreditCard()
	{
		$now = time();
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始使用信用卡进行手机充值($now)");
		$authorId = trim($this->arr_channelinfo['authorid']);											// 用户ID

		$msgBody = $this->arr_body;
		foreach($msgBody as $key => $value)
		{
			$msgBody[$key] = trim($value);
		}
		
		// 必填的字段：充值金额、实际支付金额、充值手机号、银行卡号、银行ID、身份证号码、
		//				信用卡持有者手机号、信用卡持有者姓名、有效期（年）、有效期（月）、信用卡背面的3位或4位cvv2码
		$requiredField = array("rechargeMoney", "payMoney", "rechargePhone", "bankCardId", "bankId", "manCardId", 
							"payPhone", "manName", "expireYear", "expireMonth", "cvv");
							
		foreach($requiredField as $key => $value)
		{
			if($msgBody[$value] == "")	ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '用户输入信息不完整'));
		}
		
$logger->info("正在使用信用卡进行手机充值($now) : 输入的信息包括" . print_r($msgBody, true));

		$orderId = MobileRecharge :: CreateOrder($msgBody, $authorId, "YiBao");
$logger->info("正在使用信用卡进行手机充值($now) : 通付宝订单号($orderId)");
		$cardInfo = array(
			"orderId" => $orderId,
			"money" => $msgBody['payMoney'],
			"personId" => $msgBody['manCardId'],
			"bankId" => $msgBody['bankId'],
			"phone" => $msgBody['payPhone'],
			"name" => $msgBody['manName'],
			"cardId" => $msgBody['bankCardId'],
			"expireYear" => $msgBody['expireYear'],
			"expireMonth" => $msgBody['expireMonth'],
			"cvv" => $msgBody['cvv'],
		);
		$payResult = YiBaoPay :: PayWithCreditCard($cardInfo);
$logger->info("完成易宝支付($now), 返回的结果" . print_r($payResult, true));
		MobileRecharge :: YiBaoPayFeedback($payResult, $orderId);
		if($payResult['r1_Code'] == "1")
		{
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "支付成功，正在为您充值中");
		}
		else if($payResult['r1_Code'] == "81100" || $payResult['r1_Code'] == "81201" || $payResult['r1_Code'] == "81202" || $payResult['r1_Code'] == "81203")
		{
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "交易存在风险，需短信验证码回复校验");
			$arr_msg['msgbody']['verifyCode'] = 1;
			$arr_msg['msgbody']['orderId'] = $orderId;
		}
		else
		{
			$retcode = "200";
			$arr_message = array("result" => "fail", "message" => $payResult['errorMsg']);
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
$logger->info("完成使用信用卡进行手机充值($now) : 返回的信息包括" . print_r($arr_msg['msgbody'], true));
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// 使用验证码进行验证后充值
	public function PayWithVerifyCode()
	{
		$now = time();
$logger = Logger::getLogger('mobilerecharge');
$logger->debug("开始使用验证码进行验证后充值($now)");
		$authorId = trim($this->arr_channelinfo['authorid']);											// 用户ID

		$msgBody = $this->arr_body;
		
		foreach($msgBody as $key => $value)
		{
			$msgBody[$key] = trim($value);
		}
		
		// 必填的字段
		$requiredField = array(
			"orderId", 																					// 通付宝订单号
			"verifyCode"																				// 验证码
		);
		foreach($requiredField as $key => $value)
		{
			if($msgBody[$value] == "")	ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '用户输入信息不完整'));
		}
		
$logger->info("正在使用验证码进行验证后充值($now) : 输入的信息包括" . print_r($msgBody, true));

		$cardInfo = array(
			"orderId" => $msgBody['orderId'],
			"verifyCode" => $msgBody['verifyCode'],
		);
		$payResult = YiBaoPay :: PayWithVerifyCode($cardInfo);
$logger->info("完成易宝支付($now), 返回的结果" . print_r($payResult, true));
		MobileRecharge :: YiBaoPayFeedback($payResult, $msgBody['orderId']);
		if($payResult['r1_Code'] == "1")
		{
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "支付成功，正在为您充值中");
		}
		else
		{
			$retcode = "200";
			$arr_message = array("result" => "fail", "message" => $payResult['errorMsg']);
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
$logger->info("完成使用验证码进行验证后充值($now) : 返回的信息包括" . print_r($arr_msg['msgbody'], true));
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
	
	// 获取手机充值历史，20140728
	public function ReadMobileRechangeList()
	{
		$authorId = trim($this->arr_channelinfo['authorid']);											// 用户ID
		if($authorId == "") ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '用户信息异常'));
		
		$pageStart = trim($this->arr_body['msgstart']) + 0;
		$pageCount = trim($this->arr_body['msgdisplay']) + 0;
		$pageCount = $pageCount > 0 ? $pageCount : 8;
		
		$query = "SELECT fd_mrclist_rechamoney AS rechamoney, fd_mrclist_paymoney AS rechapaymoney, 
				fd_mrclist_mobileprov AS rechamobileprov,  fd_mrclist_bankcardno AS rechabkcardno, 
				fd_mrclist_paydate AS rechadatetime, fd_mrclist_rechaphone AS rechamobile 
				FROM tb_mobilerechargelist 
				WHERE fd_mrclist_authorid = '$authorId' AND fd_mrclist_payrq = '00' 
				ORDER BY fd_mrclist_paydate DESC LIMIT $pageStart, $pageCount";
				
		$db = new DB_test();
		$db->query($query);
		$msgBody = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
		
		if(!$msgBody)
		{
			$arr_message = array ("result" => "fail", "message" => "您还未使用过手机充值业务!");
			$retcode = "200";
		}
		else
		{
			$arr_message = array ("result" => "success", "message" => "");
			$retcode = "0";
		}
		
		$msgBody['msgbody']['result'] = $arr_message['result'];
        $msgBody['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array("msgbody" => $msgBody['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
}