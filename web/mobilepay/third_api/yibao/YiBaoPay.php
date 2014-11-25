<?php
class YiBaoPay
{
	static $MODE = "FORMAL";														// 当前模式：TEST、FORMAL

	// 通付宝商户编号和密钥
	static $merId = "10012409428";
	static $key = "4419Vkr4v1r21CH3HK8MNg32M85y27K69Dei4155v9ynN0Pl00XR8kNy15a9";
	static $url = "https://www.yeepay.com/app-merchant-proxy/command.action";		// 进账
	
	static $feedbackUrl = "http://www.tfbpay.cn/mobilepay/third_api/yibao/feedback.php";
	
	// 获取转账手续费
	public function GetPayFee($money)
	{
		return $money * 0.0044;
	}
	
	// 直接使用信用卡支付
	public function PayWithCreditCard($cardInfo)
	{
		$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->info("开始易宝支付($now) : 传入的参数为" . print_r($cardInfo, true));
		$paramList = array(
			"p0_Cmd" => "EposSale",													// 业务类型，固定值
			"p1_MerId" => YiBaoPay :: $merId,										// 商户在易宝支付系统的唯一身份标识
			"p2_Order" => $cardInfo["orderId"],										// 通付宝订单号
			"p3_Amt" => $cardInfo["money"],											// 消费金额
			"p4_Cur" => "CNY",														// 交易币值，固定值
			"p5_Pid" => "TEST",														// 商品名称，可为空
			"p8_Url" => YiBaoPay :: $feedbackUrl,									// 回调地址
			"pa_CredType" => "IDCARD",												// 证件类型，目前固定为身份证
			"pb_CredCode" => $cardInfo["personId"],									// 身份证号
			"pd_FrpId" => $cardInfo["bankId"],										// 银行编码
			"pe_BuyerTel" => $cardInfo["phone"],									// 消费者手机号
			"pf_BuyerName" => auto_charset($cardInfo["name"], 'utf-8', 'gbk'),		// 消费者姓名，易宝支付平台统一使用GBK/GB2312 编码方式
			"pt_ActId" => $cardInfo["cardId"],										// 信用卡卡号
			"pa0_Mode" => "1",														// 是否短信通知持卡人，可为空
			"pa2_ExpireYear" => $cardInfo["expireYear"],							// 有效期（年）
			"pa3_ExpireMonth" => $cardInfo["expireMonth"],							// 有效期（月）
			"pa4_CVV" => $cardInfo["cvv"],											// 信用卡背面的3位或4位cvv2码
			"pa5_BankBranch" => "",													// 开户行，可为空
			"pa6_CardType" => "",													// 信用卡类型，可为空，例如VISA
			"prisk_TerminalCode" => YiBaoPay :: $merId . "001",						// 终端号，可为空
			"prisk_Param" => ""														// 风险扩展参数，可为空
		);
		$hmac = YiBaoPay :: GetHmacString($paramList);
$logger->debug("进行易宝支付($now) : 生成的签名为($hmac)");
		$paramList["hmac"] = $hmac;
		$paramList["pr_NeedResponse"] = "1";										// 需要应答，固定值
		$url = YiBaoPay :: BuildQueryString($paramList);
$logger->debug("进行易宝支付($now) : url($url)");
		if(YiBaoPay :: $MODE != "TEST")
		{
			$returnValue = YiBaoPay :: Request($url);
		}
		else
		{
			$returnValue = YiBaoPay :: RandomPayWithCreditCardResult($cardInfo["orderId"]);
		}
		$returnValue = YiBaoPay :: Feedback($returnValue, $cardInfo["phone"], $cardInfo["orderId"]);
$logger->info("结束易宝支付($now) : 返回的结果为" . print_r($returnValue, true) . "\r\n\r\n\r\n\r\n\r\n");
		return $returnValue;
	}
	
	// 使用短信验证码支付
	public function PayWithVerifyCode($cardInfo)
	{
		$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->info("开始使用短信验证码支付($now) : " . print_r($cardInfo, true));
		$paramList = array(
			"p0_Cmd" => "EposVerifySale",											// 业务类型，固定值
			"p1_MerId" => YiBaoPay :: $merId,										// 商户在易宝支付系统的唯一身份标识
			"p2_Order" => $cardInfo["orderId"],										// 通付宝订单号
			"pb_VerifyCode" => $cardInfo["verifyCode"]								// 验证码
		);
		$hmac = YiBaoPay :: GetHmacString($paramList);
$logger->debug("正在使用短信验证码支付($now) : 生成的签名为($hmac)");
		$paramList["hmac"] = $hmac;
		$url = YiBaoPay :: BuildQueryString($paramList);
$logger->debug("正在使用短信验证码支付($now) : url($url)");
		if(YiBaoPay :: $MODE != "TEST")
		{
			$returnValue = YiBaoPay :: Request($url);
		}
		else
		{
			$returnValue = YiBaoPay :: RandomPayWithVerifyCodeResult($cardInfo["orderId"]);
		}
		$returnValue = YiBaoPay :: Feedback($returnValue, "", "");
$logger->debug("结束使用短信验证码支付($now) : 返回" . print_r($returnValue, true));
		return $returnValue;
	}
	
