<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

require_once ("../class/transfermoney.php");
require_once ("../third_api/yibao/YiBaoPay.php");

class ApiTransferMoney extends TfbxmlResponse
{
	public function TransferWithCreditCard()
	{
$logger = Logger::getLogger("transfermoney");
$logger->debug("开始使用信用卡进行转账付款");
		$authorId = trim($this->arr_channelinfo["authorid"]);											// 用户ID

		$requestBody = $this->arr_body;
		foreach($requestBody as $key => $value)
		{
			$requestBody[$key] = trim($value);
		}
		
		// 必填的字段：实际支付金额、转账金额、收款方卡号、收款方银行名、收款人手机号、
		//				收款方人姓名、付款方银行卡号、付款方银行编码、付款方身份证号码、
		//				付款方手机号、付款方人姓名、有效期（年）、有效期（月）、信用卡背面的3位或4位cvv2码
		$requiredField = array("payMoney", "transferMoney", "receiveBankCardId", "receiveBankName", "receivePhone", 
							"receivePersonName", "sendBankCardId", "sendBankCode", "personCardId", 
							"sendPhone", "sendPersonName", "expireYear", "expireMonth", "cvv");
							
		foreach($requiredField as $key => $value)
		{
			if($requestBody[$value] == "")	ErrorReponse :: reponError(array("retcode" => "200", "retmsg" => "用户输入信息不完整"));
		}
		
$logger->info("正在使用信用卡进行转账付款 : 输入的信息包括" . print_r($requestBody, true));

		$orderId = TransferMoney :: CreateOrder($requestBody, $authorId, "YiBao");
		TransferMoney :: CreateAgentPayOrder($orderId);
$logger->info("正在使用信用卡进行转账付款 : 生成的通付宝订单号($orderId)");
		$cardInfo = array(
			"orderId" => $orderId,
			"money" => $requestBody["payMoney"],
			"personId" => $requestBody["manCardId"],
			"bankId" => $requestBody["bankId"],
			"phone" => $requestBody["payPhone"],
			"name" => $requestBody["manName"],
			"cardId" => $requestBody["bankCardId"],
			"expireYear" => $requestBody["expireYear"],
			"expireMonth" => $requestBody["expireMonth"],
			"cvv" => $requestBody["cvv"],
		);
		$payResult = YiBaoPay :: PayWithCreditCard($cardInfo);
$logger->info("完成易宝支付, 返回的结果" . print_r($payResult, true));
		TransferMoney :: YiBaoPayFeedback($payResult, $orderId);
		if($payResult["r1_Code"] == "1")
		{
			$returnCode = "0";
			$returnMessage = array("result" => "success", "message" => "支付成功，正在为您转账中");
		}
		else if($payResult["r1_Code"] == "81100" || $payResult["r1_Code"] == "81201" || $payResult["r1_Code"] == "81202" || $payResult["r1_Code"] == "81203")
		{
			$returnCode = "0";
			$returnMessage = array("result" => "success", "message" => "交易存在风险，需短信验证码回复校验");
			$responseBody["msgbody"]["verifyCode"] = 1;
			$responseBody["msgbody"]["orderId"] = $orderId;
		}
		else
		{
			$returnCode = "200";
			$returnMessage = array("result" => "fail", "message" => $payResult["errorMsg"]);
		}
		
		$responseBody["msgbody"]["result"] = $returnMessage["result"];
		$responseBody["msgbody"]["message"] = $returnMessage["message"];
$logger->info("完成使用信用卡进行转账付款 : 返回给用户的信息" . print_r($responseBody["msgbody"], true));
		$returnValue = array("msgbody" => $responseBody["msgbody"]);
		$returnValue = TfbxmlResponse :: ResponsetoApp($returnCode, $returnValue);
        return $returnValue;
	}
	
	// 使用验证码进行验证后充值
	public function PayWithVerifyCode()
	{
		$now = time();
$logger = Logger::getLogger('transfermoney');
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
		TransferMoney :: YiBaoPayFeedback($payResult, $msgBody['orderId']);
		if($payResult['r1_Code'] == "1")
		{
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "支付成功，正在为您转账中");
		}
		else
		{
			$retcode = "200";
			$arr_message = array("result" => "fail", "message" => $payResult['errorMsg']);
		}
		
		$arr_msg["msgbody"]['result'] = $arr_message['result'];
		$arr_msg["msgbody"]['message'] = $arr_message['message'];
$logger->info("完成使用验证码进行验证后充值($now) : 返回的信息包括" . print_r($arr_msg["msgbody"], true));
		$returnvalue = array("msgbody" => $arr_msg["msgbody"]);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
	}
}