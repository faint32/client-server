<?php
class OfpayV3
{
	static $MODE = "FORMAL";																// 当前模式：TEST、FORMAL
	static $merchantId = "A942987";
	static $merchantPassword = "c7e8747acb478078e1b85db359df8f96";							// md5("tfbao20140603")
	
	public static function CanMobileRecharge($phone, $money)
	{
		$money += 0;
		$url = "http://api2.ofpay.com/telcheck.do?phoneno=$phone&price=$money&userid=" . OfpayV3 :: $merchantId;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		if(strpos($output, "1#") === false)
			$result = false;
		else
			$result = true;
$logger = Logger::getLogger('ofpay');
$logger->debug("判断出手机号($phone)" . ($result ? "能" : "不能") . "充值($money)元 : url($url), output($output) : 调试信息" . print_r(curl_getinfo($ch), true));
		curl_close($ch);		
		return $result;
	}
	
	public static function MobileRecharge($data)
	{
$logger = Logger::getLogger("ofpay");
$logger->info("开始手机充值 : " . print_r($data, true));
		$now = date("YmdHis");
		$data['money'] = $data['money'] + 0;
		$hmac = strtoupper(md5(OfpayV3 :: $merchantId . OfpayV3 :: $merchantPassword . "140101" . $data['money'] . $data['orderId'] . $now . $data['phone'] . "OFCARD"));
		$url = "http://api2.ofpay.com/onlineorder.do?userid=" . OfpayV3 :: $merchantId . "&userpws=" . OfpayV3 :: $merchantPassword . "&cardid=140101&cardnum=" . $data['money'] . "&sporder_id=" . $data['orderId'] . "&sporder_time=$now&game_userid=" . $data['phone'] . "&md5_str=$hmac&ret_url=&version=6.0";
		$xml = OfpayV3 :: Request($url);

		if($xml)
		{
$logger->info("完成手机充值，从($url)返回的结果" . print_r($xml, true));
			OfpayV3 :: Feedback($xml, $data['orderId']);
		}
		else
		{
$logger->error("手机充值不成功");
		}
	}
	
	public static function Feedback($result, $orderId)
	{
$logger = Logger::getLogger("ofpay");
$logger->info("开始分析手机充值返回的结果，订单号($orderId)");
		// if(isset($result->retcode) && $result->retcode == 1 && isset($result->game_state) && $result->game_state == 1)
		if(isset($result->retcode) && $result->retcode == 1)
		{
$logger->info("正在分析手机充值返回的结果 : 通付宝订单号($orderId) 充值成功");
			$db = new DB_test();
			$query = "UPDATE tb_mobilerechargelist SET fd_mrclist_payrq = '00', fd_mrclist_ofstate = 1 
					WHERE fd_mrclist_bkordernumber = '". $orderId . "'";
$logger->debug("完成手机充值 : 回写状态进通付宝数据库($query)");
			$db->query($query);
		}
		else
		{
$logger->error("完成手机充值 : 充值失败");
		}
	}
	
	private static function Request($url)
	{
$logger = Logger::getLogger("ofpay");
$logger->info("开始手机充值 : url" . $url);
		$maxTryCount = 3;
		$tryCount = 0;
		while($tryCount < $maxTryCount && !$output && OfpayV3 :: $MODE == "FORMAL")
		{
			$tryCount++;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
$logger->debug("尝试手机充值: url($url), output($output) : 调试信息" . print_r(curl_getinfo($ch), true));
			curl_close($ch);
		}

		if($output)
		{
$logger->debug("正在手机充值，经历过$tryCount 次尝试后，从($url)返回的结果" . $output);
			$output = iconv('gbk', 'utf-8', $output);
			$output = str_replace("GB2312", "utf-8", $output);
			$xml = simplexml_load_string($output);
$logger->debug("正在手机充值，转换完成xml");
			return $xml;
		}
		else
		{
$logger->error("手机充值不成功，经历过$tryCount 次尝试后，($url) 没有返回数据");
		}
	}
}