	// 临时供测试使用，退款
	public function AskForRefund($cardInfo)
	{
		$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->info("start AskForRefund($now) : " . print_r($cardInfo, true));
		$paramList = array(
			"p0_Cmd" => "RefundOrd",												// 业务类型，固定值
			"p1_MerId" => YiBaoPay :: $merId,										// 商户在易宝支付系统的唯一身份标识
			"p2_Order" => $cardInfo["orderId"],										// 发起退款时的请求号
			"pb_TrxId" => $cardInfo["yibaoOrderId"],								// 易宝支付平台产生的交易流水号
			"p3_Amt" => $cardInfo["money"],											// 退款金额
			"p4_Cur" => "CNY",														// 交易币值，固定值
			"p5_Desc" => "",														// 退款说明
			"pv_Ver" => ""															// 版本号
		);
		$hmac = YiBaoPay :: GetHmacString($paramList);
$logger->debug("process AskForRefund($now) : hmac($hmac)");
		$paramList["hmac"] = $hmac;
		$url = YiBaoPay :: BuildQueryString($paramList);
$logger->debug("process AskForRefund($now) : url($url)");
		$return = YiBaoPay :: Request($url);
$logger->debug("process AskForRefund($now) : return($return)");
		return $return;
	}
	
	// 参数值按照签名顺序拼接产生字符串，然后再用商户密钥生成签名数据
	// 该函数由易宝提供
	public function GetHmacString($paramList)
	{
		$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->debug("开始生成签名($now) : " . print_r($paramList, true));
		$data = "";
		foreach($paramList as $key => $value)
		{
			$data .= $value;
		}
$logger->debug("正在生成签名($now) : 所有参数拼成明文($data)");
		$key = YiBaoPay :: $key;
		
		$key = iconv("GB2312", "UTF-8", $key);
		$data = iconv("GB2312", "UTF-8", $data);
		
		$b = 64;
		if(strlen($key) > $b)
			$key = pack("H*", md5($key));
			
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad("", $b, chr(0x36));
		$opad = str_pad("", $b, chr(0x5c));
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		$returnValue = md5($k_opad . pack("H*", md5($k_ipad . $data)));
$logger->debug("生成签名($now) : ($returnValue)");
		return $returnValue;
	}
	
	// 根据参数数组获取url地址
	public function BuildQueryString($paramList)
	{
		$queryString = "";
		if(is_array($paramList))
		{
			foreach($paramList as $key => $value)
			{
				$queryString .= urlencode($key) . "=" . urlencode($value) . "&";
			}
		}
		if($queryString != "")
			$queryString = substr($queryString, 0, -1);
		return YiBaoPay :: $url . "?" . $queryString;
	}
	
	// 发起向易宝的转账请求
	public function Request($url)
	{
		$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->info("开始发起请求($now) : url($url)");
		$maxTryCount = 3;
		$tryCount = 0;
		while($tryCount < $maxTryCount && !$result)
		{
			$tryCount++;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		
		if($result)
$logger->info("结束请求($now) : 经过了($tryCount)次尝试后，获取到的数据(" . print_r($result, true) . ")");
		else
$logger->info("结束请求($now) : 经过了($tryCount)次尝试后，url($url)没有返回数据");
		
		$result = auto_charset($result, 'gbk', 'utf-8');
		return $result;
	}
	
	// 对易宝返回的结果
	// 进行签名验证
	// 如果需要发短信验证，调用发送短信接口
	public function Feedback($rawData, $phone, $orderId, $asyncReturn = false)
	{
		$now = time();
$logger = Logger::getLogger('yibaopay');
$logger->info("获取转账结果($now) : 返回的结果：($rawData), 手机($phone), 订单号($orderId)");
		$returnArray = array();
		if(!is_array($rawData))
		{
			$rawData = explode("\n", $rawData);
			for($i = 0; $i < count($rawData); $i++)
			{
				$item = trim($rawData[$i]);
				if(strlen($item) == 0) continue;
				$itemArr = explode("=", $item);
				if(count($itemArr) != 2) continue;
				$returnArray[$itemArr[0]] = trim($itemArr[1]);
			}
		}
		else
		{
			$returnArray = $rawData;
		}
		
		if($returnArray["hmac"] == "")
		{
$logger->error("分析转账结果($now) : 签名sign($sign)为空");
			return array("r1_Code" => -1);			
		}
		
		// 验证返回结果
		if(!$asyncReturn)
		{
			$paramList = array($returnArray["r0_Cmd"], $returnArray["r1_Code"], $returnArray["r2_TrxId"], $returnArray["r6_Order"], $returnArray["ro_BankOrderId"]);
		}
		else
		{
			$paramList = array($returnArray["p1_MerId"], $returnArray["r0_Cmd"], $returnArray["r1_Code"], $returnArray["r2_TrxId"], $returnArray["r3_Amt"], $returnArray["r4_Cur"], $returnArray["r5_Pid"], $returnArray["r6_Order"], $returnArray["r7_Uid"], $returnArray["r8_MP"], $returnArray["r9_BType"]);
		}
		$sign = YiBaoPay :: GetHmacString($paramList);
		if(YiBaoPay :: $MODE != "TEST" && $sign != $returnArray["hmac"])
		{
$logger->error("分析转账结果($now) : 签名sign($sign)错误，无法匹配(" . print_r($returnArray, true) . ")");
			return array("r1_Code" => -1, "errorMsg" => "签名验证失败");
		}
		
		// 如果返回的状态码为81202，则需要通付宝把验证码用短信发送给用户
		if($returnArray["r1_Code"] == '81202')
		{
$logger->info("分析转账结果($now) : 需要发送手机($phone)验证码" . $returnArray["errorMsg"]);
			if($phone != "" && $returnArray["errorMsg"] != "")
			{
				$returnArray['r6_Order'] = $orderId;
				$data = array(
					'FUNC' => 'SEND_SMS', 
					'phone' => $phone, 
					'message' => "您的验证码是：" . $returnArray["errorMsg"] . "，仅用于通付宝支付业务，电话400-6868-956。如需帮助请联系客服。"
				);
				
				// 调用接口把短信发给用户
				global $weburl;
				$url = $weburl . "sever/AsyncInterface.php";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				
				curl_exec($ch);
				curl_close($ch);
$logger->info("分析转账结果($now) : 已经发送手机验证码");
			}
			else
			{
$logger->error("分析转账结果($now) : 不能发送验证码，phone($phone)，verifyCode(" . $returnArray["errorMsg"] . ")");
			}
		}
		return $returnArray;
	}
	
	
	// 测试用，在测试模式下随机虚拟出一些返回结果
	public function RandomPayWithCreditCardResult($orderId)
	{
		$PayWithCreditCard = array();
		$PayWithCreditCard[] = "r0_Cmd=EposSale
r1_Code=1
errorMsg=
r2_TrxId=714235266233371I
r6_Order=" . $orderId . "
ro_BankOrderId=
hmac=40266eb38ff4c793f58d577ba0140890";																// 直接支付成功的情况
		$PayWithCreditCard[] = "r0_Cmd=EposSale
r1_Code=10805
errorMsg=该笔交易可能存在风险，扣款失败
r2_TrxId=
r6_Order=
ro_BankOrderId=
hmac=0be147d5b35de0d181c0d67141bfbaf7";																// 交易失败的情况
		$PayWithCreditCard[] = "r0_Cmd=EposSale
r1_Code=81202
errorMsg=519
r2_TrxId=
r6_Order=
ro_BankOrderId=
hmac=c833cdd0b2079ce4dc557bd2178f4acf";																// 需要短信验证的情况
		return $PayWithCreditCard[1];
		return $PayWithCreditCard[mt_rand() % 2];
	}
	
	// 测试用，在测试模式下随机虚拟出一些返回结果
	public function RandomPayWithVerifyCodeResult($orderId)
	{
		$PayWithVerifyCode = array();
		$PayWithVerifyCode[] = "r0_Cmd=EposVerifySale
r1_Code=81205
errorMsg=验证码已无效
r2_TrxId=
r6_Order=" . $orderId . "
ro_BankOrderId=
hmac=38c6db01dc55f556ae918a9dce8279f1";																// 失败的情况
		$PayWithVerifyCode[] = "r0_Cmd=EposVerifySale
r1_Code=1
errorMsg=交易成功
r2_TrxId=315254269515852I
r6_Order=" . $orderId . "
ro_BankOrderId=000000
hmac=5c7b7674b27b2027e8e0ff557b3a5079";																// 成功的情况
		return $PayWithVerifyCode[1];
		//return $PayWithVerifyCode[mt_rand() % 2];
	}
}